<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use PhpConsole;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $config = $e->getApplication()->getConfig();

        if (isset($config['phpSettings'])) {
            $phpSettings = $config['phpSettings'];

            foreach ($phpSettings as $key => $value) {
                ini_set($key, $value);
            }
        }

        $eventManager->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            'dispatch',
            function ($e) {
                $controller = $e->getTarget();
                $controllerClass = get_class($controller);
                $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                $config = $e->getApplication()->getServiceManager()->get('config');

                $moduleLayouts = $config['module_layouts'];

                if (isset($moduleLayouts[$moduleNamespace])) {
                    $controller->layout($moduleLayouts[$moduleNamespace]);
                }

            },
            100
        );

        $eventManager->getSharedManager()->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            'dispatch',
            array($this, 'layoutModuleNamespace')
        );

        $handler = PhpConsole\Handler::getInstance();

        $handler->start(); // initialize handlers
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'initLocale'), 1);

    }

    public function layoutModuleNamespace(MvcEvent $e)
    {
        $moduleNamespace = $e->getRouteMatch()->getParam('__NAMESPACE__');

        $e->getTarget()->layout()->moduleNamespace = substr(
            $moduleNamespace,
            0,
            strpos($moduleNamespace, '\\')
        ) ? : 'Application';
    }

    public function getConfig()
    {
        $result = array();

        $configs = glob(__DIR__ . '/config/*.config.php');

        foreach ($configs as $config) {
            if (file_exists($config)) {
                $result = array_merge_recursive($result, include_once($config));
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

    public function initLocale(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        $translator = $serviceManager->get('translator');
        $config =  $serviceManager->get('Config');
        $shotLang = $e->getRouteMatch()->getParam('lang');

        if (isset($config['languages'][$shotLang])){
            // set locale
            $translator->setLocale($config['languages'][$shotLang]['locale']);
        } else {
            // undefined lang
            $lang = array_shift($config['languages']);
            $translator->setLocale($lang['locale']);
        }
        $renderer = $serviceManager->get('viewmanager')->getRenderer();
        $translate = $renderer->plugin('translate');
        $translate->setTranslator($translator);
    }
}
