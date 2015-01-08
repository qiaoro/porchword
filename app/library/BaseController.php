<?php
include_once REALPATH . 'config/Ini.config.php';
class BaseController extends \Phalcon\Mvc\Controller
{
    /**
     * 程序日志
     * @param {string} $msg 日志消息
     * @param {string} $type 消息级别 默认info
     */
    public function log($msg, $type = 'info')
    {
        $this ->logger ->log($type, $msg);
    }
}
?>