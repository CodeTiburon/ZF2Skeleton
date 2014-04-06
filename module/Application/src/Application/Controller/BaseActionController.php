<?php

namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Authentication\AuthenticationService;
use \Zend\Http\Response as HttpResponse;
use \Zend\Mvc\Exception;
use \Zend\Mvc\MvcEvent;
use \Zend\Session\Container;

/**
 * Class BaseActionController
 * @package Application\Controller
 *
 */
abstract class BaseActionController extends AbstractActionController
{
    /**
     * @var \User\Model\User
     */
    public $currentUser;

    /**
     * @var \User\Service\Users
     */
    public $usersService;

    public $routeName;

    public $host;
    /** @var \Zend\Authentication\AuthenticationService */
    public $auth;

    public $viewRender;

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $action = $routeMatch->getParam('action', 'not-found');
        $method = static::getMethodFromAction($action);

        $this->auth = new AuthenticationService();

        /** @var \User\Model\User currentUser */
        $this->currentUser = $this->serviceLocator->get('CurrentUser');
        $this->usersService = $this->serviceLocator->get('User\UsersService');

        $this->viewRender = $this->serviceLocator->get('ViewRenderer');

        $actionResponse = $this->$method();
        $e->setResult($actionResponse);

        return $actionResponse;
    }

    public function translate($message, $textDomain = null, $locale = null)
    {
        /** @var \Zend\I18n\View\Helper\Translate $translate */
        $translate = $this->getHelper('translate');
        if (null === $locale) {
            $locale = $this->serviceLocator->get('translator')->getLocale();
        }

        return $translate($message, $textDomain, $locale);
    }

    public function addMessage($message, $type = 'default')
    {
        switch ($type) {
            case 'success':
                $this->flashMessenger()->addSuccessMessage($message);
                break;
            case 'error':
                $this->flashMessenger()->addErrorMessage($message);
                break;
            case 'info':
                $this->flashMessenger()->addInfoMessage($message);
                break;
            default:
                $this->flashMessenger()->addMessage($message);
                break;
        }
    }

    public function getCurrentMessages()
    {
        return $this->flashMessenger()->getCurrentMessages();
    }

    public function getMessages()
    {
        return $this->flashMessenger()->getMessages();
    }

    /**
     * @param null $helperName
     * @return bool
     */
    public function getHelper($helperName = null)
    {
        if ($helperName && is_string($helperName)) {
            return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
        }

        return false;
    }

    public function getConfig($partName = null)
    {
        $config = $this->serviceLocator->get('Config');

        if ($partName) {
            $config = $config[$partName];
        }

        return $config;
    }

}