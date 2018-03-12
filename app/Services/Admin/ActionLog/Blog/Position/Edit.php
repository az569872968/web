<?php namespace App\Services\Admin\ActionLog\Blog\Position;

use App\Services\Admin\AbstractActionLog;
use App\Events\Admin\ActionLog;
use Request, Lang;

/**
 * 文章推荐位管理操作日志
 *
 * @author jiang <mylampblog@163.com>
 */
class Edit extends AbstractActionLog
{
    /**
     * 编辑文章推荐位时的日志记录
     */
    public function handler()
    {
        if(Request::method() !== 'POST') return false;
        if( ! $this->isLog()) return false;
        $info = $this->getExtDatas();
        event(new ActionLog(Lang::get('actionlog.edit_position', ['name' => $info['param']['name']])));
    }
    
}
