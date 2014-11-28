<?php
include_once (REALPATH."/app/library/BaseController.php");
class IndexController extends BaseController
{

    public function indexAction()
    {
        echo phpinfo();
        $this ->logger ->log("这是log打印的消息,默认级别是debug");
        $this ->logger ->log('info', "这是log打印的消息,我是info");
        $this ->logger ->debug("这是debug级别的消息");
        $this ->logger ->info("这是info级别的消息");
        $this ->logger ->warning("这是warning级别的消息");
        $this ->logger ->error("这是error级别的消息");
        $this ->log('我是封装的日志方法');
    }

}
?>