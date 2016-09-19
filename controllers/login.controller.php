<?php

class LoginController extends Controller
{

    public function __construct ($data = array())
    {
        include ('../models/login.php');//todo разобраться с инклудом
        parent::__construct($data);
        $this->model = new Login();
    }

    public function user_login (){
        if ( $_POST && isset($_POST['email']) && isset($_POST['password']) ) {
            $user = $this->model->getByMail($_POST['email']);
            $password = md5(Config::get('salt') . $_POST['password']);

            if ( $password == $user['password'] ) {
                Session::set('user_id', $user['user_id']);
                Session::set('name', $user['user_name']);
                Session::set('role', $user['role']);
                Router::redirect($_SERVER ['HTTP_REFERER']);
                //Router::redirect('/pages/index/');//todo сделать индикацию входа для пользователя
            } else {
                Router::redirect($_SERVER ['HTTP_REFERER']);
            }
        }
    }

    public function user_logout (){
        Session::destroy();
        Router::redirect($_SERVER ['HTTP_REFERER']);
    }

    public function registration(){
        print_r($_POST);
        if ($_POST){
            if ($this->model->saveUserToDb($_POST)) {
                $user = $this->model->getByMail($_POST['email']);
                Session::set('user_id', $user['user_id']);
                Session::set('name', $user['user_name']);
                Session::set('role', $user['role']);
                Router::redirect('/pages/index');
            } else {
                Router::redirect('/pages/index/');
            }
        }
    }

    public function admin_login(){}

    public function admin_index () {
        if ( $_POST && isset($_POST['email']) && isset($_POST['password']) ) {
            $user = $this->model->getByMail($_POST['email']);
            $password = md5(Config::get('salt') . $_POST['password']);

            if ($password == $user['password'] ) {
                Session::set('name', $user['user_name']);
                Session::set('role', $user['role']);
                Router::redirect('/pages/admin_index/');
            }
            //Router::redirect('/admin/');
        }
    }

    public function admin_logout(){
        Session::destroy();
        Router::redirect('/admin/');
    }

}
