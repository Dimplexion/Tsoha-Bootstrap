<?php

class DrinkRecipe extends BaseModel
{
    public $id, $name, $owner_id, $approved;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }

    public static function all()
    {
        $query = DB::connection()->prepare('SELECT * FROM DrinkRecipe');
        $query->execute();
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

    public static function find($id)
    {
        $query = DB::connection()->prepare('SELECT * FROM DrinkRecipe WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if(!$row)
        {
            return null;
        }

        return new DrinkRecipe(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'owner_id' => $row['owner_id'],
            'approved' => $row['approved']
        ));
    }
}