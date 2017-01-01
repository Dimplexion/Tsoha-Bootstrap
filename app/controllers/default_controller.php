<?php

  class PageController extends BaseController{

    public static function index(){
         View::make('index.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::Make('helloworld.html');
    }

  }
