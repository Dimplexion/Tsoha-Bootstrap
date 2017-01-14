<?php

class RecipeController extends BaseController
{
    public static function index()
    {
        self::check_logged_in();

        $recipe_suggestions = DrinkRecipe::all_suggestions_for_user();
        $recipes = DrinkRecipe::all_approved();
        View::make('recipe/recipe_list.html', array(
            'recipes' => $recipes,
            'suggestions' => $recipe_suggestions)
        );
    }

    public static function show($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        /// TODO :: Test if this works.
        if(!$recipe->approved)
        {
            self::check_user_owner_or_admin($recipe);
        }

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

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

        View::Make('recipe/recipe_edit.html', array(
            'recipe' => $recipe)
        );
    }

    public static function update($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

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
            'owner_id' => $recipe->owner_id,
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

        // TODO :: Test approving without admin rights.
        $params = $_POST;

        $approved = 'false';
        if($params['approved'] == 'true' &&
            self::is_user_admin())
        {
            $approved = 'true';
        }

        $recipe = new DrinkRecipe(array(
            'name' => $params['name'],
            'owner_id' => self::get_user_logged_in()->id,
            'approved' => $approved
        ));

        $recipe->save();
        $errors = $recipe->errors();

        if(count($errors) == 0)
        {
            // TODO :: Make dynamic.
            $ingredient1 = new Ingredient(array(
                'name' => $params['ingredient1']
            ));
            $ingredient1->save();

            $ingredient2 = new Ingredient(array(
                'name' => $params['ingredient2']
            ));
            $ingredient2->save();

            $ingredient1_comb = new DrinkRecipeIngredientComb( array(
                'recipe_id' => $recipe->id,
                'ingredient_id'=> $ingredient1->id,
                'amount' => $params['ingredient1_amount']
            ));

            $ingredient1_comb->save();

            $ingredient2_comb = new DrinkRecipeIngredientComb( array(
                'recipe_id' => $recipe->id,
                'ingredient_id'=> $ingredient2->id,
                'amount' => $params['ingredient2_amount']
            ));

            $ingredient2_comb->save();

            // TODO :: Also check errors in ingredients here
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

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

        $recipe = new DrinkRecipe(array('id' => $id));
        $recipe->destroy();

        Redirect::to('/recipe/list', array('message' => 'Drinkkiresepti on poistettu onnistuneesti!'));
    }

    public static function check_user_owner_or_admin($recipe)
    {
        $user = self::get_user_logged_in();
        if(!$user || ($user->id !== $recipe->owner_id && !$user->admin))
        {
            Redirect::to('/recipe/list', array('errors' => array('Ei oikeuksia katsoa reseptiä.')));
        }
    }
}