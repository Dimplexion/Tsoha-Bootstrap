<?php

  class BasicController extends BaseController{

      /// TODO :: Should be moved? Or the class needs to be renamed.
    public static function index()
    {
        if(BaseController::check_logged_in(false))
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
