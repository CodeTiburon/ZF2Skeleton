<?php

namespace User\Controller;

use \Zend\View\Model\ViewModel;
use \Zend\View\Model\JsonModel;
use \Zend\Validator as Validator;
use \Zend\Session\Container;
use \User\Model\User;

use \Application\Controller\BaseActionController;

class IndexController extends BaseActionController
{
    /**
     * dashboard page
     *
     * @return array|JsonModel
     */
    public function indexAction()
    {
    }

    /**
     * profile page
     *
     * @return JsonModel
     */
    public function profileAction()
    {
        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->isPost()) {
            try {
                if (!empty($data = (array)$request->getPost())) {
                    /** @var \User\Form\Profile $form */
                    $form = $this->serviceLocator->get('User\ProfileForm');
                    $form->setValidationGroup(array_keys($data));
                    $form->setData($data);

                    if ($form->isValid()) {
                        $data = $form->getData();
                        $userModel = new User();

                        $userModel->exchangeArray(array_merge((array)$this->currentUser, $data));

                        if ($this->usersService->saveUser($userModel)) {
                            return new JsonModel(
                                [
                                    'status'   => true,
                                    'data'     => $data,
                                    'messages' => ['Data was updated.'],
                                ]
                            );
                        }
                    } else {
                        return new JsonModel(
                            [
                                'status' => false,
                                'errors' => $form->getMessages(),
                            ]
                        );
                    }
                }
            } catch (\Exception $e) {
                return new JsonModel(
                    [
                        'status' => false,
                        'errors' => $e->getMessage(),
                    ]
                );
            }
        }
    }
}
