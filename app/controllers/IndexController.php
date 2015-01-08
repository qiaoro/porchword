<?php
include_once (REALPATH."/app/library/BaseController.php");
class IndexController extends BaseController
{

    public function indexAction()
    {
    	// $user_name ='童筱佑';
    	// $user_password = '123456';
    	// $db = $this ->db;
    	// $db ->execute('set names utf8');
    	// $res = $db  ->query('select count(id) from userinfo') ->numRows();
		// $db ->execute("INSERT INTO userinfo(`user_name`,`user_password`) values(?,PASSWORD($user_password))", [$user_name]);
		// echo $db ->affectedRows() . "<br />";
    	// $this->view ->disable();
        $this ->log('indexAction');
    }
    public function personAction(){
        $this ->log('-goto person-');
    }

}
?>