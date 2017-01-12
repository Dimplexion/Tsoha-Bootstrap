<?php

class Ingredient extends BaseModel
{
    public $id, $name;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
        //$this->validators = array('validate_name');
    }

    public static function all()
    {
        $query = DB::connection()->prepare('SELECT * FROM Ingredient');
        $query->execute();
        $rows = $query->fetchAll();

        $ingredients = array();
        foreach($rows as $row)
        {
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

        if(!$row)
        {
            return null;
        }

        return new Ingredient(array(
            'id' => $row['id'],
            'name' => $row['name']
        ));
    }

    public static function find_by_recipe_id($recipe_id)
    {
        $query = DB::connection()->prepare('SELECT Ingredient.id, Ingredient.name, DrinkRecipeIngredientComb.amount FROM DrinkRecipeIngredientComb 
                                              INNER JOIN Ingredient ON DrinkRecipeIngredientComb.Ingredient = Ingredient.ID 
                                              WHERE DrinkRecipeIngredientComb.Recipe = :recipe_id');
        $query->execute(array('recipe_id' => $recipe_id));
        $rows = $query->fetchall();

        if(!$rows)
        {
            return null;
        }

        $ingredients = array();
        foreach( $rows as $row)
        {
            $ingredients[] = array(
                $row['amount'],
                new Ingredient(array(
                    'id' => $row['id'],
                    'name' => $row['name']
                ))
            );
        }

        return $ingredients;
    }

    public static function find_by_name($name)
    {
        /// TODO :: Implement me!
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

    private static function query_list($query_string, $args = array())
    {
        $query = DB::connection()->prepare( $query_string );
        $query->execute($args);
        $rows = $query->fetchAll();

        $recipes = array();
        foreach($rows as $row)
        {
            $recipes[] = new DrinkRecipe(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'owner_id' => $row['owner_id'],
                'approved' => $row['approved']
            ));
        }

        return $recipes;
    }
}