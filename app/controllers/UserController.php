<?php

class UserController extends BaseController {

    static public function register()
    {
        View::Make('user/user_register.html');
    }

    static public function edit($id)
    {
        /// TODO :: Also check if the user is admin (only admins are allowed to see user information).
        self::check_logged_in();

        View::Make('recipe/user_edit.html', array('user' => User::find($id)));
    }

    static public function store()
    {
        $params = $_POST;

        $user = new User(array(
            'username' => $params['username'],
            'password' => $params['password'],
            'admin' => 'false'
        ));
        $errors = $user->errors();

        if(count($errors) == 0)
        {
            $user->save();
            Redirect::to('/user/show/' . $user->id, array('message' => "Käyttäjä rekisteröity!"));
        }
        else
        {
            Redirect::to('/user/register', array('errors' => $errors, 'attributes' => $params));
        }
    }

    static public function login()
    {
        View::make('user/user_login.html');
    }

    static public function handle_login()
    {
        $params = $_POST;

        $user = User::authenticate($params['username'], $params['password']);

        if(!$user){
            Redirect::to('/login', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
        }else{
            $_SESSION['user'] = $user->id;

            Redirect::to('/recipe/list', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
        }
    }

    static public function log_out()
    {
        $_SESSION['user'] = null;
        Redirect::to('/login', array('message', 'Olet kirjautunut ulos!'));
    }
}