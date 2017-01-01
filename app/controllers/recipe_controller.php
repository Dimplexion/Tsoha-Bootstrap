<?php

class RecipeController extends BaseController
{
    public static function index_recipe()
    {
        $recipes = DrinkRecipe::all_approved();
        View::make('recipe/recipe_list.html', array('recipes' => $recipes));
    }

    public static function show_recipe($id)
    {
        $recipe = DrinkRecipe::find($id);

        // TODO :: Add getting ingredients from the database.
        $ingredients = array(array(
            'name' => 'Rommi (test)',
            'amount' => 4
        ));

        View::Make('recipe/recipe_show.html',
                    array(
                        'recipe' => $recipe,
                        'ingredients' => $ingredients
                    ));
    }

    public static function edit_recipe()
    {
        View::Make('recipe/recipe_edit.html');
    }

    public static function new_recipe()
    {
        View::Make('recipe/recipe_new.html');
    }

    public static function store_recipe()
    {
        // TODO :: Check here if the user has rights to approve the recipe himself.
        $params = $_POST;

        /*
         * TODO :: Iterate the given ingredients and add them dynamically.
         * For testing add the two hard coded ingredients.
         */
        $ingredients = array(
            array(
                'name' => $params['ingredient1'],
                'amount' => $params['ingredient1_amount']
            ),
            array(
                'name' => $params['ingredient2'],
                'amount' => $params['ingredient2_amount']
            )
        );

        // TODO :: Save the ingredients to database.

        // TODO ::  Set owner_id properly when logging in has been implemented.
        //          Currently defaults to first user.
        $recipe = new DrinkRecipe(array(
            'name' => $params['name'],
            'owner_id' => 1,
            'approved' => $params['approved']
        ));

        $recipe->save();

        Redirect::to('/recipe/show/' . $recipe->id, array('message', "Drinkkireseptiehdots lisätty!"));
    }
}