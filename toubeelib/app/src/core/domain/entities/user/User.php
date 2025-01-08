<?php
namespace toubeelib\core\domain\entities\user;

use toubeelib\core\domain\entities\Entity;

class User extends Entity{
    const PATIENT = 0;
    const ROLE_PERSONNEL_MEDICAL= 5;
    const ROLE_PRACTICIEN = 10;
    const ROLE_ADMIN = 15;

    const PERMISSION_READ = 1;
    const PERMISSION_WRITE = 2;
    const PERMISSION_DELETE = 3;

    protected string $id;
    protected string $email;
    protected string $password;
    protected string $role;

    public function __construct(string $email, string $password, string $role){
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getRole(){
        return $this->role;
    }

}