<?php

namespace User;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        /** @var \User\Event\AuthenticationListener $moduleRouteListener */
        $moduleRouteListener = $serviceManager->get('User\AuthenticationListener');

        /** @var \User\Storage\Session $storage */
        $storage = $serviceManager->get('User\Storage\SessionStorage');
        $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');

        $storage->setDbSessionStorage($adapter);
        $moduleRouteListener->attach($e->getApplication()->getEventManager());
    }

    public function layoutModuleNamespace(MvcEvent $e)
    {
        $moduleNamespace = $e->getRouteMatch()->getParam('__NAMESPACE__');
        $e->getTarget()->layout()->moduleNamespace = substr(
            $moduleNamespace,
            0,
            strpos($moduleNamespace, '\\')
        ) ? : 'User';
    }

    public function getConfig()
    {
        $result = array();

        $configs = glob(__DIR__ . '/config/*.config.php');

        foreach ($configs as $config) {
            if (file_exists($config)) {
                $result = array_merge($result, include_once($config));
            }
        }

        return $result;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
