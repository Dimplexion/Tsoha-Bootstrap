<?php

class DrinkRecipe extends BaseModel
{
    public $id, $name, $owner_id, $approved;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->validators = array('validate_name');
    }

    public static function all()
    {
        return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe');
    }

    public static function all_approved()
    {
        return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe WHERE Approved = TRUE');
    }

    public static function all_suggestions_for_user()
    {
        $user = BaseController::get_user_logged_in();
        if($user)
        {
            $args = array('owner_id' => $user->id);
            return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe WHERE owner_id = :owner_id AND Approved = FALSE', $args);
        }

        return null;
    }

    public static function all_approved_for_user()
    {
        $args = array();
        $user = BaseController::get_user_logged_in();
        if($user)
        {
            $args = array('owner_id' => $user->id);
        }
        return DrinkRecipe::query_list('SELECT * FROM DrinkRecipe WHERE owner_id = :owner_id AND Approved = TRUE', $args);
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

    public function update()
    {
        $query = DB::connection()->prepare('UPDATE DrinkRecipe SET Name = :name, Owner_ID = :owner_id, Approved = :approved WHERE ID = :id RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'approved' => $this->approved,
            'id' => $this->id
        ));

        /// TODO :: How to check something was updated?
        $query->fetch();
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


    public function destroy()
    {
        $query = DB::connection()->prepare('DELETE FROM DrinkRecipe WHERE ID = :id');
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

    public function validate_name(){
        $errors = array();
        if($this->name === '' || $this->name === null)
        {
            $errors[] = 'Drinkin nimi ei saa olla tyhjä!';
        }
        if(!BaseModel::validate_string_length($this->name, 3))
        {
            $errors[] = 'Drinkin nimen pituuden tulee olla vähintään kolme merkkiä!';
        }

        return $errors;
    }
}