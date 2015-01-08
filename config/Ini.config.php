<?php
$_CFG['log'] = 5;
/**
 * 记录日志
 * @param string $level 日志级别
 * @param string $msg 日志内容
 */
function log_message( $level, $msg )
{
    // 文件日志对象
    $logger = $GLOBALS['di']->get('logger');
    // 设置的日志等级
    $set_level = $GLOBALS['_CFG']['log'];
    // 不同等级开启的日志级别
    $config = array(
        array('error'),
        array('error', 'warning'),
        array('error', 'warning', 'debug'),
        array('error', 'warning', 'debug', 'info')
    );
    // 设置的日志等级为5 是开启所有
    if(  $set_level == 5 || in_array($level, $config[$set_level - 1]) )
    {
        $before_str = '';
        /*查看调用流程*/
        /*try
        {
            $debug_info = debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT & DEBUG_BACKTRACE_PROVIDE_OBJECT );

            $class = $debug_info[1]['class'];
            $fun   = $debug_info[1]['function'];
            $type  = $debug_info[1]['type'];

            $before_str = "(${class}${type}${fun}=>)";
        }
        catch(Exception $e) {

        }*/
        /*查看调用流程*/
        $logger ->log($level, $before_str . $msg);
    }
}
