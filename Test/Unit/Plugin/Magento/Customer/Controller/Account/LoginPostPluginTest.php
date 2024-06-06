<?php

declare(strict_types=1);

namespace RCFerreira\LoginByTaxvat\Test\Unit\Plugin\Magento\Customer\Controller\Account;

use RCFerreira\LoginByTaxvat\Plugin\Magento\Customer\Controller\Account\LoginPostPlugin;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Customer\Controller\Account\LoginPost;

class LoginPostPluginTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var (\RCFerreira\LoginByTaxvat\Model\Data\Customer&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerMock;

    /**
     * @var object
     */
    protected $loginPostPlugin;

    /**
     * @var (\Magento\Customer\Model\Data\Customer&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerDataMock;

    /**
     * @var (\Magento\Framework\App\Request\Http&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var (LoginPost&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $loginPostMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->customerMock = $this->getMockBuilder(\RCFerreira\LoginByTaxvat\Model\Data\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerDataMock = $this->getMockBuilder(\Magento\Customer\Model\Data\Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginPostMock = $this->getMockBuilder(LoginPost::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManager($this);

        $this->loginPostPlugin = $objectManagerHelper->getObject(
            LoginPostPlugin::class,
            [
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
            ->method('getParam')
            ->with('login')
            ->willReturn($content);

        $this->loginPostMock->expects($this->any())
            ->method('getRequest')
            ->willReturn($this->requestMock);

        $this->customerDataMock->expects($this->any())
            ->method('getEmail')
            ->willReturn('test@example.com');

        $this->customerMock->expects($this->once())
            ->method('getCustomerDataByTaxvat')
            ->with('test@example.com')
            ->willReturn(['0' =>$this->customerDataMock]);

        $this->loginPostPlugin->beforeExecute($this->loginPostMock);
    }

}
