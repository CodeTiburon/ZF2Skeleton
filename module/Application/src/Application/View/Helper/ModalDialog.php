<?php

namespace Application\View\Helper;

use \Zend\View\Helper\AbstractHelper;
use \Zend\ServiceManager\ServiceLocatorAwareInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\View\HelperPluginManager;
use \Zend\View\Model\ViewModel;

class ModalDialog extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var HelperPluginManager
     */
    protected $serviceLocator;

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
	* Returns modal dialog markup
	*
	* @param string $id - dialog element ID
	* @param string $header - dialog header
	* @param string $body - dialog body
	* @param string $okayName - yes button name
	* @param string $cancelName - close button name
	* @param string $class - dialog css class
	* @return string dialog markup
	*/
	public function __invoke($id, $header, $body, $okayName = 'Ok', $cancelName = 'Cancel', $class = '')
	{
        $translator = $this->serviceLocator->getServiceLocator()->get('translator');
        $class = 'modal hide fade'. ($class ? (' ' . $class) : '');

        if ($body instanceof ViewModel) {
            $body = $this->getView()->render($body);
        }

		$dialog =
            "<div id=\"$id\" class=\"$class\" style=\"display:none\">" .
            "<div class=\"modal-header\">" .
            "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>" .
            "<h3>" . $translator->translate($header) . "</h3></div><div class=\"modal-body\">" .
	        $body . "</div><div class=\"modal-footer\">" .
            ($okayName ? ("<button type=\"button\" class=\"btn btn-primary btn-okay\">" .
                $translator->translate($okayName) . '</button>') : '') .
            ($cancelName ?
            ("<button type=\"button\" class=\"btn btn-cancel\" data-dismiss=\"modal\" aria-hidden=\"true\">" .
                $translator->translate($cancelName) . "</button>") : '') . "</div></div>";

		return $dialog;
	}
}
