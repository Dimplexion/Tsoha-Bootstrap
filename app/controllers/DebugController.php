<?php

  class DebugController extends BaseController{

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
