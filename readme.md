# laravel 个人博客系统

**注意：**

1、安装方法请见master分支说明，此外需要安装Redis服务，并配置config/database.php下的redis。

2、需要安装swoole扩展。

3、配置下config/swoole.php侦听的端口。

4、为了支持当前用户在线功能，在根目录下启动swoole socket 服务器：

* php socket_server.php
* php socket_server_for_flash.php

5、当前用户在线人数，基于swoole扩展以及websocket，人数会进行广播更新，默认三分钟没有操作就当作下线，可以在config/swoole.php中修改这个时间

6、文章浏览数，每10个更新一次到数据库。

7、用户登陆使用的是 `http://git.oschina.net/ctk/laravel5_login`，请先安装并配置 `config/oauth.php`。

一、目录已有功能：

- 1、文章发布及管理
- 2、文章评论及管理
- 3、文章分类及管理
- 4、文章tag及管理
- 5、文章搜索（基于sphinx）
- 6、推荐位管理
- 7、RSS
- 8、七天热榜
- 9、总榜
- 10、当前用户在线人数
- 11、文章浏览数
- 12、用户登陆

二、近期计划的

todo...

三、日志

请查看 changelog.md

四、一点预览

老图了，更多功能请见最新版本。

![enter image description here](http://static.oschina.net/uploads/space/2015/0913/220520_9hIP_1777357.png)
![enter image description here](http://static.oschina.net/uploads/space/2015/0913/220521_WUuX_1777357.png)