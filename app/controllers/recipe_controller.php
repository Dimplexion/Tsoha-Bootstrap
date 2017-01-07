<?php

class RecipeController extends BaseController
{
    public static function index_recipe()
    {
        self::check_logged_in();

        $recipe_suggestions = DrinkRecipe::all_suggestions_for_user();
        $recipes = DrinkRecipe::all_approved();
        View::make('recipe/recipe_list.html', array(
            'user_logged_in' => self::get_user_logged_in(),
            'recipes' => $recipes,
            'suggestions' => $recipe_suggestions)
        );
    }

    public static function show_recipe($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);

        // TODO :: Add getting ingredients from the database.
        $ingredients = array(array(
            'name' => 'Rommi (test)',
            'amount' => 4
        ));

        View::Make('recipe/recipe_show.html',
                    array(
                        'id' => $id,
                        'recipe' => $recipe,
                        'ingredients' => $ingredients
                    ));
    }

    public static function new_recipe()
    {
        self::check_logged_in();

        View::Make('recipe/recipe_new.html');
    }

    public static function edit_recipe($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        View::Make('recipe/recipe_edit.html', array('attributes' => $recipe));
    }

    public static function update_recipe($id)
    {
        self::check_logged_in();

        $params = $_POST;

        $approved = 'false';
        if(isset($params['approved']) &&
            $params['approved'] === 'true')
        {
            $approved = 'true';
        }

        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'owner_id' => $params['owner_id'],
            'approved' => $approved
        );

        $recipe = new DrinkRecipe($attributes);
        $errors = $recipe->errors();

        if(count($errors) == 0)
        {
            $recipe->update();
            Redirect::to('/recipe/show/' . $recipe->id, array('message' => 'Drinkkireseptiä on muokattu onnistuneesti!'));
        }
        else
        {
            View::make('recipe/edit', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function store_recipe()
    {
        self::check_logged_in();

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

        // TODO :: Also check errors in ingredients here

        $errors = $recipe->errors();

        if(count($errors) == 0)
        {
            $recipe->save();
            Redirect::to('/recipe/show/' . $recipe->id, array('message' => "Drinkkireseptiehdotus lisätty!"));
        }
        else
        {
            Redirect::to('/recipe/new', array('errors' => $errors, 'attributes' => $params));
        }

    }

    public static function destroy_recipe($id)
    {
        self::check_logged_in();

        $recipe = new DrinkRecipe(array('id' => $id));
        $recipe->destroy();

        Redirect::to('/recipe/list', array('message' => 'Peli on poistettu onnistuneesti!'));
    }
}