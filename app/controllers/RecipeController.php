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
        /// TODO :: Test that this works.
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

        // By default add two empty ingredients.
        $recipe = array('ingredients' => array('',''), 'ingredient_amounts'=> array(null, null));
        View::Make('recipe/recipe_new.html', array('recipe' => $recipe));
    }

    public static function store()
    {
        self::check_logged_in();

        // TODO :: Test approving without admin rights.
        $params = $_POST;

        $approved = 'false';
        if(isset($params['approved']) && $params['approved'] == 'true' && self::is_user_admin())
        {
            $approved = 'true';
        }

        $recipe = new DrinkRecipe(array(
            'name' => $params['name'],
            'owner_id' => self::get_user_logged_in()->id,
            'approved' => $approved
        ));

        $errors = $recipe->errors();
        if(count($errors) > 0)
        {
            Redirect::to('/recipe/new', array('errors' => $errors, 'recipe' => $params));
        }
        $recipe->save();

        $errors = self::add_ingredients($recipe, $params['ingredients'], $params['ingredient_amounts']);
        if($errors != null)
        {
            $recipe->destroy();
            Redirect::to('/recipe/new', array('errors' => $errors, 'recipe' => $params));
        }

        Redirect::to('/recipe/show/' . $recipe->id, array('message' => "Drinkkireseptiehdotus lisätty!"));
    }

    public static function add_ingredients($recipe, $ingredients, $ingredient_amounts)
    {
        for($i = 0; $i < count($ingredients); $i++)
        {
            $ingredient = new Ingredient(array(
                'name' => $ingredients[$i]
            ));

            $errors = $ingredient->errors();
            if(count($errors) > 0)
            {
                return $errors;
            }
            $ingredient->save();

            $ingredient_comb = new DrinkRecipeIngredientComb( array(
                'recipe_id' => $recipe->id,
                'ingredient_id'=> $ingredient->id,
                'amount' => $ingredient_amounts[$i]
            ));


            $errors = $ingredient_comb->errors();
            if(count($errors) > 0)
            {
                return $errors;
            }
            $ingredient_comb->save();
        }

        return null;
    }

    public static function edit($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

        $ingredients = Ingredient::find_by_recipe_id($recipe->id);
        View::Make('recipe/recipe_edit.html', array(
            'recipe' => $recipe,
            'ingredients' => $ingredients
        ));
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

        DrinkRecipeIngredientComb::remove_by_recipe_id($recipe->id);

        foreach($params['ingredients'] as $index=>$ingredient_name)
        {
            $ingredient = Ingredient::find_by_name($ingredient_name);
            if($ingredient === null)
            {
                $ingredient = new Ingredient(array('name' => $ingredient_name));

                /// TODO :: Handle errors. (Refactor into separate function?)
                $ingredient->save();
            }

            $comb = new DrinkRecipeIngredientComb(array(
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id,
                'amount' => $params['ingredient_amounts'][$index]
            ));
            /// TODO :: Handle errors.
            $comb->save();
        }

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