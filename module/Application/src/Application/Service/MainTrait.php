<?php

namespace Application\Service;

trait MainTrait
{

    protected $config;

    /**
     * @param string $name
     * @return array|object
     */
    public function getConfig ($name='')
    {
        $this->config = $this->getServiceLocator()->get('Config');

        if (!empty($name)) {
            $this->config = $this->config[$name];
        }

        return $this->config;
    }

    public function getRequest ()
    {
        return $this->getServiceLocator()->get('request');
    }

    /**
     * @return mixed
     */
    public function uniqId()
    {
        return preg_replace('/[^a-zA-Z0-9]+/', '', md5(microtime()) . uniqid());
    }

    /**
     * @param $tpl
     * @param $data
     * @param mixed $to
     * @param bool $subject
     * @param array $dataLayout
     * @return bool
     */
    public function sendEmail($tpl, $data, $to = false, $subject = false, $dataLayout = [])
    {
        if (!empty($data)) {
            $request = $this->getRequest();

            $data['request'] = $this->getServiceLocator()->get('request');
            $data['baseUrl'] = sprintf(
                '%s://%s%s',
                $request->getUri()->getScheme(),
                $request->getUri()->getHost(),
                $request->getBaseUrl()
            );

            /** @var \Zf2EmailService\Service\Email $emailService */
            $emailService = $this->getServiceLocator()->get('Zf2EmailService\Email');

            $emailService->addFrom($this->getConfig('settings')['email_noreply']);
            $emailService->addTo(!$to ? $data['user']['email'] : $to);

            $emailService->setLayout('layout/mail', $dataLayout);
            $emailService->setTemplate($tpl, $data);

            if ($subject) {
                $emailService->setSubject($subject);
            }
            $emailService->sendSmtp();
            $emailService->getHeaders()->clearHeaders();
            unset($emailService);

            return true;
        }

        return false;
    }
} 