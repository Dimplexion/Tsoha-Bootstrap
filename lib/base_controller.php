<?php

  class BaseController{

    public static function get_user_logged_in(){

        if(isset($_SESSION['user'])){
            $user = User::find($_SESSION['user']);

            return $user;
        }

        return null;
    }

    public static function check_logged_in($should_redirect=true){
        if(self::get_user_logged_in() == null)
        {
            if($should_redirect === true)
            {
                Redirect::to('/user/login');
            }
            else
            {
                return false;
            }
        }

        return true;
    }
  }
