<?php

declare(strict_types=1);

namespace Ferreira\LoginByTaxvat\Plugin\Magento\Customer\Controller\Account;

use Magento\Customer\Controller\Account\LoginPost;
use Ferreira\LoginByTaxvat\Model\Data\Customer;

class LoginPostPlugin
{
    /**
     * @param Customer $customer
     */
    public function __construct(
        private Customer $customer
    ) {}

    /**
     * Login by taxvat in login page
     *
     * @param LoginPost $subject
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeExecute(
        LoginPost $subject
    ) {
        $login = $subject->getRequest()->getParam('login');

        if (!empty($login['username'])) {
            $customerData = $this->customer->getCustomerDataByTaxvat($login['username']);
            if (!empty($customerData)) {
                $email = current($customerData)->getEmail();
                $login['username'] = $email;
                $subject->getRequest()->setPostValue('login', $login);
            }
        }
    }
}
