<?php

namespace User\Controller;

use \User\Model\User;
use \Zend\View\Model\ViewModel;
use \Zend\View\Model\JsonModel;
use \Zend\Authentication\AuthenticationService;
use \Application\Controller\BaseActionController;
use \User\Form;


class AuthController extends BaseActionController
{
    public function indexAction()
    {

        $form = $this->getServiceLocator()->get('User\LoginForm');
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $result = $this->usersService->logIn($data['email'], $data['password'], $data['remember_me']);

                    if (!empty($result->id)) {

                        return new JsonModel(
                            [
                                'status'      => true,
                                'redirectUrl' => $this->url()->fromRoute('home'),
                            ]
                        );
                    }

                    return new JsonModel(
                        [
                            'status' => false,
                            'errors' => ['auth' => ['authError' => 'Such combination of Login and Password was not found']],
                        ]
                    );

                } catch (\Exception $e) {
                    return new JsonModel(
                        [
                            'status' => false,
                            'errors' => ['auth' => $e->getMessage()],
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


        return new viewModel(
            [
                'form' => $form,
            ]
        );
    }

    public function logoutAction()
    {
        if (isset($_COOKIE['noticeOff'])) {
            setcookie('noticeOff', '', (time() - 86400), '/', $this->host);
        }

        $this->usersService->logOut();
        $this->redirect()->toRoute('auth');
    }

    public function signupAction()
    {

        /** @var \Auth\Form\Signup $form */
        $form = $this->getServiceLocator()->get('Auth\SignupForm');
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $userId = $this->usersService->signup($data);

                    if ($userId) {

                        $auth = new AuthenticationService();
                        $auth->getStorage()->write($userId);

                        return new JsonModel(
                            array(
                                'status'      => true,
                                'redirectUrl' => $this->url()->fromRoute('home'),
                            )
                        );
                    }
                } catch (\Exception $e) {
                    return new JsonModel(
                        [
                            'status'   => false,
                            'messages' => $e->getMessage(),
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

        return new viewModel(
            [
                'form'  => $form
            ]
        );
    }

    public function forgotPasswordAction()
    {
        $form = $this->getServiceLocator()->get('Auth\ForgotPasswordForm');
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest() && $request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                try {
                    $data = $form->getData();

                    if ($this->usersService->forgotPassword($data['email'])) {
                        return new JsonModel(
                            array(
                                'status'      => true,
                                'redirectUrl' => $this->url()->fromRoute('auth'),
                            )
                        );
                    }

                    return new JsonModel(
                        [
                            'status'   => false,
                            'messages' => ['Fail to restore your password'],
                        ]
                    );
                } catch (\Exception $e) {
                    return new JsonModel(
                        [
                            'status'   => false,
                            'messages' => ['Bad params'],
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

        return new viewModel(
            [
                'form' => $form,
            ]
        );
    }

    public function restorePasswordAction()
    {
        $form = $this->getServiceLocator()->get('\Auth\RestorePasswordForm');
        $hash = $this->params()->fromRoute('hash');

        $user = $this->usersService->getUser(['hash=?' => $hash]);

        if (!$user) {
            $this->addMessage($this->translate('This change password key has been already used'), 'error');

            return $this->redirect()->toRoute('forgotPassword');
        }

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                try {
                    $data = $form->getData();
                    $user->password = password_hash(
                        $data['password'],
                        PASSWORD_BCRYPT,
                        ['salt' => $this->getConfig('settings')['salt']]
                    );
                    $user->hash = null;
                    $upUser = new User();
                    $upUser->exchangeArray((array)$user);
                    $this->usersService->saveUser($upUser);

                    $this->addMessage($this->translate('Your password has been changed successfully!'), 'success');

                    return new JsonModel(
                        [
                            'status'      => true,
                            'redirectUrl' => $this->url()->fromRoute('auth'),
                        ]
                    );
                } catch (\Exception $e) {

                    return new JsonModel(
                        [
                            'status'   => false,
                            'messages' => ['Fail to set your new password'],
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

        return [
            'form' => $form,
            'hash' => $hash,
        ];
    }

    public function confirmationAction()
    {
        $hash = $this->params()->fromRoute('hash');


        /** @var \User\Model\User $user */
        $user = new User();
        $user->exchangeArray((array)$this->usersService->getUser(['hash=?' => $hash]));

        if (!$user->id) {
            return $this->redirect()->toRoute('home');
        }

        $user->is_confirmed = 1;
        $user->mdate = time();
        $user->hash = null;

        if ($this->usersService->saveUser($user)) {

            if (!$this->auth->hasIdentity()) {
                $this->auth->getStorage()->write($user->id);
            }
        }

        return $this->redirect()->toRoute('home');
    }

    public function resendMessageAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            $hash = $this->currentUser->hash;
            if (!$this->currentUser->hash) {
                $user = $this->usersService->getUser(['id=?' => $this->currentUser->id]);
                $hash = $user->hash = md5(time() . $user->email);
                $updateUser = new User();
                $updateUser->exchangeArray((array)$user);
                $this->usersService->saveUser($updateUser);
            }


            $this->usersService->sendEmail(
                'auth/mail-template/resendmessage',
                [
                    'hash'      => $hash,
                    'firstName' => $this->currentUser->first_name,
                ],
                $this->currentUser->email
            );

            return new JsonModel(
                [
                    'status' => false,
                ]
            );
        }
    }
}
