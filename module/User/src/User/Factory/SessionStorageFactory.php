<?php

namespace User\Factory;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \User\Storage\Session;

class SessionStorageFactory implements FactoryInterface
{
    public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $conf = $serviceLocator->get('Config');
        $config = null;

        if (isset($conf['session']) && isset($conf['session']['sessionConfig'])) {
            $config = $conf['session']['sessionConfig'];
        }

        return new Session($config);
    }
}