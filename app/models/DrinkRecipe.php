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
        return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe');
    }

    public static function all_approved()
    {
        return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe WHERE Approved = TRUE');
    }

    public static function find($id)
    {
        $query = DB::connection()->prepare('SELECT * FROM DrinkRecipe WHERE ID = :id LIMIT 1');
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

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO DrinkRecipe (Name, Owner_ID, Approved) VALUES (:name, :owner_id, :approved) RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'approved' => $this->approved
        ));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    private static function query_list($query_string)
    {
        $query = DB::connection()->prepare( $query_string );
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
}