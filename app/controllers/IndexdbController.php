<?php
include_once (REALPATH."/app/library/BaseController.php");
class IndexdbController extends BaseController
{
    function loginAction() {
        $this-> log('我是来登录的');
        $username = $_POST['username'];
        $userpassword = $_POST['userpassword'];
        $this-> log($username);
        $this-> log($userpassword);
        if(!empty($userpassword) && !empty($username)){
            $db = $this ->db;
            $sqlStr = "select user_name from userinfo where user_name= :name and user_password= :password";
            $statement = $db ->prepare($sqlStr);
            $statement->bindParam(':name', $username );
            $statement->bindParam(':password', $userpassword );
            $this -> log(json_encode($statement));
            if( $statement ->execute() ) {
                $res = $statement ->fetchAll();
                log_message('info', json_encode($res));
            }
           /* if(empty($robots)){
                echo "账号不存在";
            }else{
                // 验证成功
            }*/
        }else{
            if(empty($username)){
                echo "请输入账号";
            }else{
                echo "请设置密码";
            }
        }
    }
    function passportAction(){
        $this-> log('我是来注册账号的');
    }
}
?>