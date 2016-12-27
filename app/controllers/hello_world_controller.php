<?php

  class HelloWorldController extends BaseController{

    public static function index(){
         View::make('index.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::Make('helloworld.html');
    }

    public static function resepti_list(){
      View::Make('resepti_list.html');
    }

    public static function resepti_show() {
        View::Make('resepti_show.html');
    }

      public static function resepti_edit() {
          View::Make('resepti_edit.html');
      }
  }
