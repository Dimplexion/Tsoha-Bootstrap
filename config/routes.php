<?php

$routes->get('/', function() {
    IndexController::index();
});

$routes->get('/hiekkalaatikko', function() {
    DebugController::sandbox();
});

/*
 * "Get" routes for recipes.
 */

$routes->get('/recipe/list', function() {
    RecipeController::index();
});

$routes->get('/recipe/show/:id', function($id) {
    RecipeController::show($id);
});

$routes->get('/recipe/new', function() {
    RecipeController::create();
});

/*
 * "Post" routes for recipes.
 */

$routes->get('/recipe/edit/:id', function($id) {
    RecipeController::edit($id);
});

$routes->post('/recipe/add', function() {
    RecipeController::store();
});

$routes->post('/recipe/edit/:id', function($id) {
    RecipeController::update($id);
});

$routes->post('/recipe/destroy/:id', function($id) {
    RecipeController::destroy($id);
});

/*
 * "Get" routes for users.
 */

$routes->get('/user/list', function() {
    UserController::index();
});

$routes->get('/user/register', function() {
    UserController::register();
});

$routes->get('/user/edit/:id', function($id) {
    UserController::edit($id);
});

$routes->get('/user/login', function() {
    UserController::login();
});

$routes->get('/user/show/:id', function($id) {
    UserController::show($id);
});

/*
 * "Post" routes for users.
 */

$routes->post('/user/login', function() {
    UserController::handle_login();
});

$routes->post('/user/logout', function() {
    UserController::log_out();
});

$routes->post('/user/add', function() {
    UserController::store();
});

$routes->post('/user/edit/:id', function($id) {
    UserController::update($id);
});

$routes->post('/user/destroy/:id', function($id) {
    UserController::destroy($id);
});