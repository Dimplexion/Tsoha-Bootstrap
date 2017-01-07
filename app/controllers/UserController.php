<?php

class UserController extends BaseController{

    static public function login()
    {
        View::make('user/login.html');
    }

    static public function handle_login()
    {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if(!$user){
            Redirect::to('/login', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        }else{
            $_SESSION['user'] = $user->id;

            Redirect::to('/recipe/list', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
        }
    }
}