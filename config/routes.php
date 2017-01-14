<?php

// Routes for displaying pages.
$routes->get('/', function() {
    BasicController::index();
});

$routes->get('/hiekkalaatikko', function() {
    BasicController::sandbox();
});

$routes->get('/recipe/list', function() {
    RecipeController::index();
});

$routes->get('/recipe/show/:id', function($id) {
    RecipeController::show($id);
});

$routes->get('/recipe/new', function() {
    RecipeController::create();
});

// Show the editing form
$routes->get('/recipe/edit/:id', function($id) {
    RecipeController::edit($id);
});

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

// Routes for actions.

$routes->post('/recipe/add', function() {
    RecipeController::store();
});

// Update the database
$routes->post('/recipe/edit/:id', function($id) {
    RecipeController::update($id);
});

// Delete the drink recipe
$routes->post('/recipe/destroy/:id', function($id) {
    RecipeController::destroy($id);
});

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