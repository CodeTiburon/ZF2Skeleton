<?php
/**
 * Author: Nickolay U. Kofanov
 * Company: CodeTiburon
 */
namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

class CurrentUser extends AbstractHelper
    implements ServiceLocatorAwareInterface
{
    /**
     * @var HelperPluginManager
     */
    protected $serviceLocator;

    /**
     * @var \User\Model\User
     */
    protected $user;

    /**
     * @return \User\Model\User
     */
    public function getUser()
    {
        if (null === $this->user) {
            $this->user = $this->serviceLocator->getServiceLocator()->get('CurrentUser');
        }
        return $this->user;
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
     * @param bool  $res
     * @param array $options
     *
     * @return string|\User\Model\User
     */
    public function __invoke($res=false, $options=array())
    {
        switch($res) {
            case 'render':
                return $this->render();
            case 'avatar':
                return $this->getAvatar($options['avatarName']);
            default:
                return $this->getUser();
        }
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getAvatar($name = '168x168')
    {
        $view = $this->getView();
        $user = $this->getUser();
        if (file_exists(BASE_DIR . '/public/uploads/users/avatar/' . $user->avatar . '/' . $user->id . '_' . $name . '.jpg')) {
            return $view->basePath('/uploads/users/avatar/' . $user->avatar . '/' . $user->id . '_' . $name . '.jpg');
        } else {
            return $view->basePath('/uploads/users/avatar/default' . '/' . $name . '.jpg');
        }
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->getView()->render('user/index/current-user-widget',
            array(
                'user' => $this->getUser(),
            )
        );
    }
}
