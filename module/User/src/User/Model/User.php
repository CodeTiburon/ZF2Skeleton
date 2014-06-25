<?php

namespace User\Model;

use Zf2Db\Model\AbstractModel;

class User extends AbstractModel
{
    public $id;
    public $first_name;
    public $last_name;
    public $avatar;
    public $role;
    public $email;
    public $password;
    public $gender;
    public $cdate;
    public $mdate;
}