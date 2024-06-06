<?php

declare(strict_types=1);

namespace Ferreira\LoginByTaxvat\Test\Unit\Plugin\Magento\Customer\Controller\Ajax;

use Ferreira\LoginByTaxvat\Plugin\Magento\Customer\Controller\Ajax\LoginPlugin;
use Magento\Customer\Controller\Ajax\Login;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class LoginPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json|(\Magento\Framework\Serialize\Serializer\Json&object&\PHPUnit\Framework\MockObject\MockObject)|(\Magento\Framework\Serialize\Serializer\Json&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $jsonMock;

    /**
     * @var \Ferreira\LoginByTaxvat\Model\Helper\ValidatorEmail|(\Ferreira\LoginByTaxvat\Model\Helper\ValidatorEmail&object&\PHPUnit\Framework\MockObject\MockObject)|(\Ferreira\LoginByTaxvat\Model\Helper\ValidatorEmail&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $validatorEmailMock;

    /**
     * @var \Ferreira\LoginByTaxvat\Model\Data\Customer|(\Ferreira\LoginByTaxvat\Model\Data\Customer&object&\PHPUnit\Framework\MockObject\MockObject)|(\Ferreira\LoginByTaxvat\Model\Data\Customer&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerMock;

    /**
     * @var Login|(Login&object&\PHPUnit\Framework\MockObject\MockObject)|(Login&\PHPUnit\Framework\MockObject\MockObject)|(object&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $loginMock;

    /**
     * @var (\Magento\Framework\App\Request\Http&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var object
     */
    protected $loginPlugin;

    /**
     * @var (\Magento\Customer\Model\Data\Customer&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerDataMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->jsonMock = $this->getMockBuilder(\Magento\Framework\Serialize\Serializer\Json::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validatorEmailMock = $this->getMockBuilder(\Ferreira\LoginByTaxvat\Model\Helper\ValidatorEmail::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerMock = $this->getMockBuilder(\Ferreira\LoginByTaxvat\Model\Data\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loginMock = $this->getMockBuilder(Login::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerDataMock = $this->getMockBuilder(\Magento\Customer\Model\Data\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManager($this);
        $this->loginPlugin = $objectManagerHelper->getObject(
            LoginPlugin::class,
            [
                'json' => $this->jsonMock,
                'validatorEmail' => $this->validatorEmailMock,
                'customer' => $this->customerMock
            ]
        );
    }

    /**
     * @return void
     */
    public function testBeforeExecute()
    {
        $content = ['username' => 'test@example.com'];
        $serializedContent = '{"username":"test@example.com"}';
        $email = 'test@example.com';

        $this->requestMock->expects($this->any())
            ->method('getContent')
            ->willReturn($serializedContent);

        $this->requestMock->expects($this->any())
            ->method('setContent')
            ->with('{"username":"test@example.com"}');

        $this->loginMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->jsonMock->expects($this->any())
            ->method('unserialize')
            ->with($serializedContent)
            ->willReturn($content);

        $this->validatorEmailMock->expects($this->once())
            ->method('validaEmail')
            ->with('test@example.com')
            ->willReturn(false);

        $this->customerDataMock->expects($this->any())
            ->method('getEmail')
            ->willReturn('test@example.com');

        $this->customerMock->expects($this->once())
            ->method('getCustomerDataByTaxvat')
            ->with('test@example.com')
            ->willReturn(['0' =>$this->customerDataMock]);

        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->with(['username' => $email])
            ->willReturn('{"username":"test@example.com"}');

        $this->loginPlugin->beforeExecute($this->loginMock);
    }
}

