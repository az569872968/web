<?php

/**
 * socket 服务
 *
 * @author jiang <mylampblog@163.com>
 */
class WebSocketServer
{
    /**
     * 从浏览器post过来的数据
     * 
     * @var array
     */
    private $receiveData;

    /**
     * websocket 服务端
     * 
     * @var object
     */
    private $serv;

    /**
     * 服务端侦听的ip
     * 
     * @var string
     */
    private $listenIp;

    /**
     * 服务端侦听的端口
     * 
     * @var int
     */
    private $listenPort;

    /**
     * 相关的配置文件
     * 
     * @var array
     */
    private $config;

    /**
     * 初始化配置文件
     * 
     * @param  array $config
     */
    public function initConfig($config)
    {
        $this->config = $config;
        $this->listenIp = '0.0.0.0'; //$config['online_listen_ip'];
        $this->listenPort = $config['online_listen_port'];
    }

    /**
     * 初始化websocket服务端
     */
    public function initSwooleWebSocketServer()
    {
        $this->serv = new swoole_websocket_server($this->listenIp, $this->listenPort);
        $this->onOpen()->onMessage()->onClose();
        $this->serv->start();
        return $this;
    }

    /**
     * 自动加载
     */
    public function initVender()
    {
        require __DIR__.'/vendor/autoload.php';
        return $this;
    }

    /**
     * 数据统一处理入口
     * 
     * @param  int $fd     socket id
     * @param  string $data   post过来的数据
     * @param  string $onType 处理的类型
     * @return string json data
     */
    private function handle($fd, $data, $onType = '')
    {
        if( ! isset($data['controller'], $data['action'], $data['params'])) return false;
        $controller = ucfirst(strtolower($data['controller']));
        $action = strtolower($data['action']);
        $params = $data['params'];
        $touchClass = '\\App\\Services\\Home\Soket\\'.$controller;
        if(class_exists($touchClass)) {
            $classObject = new $touchClass($this->config);
            if(method_exists($classObject, $action)) return $classObject->$action($fd, $params, $onType);
        }
        return false;
    }

    /**
     * socket 连接打开的事件
     */
    private function onOpen()
    {
        $this->serv->on('Open', function($server, $req) {
            echo "connection open: ".$req->fd."\n";
        });
        return $this;
    }

    /**
     * socket 收到信息的事件
     */
    private function onMessage()
    {
        $this->serv->on('Message', function($server, $frame) {
            echo "on message from {$frame->fd}, data {$frame->data}\n";
            $data = $this->receiveData = json_decode($frame->data, TRUE);
            $result = $this->handle($frame->fd, $data, 'onMessage');
            if( ! isset($result['fdList'], $result['nums']) or ! is_array($result['fdList'])) return false;
            $this->responseToClient($server, $result['fdList'], $result['nums']);
        });
        return $this;
    }

    /**
     * socket 连接关闭的时候事件
     */
    private function onClose()
    {
        $this->serv->on('close', function($server, $fd) {
            echo "connection close: ".$fd."\n";
            $result = $this->handle($fd, $this->receiveData, 'onClose');
            if( ! isset($result['fdList'], $result['nums']) or ! is_array($result['fdList'])) return false;
            $this->responseToClient($server, $result['fdList'], $result['nums']);
        });
        return $this;
    }

    /**
     * 返回数据到客户端
     * @param  array $fdList fd数组
     * @param  int $nums   在线人数
     */
    private function responseToClient($server, $fdList, $nums)
    {
        foreach($fdList as $fdkey) {
            if( ! $server->exist($fdkey)) continue;
            echo "send message to ".$fdkey."\n";
            $server->push($fdkey, $this->jsonResponse($nums, true));
        }
    }

    /**
     * 格式化json数据
     * 
     * @param  mixed  $message 返回的数据
     * @param  boolean $status  是否成功
     * @return string           json
     */
    private function jsonResponse($message, $status = false)
    {
        $status = $status ? 'success' : 'error';
        $json = array('result' => $status, 'message' => $message);
        return json_encode($json);
    }
}

//当前路径
define('SWOOLE_SOCKET_BASE_PATH', __DIR__);
//相关的配置文件
$config = require(SWOOLE_SOCKET_BASE_PATH.'/config/swoole.php');
//启动服务
$obj = new WebSocketServer();
$obj->initConfig($config);
$obj->initVender()->initSwooleWebSocketServer();