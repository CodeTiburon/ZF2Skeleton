<?php

namespace User\Service;

use \Application\Service\MainTrait;
use \Zend\Db\Sql\Where;
use \Zf2Db\Service\AbstractService;
use \User\Model\User;
use \Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapterDbTable;
use \Zend\Authentication\AuthenticationService;
use \Zend\Filter\StaticFilter;
use \Zend\Session\Container as Session;
use \Zend\Db\Sql\Predicate;
use \Zend\Db\Adapter\Adapter;

class Users extends AbstractService
{
    use MainTrait;

    const STATUS_ACTIVE = 'active';
    const STATUS_DEACTIVATED = 'deactivated';

    /** @var \User\Mapper\Users */
    protected $usersMapper;

    /** @var  \Zend\Db\Adapter\Adapter */
    protected $adapter;

    public function initialize()
    {
        $this->usersMapper = $this->serviceLocator->get('User\UsersMapper');
        $this->adapter = $this->serviceLocator->get('Zend\Db\Adapter\Adapter');
    }

    /**
     * @param $data
     * @return bool|false|int|\User\Mapper\Users
     */
    public function signup($data)
    {
        if (is_array($data) && !empty($data)) {

            $data['password'] = password_hash(
                $data['password'],
                PASSWORD_BCRYPT,
                ['salt' => $this->getConfig('settings')['salt']]
            );
            $data['hash'] = md5(time() . $data['email']);

            $user = new User();
            $user->exchangeArray($data);

            if ($id = $this->saveUser($user)) {

                // send email
                $this->sendEmail(
                    'auth/mail-template/signup',
                    [
                        'hash'      => $user->hash,
                        'firstName' => $user->first_name,
                        'lang'      => 'en'
                    ],
                    $user->email,
                    null,
                    [
                        'layoutTitle' => 'Sing up'
                    ]
                );

                return $id;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @return int|\User\Mapper\Users|false
     */
    public function saveUser(User $user)
    {
        return $this->usersMapper->save($user);
    }

    /**
     * @param $where
     * @return bool|int
     * @throws \Zend\Db\TableGateway\Exception\RuntimeException
     * @throws \Zend\Db\Sql\Exception\InvalidArgumentException
     */
    public function deleteUser($where)
    {
        if (!empty($where)) {
            return $this->usersMapper->delete($where);
        }

        return false;
    }

    /**
     * @param $where
     * @param array $columns
     * @return bool
     * @throws \Zend\Db\Sql\Exception\InvalidArgumentException
     */
    public function getItem($where, $columns = ['*'])
    {
        if ($user = $this->getItems($where, $columns)) {
            return $user->current();
        }

        return false;
    }

    /**
     * Authenticate user by it's email and password
     * @param string $email
     * @param string $password
     * @return \Zend\Authentication\Result
     */
    public function authenticate($email, $password)
    {

        $email = StaticFilter::execute($email, 'StringTrim');
        $passwordHash = password_hash(
            StaticFilter::execute($password, 'StringTrim'),
            PASSWORD_BCRYPT,
            ['salt' => $this->getConfig('settings')['salt']]
        );

        $authAdapter = new AuthAdapterDbTable($this->adapter, 'users', 'email', 'password');

        $authAdapter
            ->setIdentity($email)
            ->setCredential($passwordHash);

        $result = $authAdapter->authenticate();

        if ($result->isValid()) {
            $result->id = (int)$authAdapter->getResultRowObject(['id'])->id;
        }

        return $result;
    }

    /**
     * Log in user by it's email and password
     * @param string $email
     * @param string $password
     * @param boolean $rememberMe
     * @return \Zend\Authentication\Result
     */
    public function logIn($email, $password, $rememberMe = false)
    {
        $result = $this->authenticate($email, $password);

        if ($result->getCode() == 1) {
            $auth = new AuthenticationService();
            $userId = $result->id;
            $auth->getStorage()->write($userId);
            if ($rememberMe) {
                Session::getDefaultManager()->rememberMe($this->getConfig('settings')['rememberMe']);
            }

        }

        return $result;
    }

    /**
     * Log out current user
     */
    public function logOut()
    {
        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {
            Session::getDefaultManager()->forgetMe();
            $auth->clearIdentity();
        }
    }

    /**
     * Sends user email with instructions to restore it's forgotten password
     * @param string $email
     * @return boolean
     */
    public function forgotPassword($email)
    {
        $user = $this->getUser(['email=?' => $email]);

        if ($user) {
            try {
                $this->usersMapper->getAdapter()->getDriver()->getConnection()->beginTransaction();
                if (!$user->hash) {
                    $user->hash = md5(time() . $user->email);
                }
                $newUser = new User();
                $newUser->exchangeArray((array)$user);
                $this->usersMapper->save($newUser);

                $this->sendEmail(
                    'auth/mail-template/forgotpassword',
                    [
                        'firstName'   => $newUser->first_name,
                        'restoreHash' => $newUser->hash
                    ],
                    $newUser->email,
                    null,
                    [
                        'layoutTitle' => 'Forgot password'
                    ]
                );

                $this->usersMapper->getAdapter()->getDriver()->getConnection()->commit();

                return true;
            } catch (\Exception $e) {
                $this->usersMapper->getAdapter()->getDriver()->getConnection()->rollback();
            }
        }

        return false;
    }

    /**
     * list of users
     *
     * @param $where
     * @param mixed $columns
     * @param mixed $order
     * @return bool|null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getItems($where, $columns = ['*'], $order = ['id DESC'])
    {
        return $this->usersMapper->getItems($where, $columns, $order);
    }

}