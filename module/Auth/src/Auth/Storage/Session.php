<?php

namespace Auth\Storage;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Session\SessionManager;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;

class Session
{

    protected $sessionConfig;

    public function __construct($sessionConfig)
    {
        $this->sessionConfig = $sessionConfig;
    }

    public function setDbSessionStorage($adapter)
    {
        $tableGW = new TableGateway('sessions', $adapter);

        $gwOpts = new DbTableGatewayOptions();
        $gwOpts->setDataColumn('data');
        $gwOpts->setIdColumn('id');
        $gwOpts->setLifetimeColumn('lifetime');
        $gwOpts->setModifiedColumn('modified');
        $gwOpts->setNameColumn('name');

        $saveHandler = new DbTableGateway($tableGW, $gwOpts);
        $sessionManager = new SessionManager();
        if ($this->sessionConfig) {
            $sessionConfig = new SessionConfig();
            $sessionConfig->setOptions($this->sessionConfig);
            $sessionManager->setConfig($sessionConfig);
        }
        $sessionManager->setSaveHandler($saveHandler);
        Container::setDefaultManager($sessionManager);
        $sessionManager->start();
    }

    public function setFileSessionStorage()
    {
        $sessionManager = new SessionManager();
        if ($this->sessionConfig) {
            $sessionConfig = new SessionConfig();
            $sessionConfig->setOptions($this->sessionConfig);
            $sessionManager->setConfig($sessionConfig);

            Container::setDefaultManager(new SessionManager($sessionConfig));
            $sessionManager->start();
        }
    }
}