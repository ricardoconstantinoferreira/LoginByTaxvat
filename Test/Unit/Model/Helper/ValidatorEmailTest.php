<?php

declare(strict_types=1);

namespace RCFerreira\LoginByTaxvat\Test\Unit\Model\Helper;

use RCFerreira\LoginByTaxvat\Model\Helper\ValidatorEmail;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ValidatorEmailTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var object
     */
    protected $validatorEmail;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $objectManagerHelper = new ObjectManager($this);
        $this->validatorEmail = $objectManagerHelper->getObject(
            ValidatorEmail::class
        );
    }

    /**
     * @return void
     */
    public function testValidaEmail()
    {
        $email = 'test@example.com';
        $result = $this->validatorEmail->validaEmail($email);
        $this->assertEquals($email, $result);
    }
}
