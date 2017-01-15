<?php

class DrinkRecipeIngredient extends BaseModel
{
    public $id, $recipe_id, $ingredient_id, $amount;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->validators = array('validate_amount');
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO DrinkRecipeIngredient (Recipe, Ingredient, Amount) VALUES (:recipe_id, :ingredient_id, :amount) RETURNING id');
        $query->execute(array(
            'recipe_id' => $this->recipe_id,
            'ingredient_id' => $this->ingredient_id,
            'amount' => $this->amount
        ));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function remove_by_recipe_id($recipe_id)
    {
        $query = DB::connection()->prepare('DELETE FROM DrinkRecipeIngredient WHERE Recipe = :id');
        $query->execute(array(
            'id' => $recipe_id,
        ));

        $query->fetch();
    }

    public function validate_amount()
    {
        $errors = array();
        if(!is_numeric($this->amount))
        {
            $errors[] = 'Ainesosan määrän on oltava numero!';
        }
        elseif($this->amount < 0)
        {
            $errors[] = 'Ainesosan määrän on oltava epänegatiivinen!';
        }

        return $errors;
    }
}