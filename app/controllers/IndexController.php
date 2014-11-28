<?php
include_once (REALPATH."/app/library/BaseController.php");
class IndexController extends BaseController
{

    public function indexAction()
    {
        $this ->log('goto index');
    }
    public function personAction(){
        $this ->log('-goto person-');
    }

}
?>