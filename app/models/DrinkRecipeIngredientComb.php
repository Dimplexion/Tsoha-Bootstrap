<?php

class DrinkRecipeIngredientComb extends BaseModel
{
    public $id, $recipe_id, $ingredient_id, $amount;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
        //$this->validators = array('validate_name');
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO DrinkRecipeIngredientComb (Recipe, Ingredient, Amount) VALUES (:recipe_id, :ingredient_id, :amount) RETURNING id');
        $query->execute(array(
            'recipe_id' => $this->recipe_id,
            'ingredient_id' => $this->ingredient_id,
            'amount' => $this->amount
        ));
        $row = $query->fetch();

        $this->id = $row['id'];
    }
}