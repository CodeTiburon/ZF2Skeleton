<?php

namespace User\Mapper;

use \Zend\Db\Adapter\Adapter;
use \Zend\Db\ResultSet\ResultSet;
use \Zf2Db\Mapper\AbstractMapper;
use \User\Model\User;

class Users extends AbstractMapper
{
    protected $table = 'users';

    /**
     * Set mapper's DB adapter
     *
     * @param \Zend\Db\Adapter\Adapter $adapter
     *
     * @return void|\Zend\Db\Adapter\AdapterAwareInterface
     */
    public function setDbAdapter (Adapter $adapter)
    {
        parent::setDbAdapter($adapter);

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User());
        $this->initialize();
    }

    /**
     * Save user model, if id in model is 0 then try to create new
     *
     * @param User $user
     *
     * @return int|false
     */
    public function save (User $user)
    {
        $id = (int)$user->id;

        $data = array(
            'first_name' => ucfirst($user->first_name),
            'last_name'  => ucfirst($user->last_name),
            'avatar'     => $user->avatar ?: null,
            'role'       => $user->role ?: 'user',
            'email'      => strtolower($user->email),
            'password'   => $user->password,
            'gender'     => $user->gender ?: null,
            'cdate'      => $user->cdate ?: time(),
            'mdate'      => time(),
        );

        if ($id == 0) {
            $this->insert($data);
            $id = $this->lastInsertValue;
        } else {
            if ($this->getItems(['id=?' => $id])->current()) {
                $this->update($data, ['id' => $id]);
            } else {
                return false;
            }
        }

        return $id;
    }
}