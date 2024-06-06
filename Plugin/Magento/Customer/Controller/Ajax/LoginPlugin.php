<?php

declare(strict_types=1);

namespace Ferreira\LoginByTaxvat\Plugin\Magento\Customer\Controller\Ajax;

use Magento\Customer\Controller\Ajax\Login;
use Magento\Framework\Serialize\Serializer\Json;
use Ferreira\LoginByTaxvat\Model\Helper\ValidatorEmail;
use Ferreira\LoginByTaxvat\Model\Data\Customer;

class LoginPlugin
{
    /**
     * @param Json $json
     * @param ValidatorEmail $validatorEmail
     * @param Customer $customer
     */
    public function __construct(
        private Json $json,
        private ValidatorEmail $validatorEmail,
        private Customer $customer
    ) {}

    /**
     * Login by taxvat in checkout login
     *
     * @param Login $subject
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeExecute(
        Login $subject
    ) {
        $content = $this->json->unserialize($subject->getRequest()->getContent());

        if (isset($content['username']) && !$this->validatorEmail->validaEmail($content['username'])) {
            $customerData = $this->customer->getCustomerDataByTaxvat($content['username']);
            $email = current($customerData)->getEmail();
            if (!empty($email)) {
                $content['username'] = $email;
                $subject->getRequest()->setContent(
                    $this->json->serialize($content)
                );
            }
        }
    }
}
