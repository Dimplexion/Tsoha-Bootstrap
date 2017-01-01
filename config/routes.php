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

$routes->get('/recipe/edit', function() {
    RecipeController::edit_recipe();
});

// Routes for actions.
$routes->post('/recipe/add', function() {
    RecipeController::store_recipe();
});
