<?php

class IndexController extends BaseController{

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
}
