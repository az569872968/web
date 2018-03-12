<?php namespace App\Http\Controllers\Home;

/**
 * 博客RSS
 *
 * @author jiang <mylampblog@163.com>
 */
class RssController extends Controller
{
    /**
     * 博客Rss
     */
    public function index()
    {
        $contentProcess = new \App\Services\Home\Content\Process();
        $rss = $contentProcess->Rss();
        return response($rss, 200)->header('Content-Type', 'text/xml');
    }

}