<?php

namespace User\Event;

use \Zend\Mvc\MvcEvent as MvcEvent;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\ServiceManager\ServiceLocatorAwareInterface;
use \Zend\EventManager;
use \Zend\Authentication\AuthenticationService;
use \User\Model\User;

class AuthenticationListener implements ServiceLocatorAwareInterface, EventManager\ListenerAggregateInterface
{

    /**
     * @var User
     */
    public $currentUser;

    /**
     * @var \User\Service\Users
     */
    public $usersService;

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManager\EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManager\EventManagerInterface $events)
    {
        $events->attach(
            MvcEvent::EVENT_DISPATCH,
            array($this, 'preDispatch'),
            10000
        );
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManager\EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManager\EventManagerInterface $events)
    {
        // TODO: Implement detach() method.
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * preDispatch Event Handler
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @throws \Exception
     */
    public function preDispatch(MvcEvent $event)
    {
        $this->usersService = $this->getServiceLocator()->get('User\UsersService');

        $auth = new AuthenticationService();

        if ($auth->hasIdentity()) {
            $this->currentUser = $this->usersService->getUser(['id=?' => $auth->getIdentity()]);
        } else {
            $this->currentUser = new User();
        }
        $this->serviceLocator->setService('CurrentUser', $this->currentUser);

    }
}
