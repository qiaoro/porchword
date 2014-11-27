<?php
error_reporting( E_ALL ^ E_NOTICE );    // 屏蔽掉未定义错误
ini_set( 'display_errors', 0 );         // 页面上不显示错误
ini_set( 'log_errors', 'on' );          // 错误输出到日志
defined('REALPATH') or define('REALPATH', str_replace("\\", '/', __DIR__ . '/'));   // 程序运行的绝对路径(index.php所在的目录)
ini_set( 'error_log', REALPATH . 'logs/php_error.log' );    // PHP错误日志输出到文件中
set_include_path( REALPATH . 'include' . PATH_SEPARATOR . get_include_path() ); // 设置include_path路径
date_default_timezone_set( 'Asia/Shanghai' );
try {
    $config = new Phalcon\Config\Adapter\Json( REALPATH . 'config/Config.json' );  // 获取配置文件内容
    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        REALPATH . $config ->application ->controllersDir,
        REALPATH . $config ->application ->modelsDir
    ))->register();
    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
    $di->set('db', function() use($config){  // 数据库操作PDO for mysql 请确保安装并开启了PDO
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config ->database ->host,
            "username" => $config ->database ->username,
            "password" => $config ->database ->password,
            "dbname" => $config ->database ->name
        ));
    });
    //Setup the view component
    $di->set('view', function() use($config){   // 传递变量$config 在闭包中访问
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir(REALPATH . $config ->application ->viewsDir);
        return $view;
    });
    //Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        return $url;
    });
    $di->set('logger', function() use($config){ // 程序日志
        $logPath = REALPATH . $config ->application ->logDir . 'log-' . date('Y-m-d') . '.log'; // 程序日志路径
        $logger = new Phalcon\Logger\Adapter\File($logPath);
        $formatter = new Phalcon\Logger\Formatter\Line("[%date%][%type%] - %message%");         // 日志的格式
        $formatter->setDateFormat('Y-m-d H:i:s');                                               // 设置日志的日期格式
        $logger->setFormatter($formatter);
        return $logger;
    });
    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch(\Phalcon\Exception $e) { // 出错的处理 这里采用重定向到404
    //echo "PhalconException: ", $e->getMessage();
    $response = new \Phalcon\Http\Response();
    $response->setRawHeader("HTTP/1.1 404 Not Found");
    $response->redirect( '404.html', false );
    $response->send();
}
?>