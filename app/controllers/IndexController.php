<?php
include_once (REALPATH."/app/library/BaseController.php");
class IndexController extends BaseController
{

    public function indexAction()
    {
        echo "<h1>Hello!</h1>";
        echo phpinfo();
    }

}
?>