<?php

  class HelloWorldController extends BaseController{

    public static function index(){
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        // View::make('home.html')
        echo 'Tämä on etusivu!';
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
