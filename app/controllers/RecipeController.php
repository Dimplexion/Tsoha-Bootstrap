<?php

class RecipeController extends BaseController
{
    public static function index()
    {
        self::check_logged_in();

        if(self::is_user_admin())
        {
            $recipe_suggestions = DrinkRecipe::all_suggestions();
        }
        else
        {
            $recipe_suggestions = DrinkRecipe::all_suggestions_for_user(self::get_user_logged_in()->id);
        }

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
        if(!$recipe)
        {
            Redirect::to('/recipe/list', array('errors' => array('Reseptiä ei löytynyt!')));
        }

        if(!$recipe->approved)
        {
            self::check_user_owner_or_admin($recipe);
        }

        $ingredients = Ingredient::find_by_recipe_id($recipe->id);

        View::Make('recipe/recipe_show.html',
                    array(
                        'id' => $id,
                        'recipe' => $recipe,
                        'ingredients' => $ingredients[0],
                        'ingredient_amounts' => $ingredients[1]
                    ));
    }

    public static function create()
    {
        self::check_logged_in();

        // By default add two empty ingredients.
        View::Make('recipe/recipe_new.html', array(
            'ingredients' => array('',''),
            'ingredient_amounts'=> array(null, null)
        ));
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
            Redirect::to('/recipe/new', array(
                'errors' => $errors,
                'recipe' => $recipe,
                'ingredients' => $params['ingredients'],
                'ingredient_amounts' => $params['ingredient_amounts']
            ));
        }
        $recipe->save();

        $errors = self::add_ingredients($recipe, $params['ingredients'], $params['ingredient_amounts']);
        if(count($errors) > 0)
        {
            $recipe->destroy();
            Redirect::to('/recipe/new', array(
                'errors' => $errors,
                'recipe' => $recipe,
                'ingredients' => $params['ingredients'],
                'ingredient_amounts' => $params['ingredient_amounts']
            ));
        }

        Redirect::to('/recipe/show/' . $recipe->id, array('message' => "Drinkkireseptiehdotus lisätty!"));
    }

    public static function add_ingredients($recipe, $ingredients, $ingredient_amounts)
    {
        $errors = array();
        for($i = 0; $i < count($ingredients); $i++)
        {
            $ingredient = Ingredient::find_by_name($ingredients[$i]);
            if(!$ingredient)
            {
                $ingredient = new Ingredient(array('name' => $ingredients[$i]));
                $errors = $ingredient->errors();
                $ingredient->save();

                if(count($errors) > 0)
                {
                    break;
                }
            }

            $ingredient_comb = new DrinkRecipeIngredientComb( array(
                'recipe_id' => $recipe->id,
                'ingredient_id'=> $ingredient->id,
                'amount' => $ingredient_amounts[$i]
            ));

            $errors = $ingredient_comb->errors();
            if(count($errors) > 0)
            {
                break;
            }
            $ingredient_comb->save();
        }

        return $errors;
    }

    public static function edit($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

        $ingredients_and_amounts = Ingredient::find_by_recipe_id($recipe->id);;
        $ingredients = $ingredients_and_amounts[0];
        $ingredient_amounts = $ingredients_and_amounts[1];
        View::Make('recipe/recipe_edit.html', array(
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'ingredient_amounts' => $ingredient_amounts
        ));
    }

    public static function update($id)
    {
        self::check_logged_in();

        $recipe = DrinkRecipe::find($id);
        self::check_user_owner_or_admin($recipe);

        $params = $_POST;

        $approved = 'false';
        if(isset($params['approved']) && $params['approved'] === 'true' && self::is_user_admin())
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
        self::check_errors_update($errors, $id, $attributes);
        $recipe->update();

        DrinkRecipeIngredientComb::remove_by_recipe_id($recipe->id);

        $errors = self::create_ingredients($recipe, $params['ingredients'], $params['ingredient_amounts']);
        self::check_errors_update($errors, $id, $attributes);

        Redirect::to('/recipe/show/' . $recipe->id, array('message' => 'Drinkkireseptiä on muokattu onnistuneesti!'));
    }

    public static function check_errors_update($errors, $id, $attributes)
    {
        if(count($errors) > 0)
        {
            Redirect::to('/recipe/edit/' . $id, array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function create_ingredients($recipe, $ingredients, $ingredient_amounts)
    {
        $errors = array();
        foreach($ingredients as $index=>$ingredient_name)
        {
            $ingredient = Ingredient::find_by_name($ingredient_name);
            if($ingredient === null)
            {
                $ingredient = new Ingredient(array('name' => $ingredient_name));

                $errors = $ingredient->errors();
                if(count($errors) > 0)
                {
                    break;
                }

                $ingredient->save();
            }

            $comb = new DrinkRecipeIngredientComb(array(
                'recipe_id' => $recipe->id,
                'ingredient_id' => $ingredient->id,
                'amount' => $ingredient_amounts[$index]
            ));

            $errors = $comb->errors();
            if(count($errors) > 0)
            {
                break;
            }
            $comb->save();
        }

        return $errors;
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