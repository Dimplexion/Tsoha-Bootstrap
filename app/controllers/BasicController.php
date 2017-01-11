<?php

  class BasicController extends BaseController{

    public static function index()
    {
        /// TODO :: Should have a separate function for checking if user has logged in without the redirecting.
        if(BaseController::get_user_logged_in(false))
        {
            Redirect::to('/recipe/list');
        }
        else
        {
            View::make('index.html');
        }
    }

    public static function sandbox(){
        $drink = new DrinkRecipe(array(
            'name' => 'a',
            'owner_id' => 1,
            'approved' => 1
        ));
        $errors = $drink->errors();

        Kint::dump($errors);
    }

  }
