<?php

// Routes for displaying pages.
$routes->get('/', function() {
    PageController::index();
});

$routes->get('/hiekkalaatikko', function() {
    PageController::sandbox();
});

$routes->get('/recipe/list', function() {
    RecipeController::index_recipe();
});

$routes->get('/recipe/show/:id', function($id) {
    RecipeController::show_recipe($id);
});

$routes->get('/recipe/new', function() {
    RecipeController::new_recipe();
});

// Show the editing form
$routes->get('/recipe/edit/:id', function($id) {
    RecipeController::edit_recipe($id);
});

$routes->get('/login', function() {
    UserController::login();
});

// Routes for actions.

$routes->post('/recipe/add', function() {
    RecipeController::store_recipe();
});

// Update the database
$routes->post('/recipe/edit/:id', function($id) {
    RecipeController::update_recipe($id);
});

// Delete the drink recipe
$routes->post('/recipe/destroy/:id', function($id) {
    RecipeController::destroy_recipe($id);
});

$routes->post('/login', function() {
    UserController::handle_login();
});

