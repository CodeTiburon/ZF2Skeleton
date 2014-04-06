<?php

namespace User\Model;

use Zf2Db\Model\AbstractModel;

class User extends AbstractModel
{
    public $id;
    public $first_name;
    public $last_name;
    public $lang;
    public $avatar;
    public $role;
    public $email;
    public $password;
    public $gender;
    public $birth;
    public $place_birth;
    public $contact_number;
    public $country;
    public $city;
    public $street;
    public $number_house;
    public $zip_code;
    public $cdate;
    public $mdate;
    public $hash;
    public $is_confirmed;
    public $sum_weight;
    public $album_id;
    public $msg_howtostart;
    public $bonus_storage;
    public $auto_login_hash;
    public $is_newsletter;
}