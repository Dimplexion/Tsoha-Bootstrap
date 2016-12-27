<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/resepti_show', function() {
    HelloWorldController::resepti_show();
});

$routes->get('/resepti_list', function() {
    HelloWorldController::resepti_list();
});

$routes->get('/resepti_edit', function() {
    HelloWorldController::resepti_edit();
});

