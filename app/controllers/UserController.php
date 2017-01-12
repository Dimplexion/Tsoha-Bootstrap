<?php

class UserController extends BaseController {

    public static function index()
    {
        self::check_admin();

        $users = User::all();
        View::make('user/user_list.html', array(
                'is_user_admin' => self::is_user_admin(),
                'users' => $users
            )
        );
    }

    static public function register()
    {
        View::Make('user/user_register.html');
    }

    static public function show($id)
    {
        self::check_admin();

        View::Make('user/user_show.html', array(
            'is_user_admin' => self::is_user_admin(),
            'user' => User::find($id))
        );
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
            Redirect::to('/user/login', array('message' => "Käyttäjä rekisteröity!"));
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
            Redirect::to('/user/login', array('errors' => array('Väärä käyttäjätunnus tai salasana!'), 'username' => $params['username']));
        }else{
            $_SESSION['user'] = $user->id;

            Redirect::to('/recipe/list', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
        }
    }

    static public function log_out()
    {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message', 'Olet kirjautunut ulos!'));
    }
}