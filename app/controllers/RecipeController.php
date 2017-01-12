<?php

class RecipeController extends BaseController
{
    public static function index()
    {
        self::check_logged_in();

        $recipe_suggestions = DrinkRecipe::all_suggestions_for_user();
        $recipes = DrinkRecipe::all_approved();
        View::make('recipe/recipe_list.html', array(
            'user_logged_in' => self::get_user_logged_in(),
            'is_user_admin' => self::is_user_admin(),
            'recipes' => $recipes,
            'suggestions' => $recipe_suggestions)
        );
    }

    public static function show($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        $ingredients = Ingredient::find_by_recipe_id($recipe->id);

        View::Make('recipe/recipe_show.html',
                    array(
                        'id' => $id,
                        'recipe' => $recipe,
                        'ingredients' => $ingredients
                    ));
    }

    public static function create()
    {
        self::check_logged_in();

        View::Make('recipe/recipe_new.html');
    }

    public static function edit($id)
    {
        self::check_logged_in();

        View::Make('recipe/recipe_edit.html', array(
            'is_user_admin' => self::is_user_admin(),
            'recipe' => DrinkRecipe::find($id))
        );
    }

    public static function update($id)
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
            Redirect::to('/recipe/edit/' . $id, array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function store()
    {
        self::check_logged_in();

        $logged_user = self::get_user_logged_in();

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
            'owner_id' => $logged_user->id,
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
            Redirect::to('/recipe/new', array('errors' => $errors, 'recipe' => $params));
        }
    }

    public static function destroy($id)
    {
        self::check_logged_in();

        $recipe = new DrinkRecipe(array('id' => $id));
        $recipe->destroy();

        Redirect::to('/recipe/list', array('message' => 'Drinkkiresepti on poistettu onnistuneesti!'));
    }
}