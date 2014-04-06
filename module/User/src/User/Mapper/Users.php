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
    public function setDbAdapter(Adapter $adapter)
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
    public function save(User $user)
    {
        $id = (int)$user->id;

        $data = array(
            'first_name'      => ucfirst($user->first_name),
            'last_name'       => ucfirst($user->last_name),
            'lang'            => $user->lang?:'en',
            'email'           => strtolower($user->email),
            'avatar'          => $user->avatar ? : null,
            'password'        => $user->password,
            'hash'            => $user->hash,
            'cdate'           => $user->cdate ? : time(),
            'mdate'           => time(),
            'gender'          => $user->gender ? : null,
            'is_confirmed'    => $user->is_confirmed ? : 0,
            'sum_weight'      => $user->sum_weight ? : 0,
            'album_id'        => $user->album_id ? : null,
            'msg_howtostart'  => $user->msg_howtostart === 0 ? $user->msg_howtostart : 1,
            'birth'           => $user->birth ? : null,
            'place_birth'     => $user->place_birth ? : null,
            'contact_number'  => $user->contact_number ? : null,
            'country'         => $user->country ? : null,
            'city'            => $user->city ? : null,
            'street'          => $user->street ? : null,
            'number_house'    => $user->number_house ? : null,
            'zip_code'        => $user->zip_code ? : null,
            'auto_login_hash' => $user->auto_login_hash ? : null,
            'role'            => $user->role ? : 'user',
            'bonus_storage'   => $user->bonus_storage ? : null,
            'is_newsletter'   => (int)$user->is_newsletter == 0 ? 0 : 1,
        );

        if ($id == 0) {
            $this->insert($data);
            $id = $this->lastInsertValue;
        } else {
            if ($this->getItems(array('id=?' => $id))->current()) {
                $this->update($data, array('id' => $id));
            } else {
                return false;
            }
        }

        return $id;
    }
}