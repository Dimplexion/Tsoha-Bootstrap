<?php

class Ingredient extends BaseModel
{
    public $id, $name;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    public static function all()
    {
        $query = DB::connection()->prepare('SELECT * FROM Ingredient');
        $query->execute();
        $rows = $query->fetchAll();

        $ingredients = array();
        foreach ($rows as $row) {
            $ingredients[] = new Ingredient(array(
                'id' => $row['id'],
                'name' => $row['name'],
            ));
        }

        return $ingredients;
    }

    public static function find($id)
    {
        $query = DB::connection()->prepare('SELECT * FROM Ingredient WHERE ID = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if (!$row) {
            return null;
        }

        return new Ingredient(array(
            'id' => $row['id'],
            'name' => $row['name']
        ));
    }

    public static function find_by_name($name)
    {
        $query = DB::connection()->prepare('SELECT * FROM Ingredient WHERE Name = :name LIMIT 1');
        $query->execute(array('name' => $name));
        $row = $query->fetch();

        if (!$row) {
            return null;
        }

        return new Ingredient(array(
            'id' => $row['id'],
            'name' => $row['name']
        ));
    }

    public static function find_by_recipe_id($recipe_id)
    {
        $query = DB::connection()->prepare('SELECT Ingredient.id, Ingredient.name, DrinkRecipeIngredient.amount FROM DrinkRecipeIngredient 
                                              INNER JOIN Ingredient ON DrinkRecipeIngredient.Ingredient = Ingredient.ID 
                                              WHERE DrinkRecipeIngredient.Recipe = :recipe_id');
        $query->execute(array('recipe_id' => $recipe_id));
        $rows = $query->fetchall();

        if (!$rows) {
            return null;
        }

        $ingredients = array();
        $ingredient_amounts = array();
        foreach ($rows as $row)
        {
            $ingredients[] = new Ingredient(array(
                    'id' => $row['id'],
                    'name' => $row['name']
            ));

            $ingredient_amounts[] = $row['amount'];
        }

        return array($ingredients, $ingredient_amounts);
    }

    public function update()
    {
        $query = DB::connection()->prepare('UPDATE Ingredient SET Name = :name WHERE ID = :id RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'id' => $this->id
        ));

        /// TODO :: How to check something was updated?
        $query->fetch();
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO Ingredient (Name) VALUES (:name) RETURNING id');
        $query->execute(array(
            'name' => $this->name,
        ));
        $row = $query->fetch();

        $this->id = $row['id'];
    }


    public function destroy()
    {
        $query = DB::connection()->prepare('DELETE FROM Ingredient WHERE ID = :id');
        $query->execute(array(
            'id' => $this->id,
        ));

        /// TODO :: How to check something was actually deleted?
        $query->fetch();
    }

    /*
     * Validation functions.
     */

    public function validate_name()
    {
        $errors = array();
        if ($this->name === '' || $this->name === null) {
            $errors[] = 'Ainesosan nimi ei saa olla tyhjä!';
        }
        elseif (!BaseModel::validate_string_length($this->name, 1, 30)) {
            $errors[] = 'Ainesosan nimen pituus saa olla maksimissaan 30 merkkiä!';
        }

        return $errors;
    }
}