<?php

class User extends BaseModel
{
    public $id, $username, $password, $admin;

    public function __construct($attributes)
    {
        $this->validators = array('validate_username', 'validate_password');
        parent::__construct($attributes);
    }

    public static function all()
    {
        $query = DB::connection()->prepare('SELECT * FROM UserAccount');
        $query->execute();
        $rows = $query->fetchAll();

        $users = array();
        foreach($rows as $row)
        {
            $users[] = new User(array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'admin' => $row['admin']
            ));
        }

        return $users;
    }

    public static function find($id)
    {
        $query = DB::connection()->prepare('SELECT * FROM UserAccount WHERE ID = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if($row)
        {
            return new User( array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'admin' => $row['admin']
            ));
        }
        else
        {
            return null;
        }
    }

    public function save()
    {
        $query = DB::connection()->prepare('INSERT INTO UserAccount (Username, password, admin) VALUES (:username, :password, :admin) RETURNING id');
        $query->execute(array(
            'username' => $this->username,
            'password' => $this->password,
            'admin' => $this->admin
        ));
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public static function authenticate($username, $password)
    {
        /// TODO :: Implement hashing the password.

        $query = DB::connection()->prepare('SELECT * FROM UserAccount WHERE Username = :username AND password = :password LIMIT 1');
        $query->execute(array('username' => $username,
            'password' => $password));
        $row = $query->fetch();

        if($row){
            return new User( array(
                'id' => $row['id'],
                'username' => $row['username'],
                'password' => $row['password'],
                'admin' => $row['admin']
            ));
        }
        else
        {
            return null;
        }
    }

    // Input validation.
    public function validate_username(){
        $errors = array();
        if($this->username === '' || $this->username === null)
        {
            $errors[] = 'Käyttäjänimi ei saa olla tyhjä! ' . $this->username;
        }
        elseif(!BaseModel::validate_string_length($this->username, 3))
        {
            $errors[] = 'Käyttäjänimen pituuden tulee olla vähintään kolme merkkiä pitkä!';
        }

        return $errors;
    }

    public function validate_password(){
        $errors = array();
        if($this->password === '' || $this->password === null)
        {
            $errors[] = 'Salasana ei saa olla tyhjä!';
        }
        elseif(!BaseModel::validate_string_length($this->password, 5))
        {
            $errors[] = 'Salasanan pituuden tulee olla vähintään viisi merkkiä pitkä!';
        }

        return $errors;
    }
}