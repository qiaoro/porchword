<?php
include_once (REALPATH."/app/library/BaseController.php");
class TestController extends BaseController
{

    public function indexAction()
    {
        echo phpinfo();
        $this ->view ->disable();
        $this ->log('goto test');
    }
    public function personAction(){
        $this ->log('-goto person-');
    }

}
?>