<?php

class User extends BaseModel
{
    public $id, $name, $password, $admin;

    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }

    public static function authenticate($username, $password)
    {
        /// TODO :: Implement hashing the password.

        $query = DB::connection()->prepare('SELECT * FROM UserAccount WHERE name = :name AND password = :password LIMIT 1');
        $query->execute(array('name' => $username,
            'password' => $password));
        $row = $query->fetch();

        if($row){
            return new User( array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password'],
                'admin' => $row['admin']
            ));
        }
        else
        {
            return null;
        }
    }

    public static function find($id)
    {
        $query = DB::connection()->prepare('SELECT * FROM UserAccount WHERE ID = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if($row){
            return new User( array(
                'id' => $row['id'],
                'name' => $row['name'],
                'password' => $row['password'],
                'admin' => $row['admin']
            ));
        }
        else
        {
            return null;
        }
    }
}