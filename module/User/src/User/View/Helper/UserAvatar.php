<?php

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

class UserAvatar extends AbstractHelper
{

    /**
     * @param        $path
     * @param        $userId
     * @param string $name
     *
     * @return mixed
     */
    public function __invoke($path, $userId, $name = '168x168')
    {
        $view = $this->getView();

        if (file_exists(BASE_DIR . '/public/uploads/users/avatar/' . $path . '/' . $userId . '_' . $name . '.jpg')) {
            return $view->basePath('/uploads/users/avatar/' . $path . '/' . $userId . '_' . $name . '.jpg');
        } else {
            return $view->basePath('/uploads/users/avatar/default' . '/' . $name . '.jpg');
        }
    }

}
