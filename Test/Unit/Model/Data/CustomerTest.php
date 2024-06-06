<?php

declare(strict_types=1);

namespace Ferreira\LoginByTaxvat\Test\Unit\Model\Data;

use Ferreira\LoginByTaxvat\Model\Data\Customer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{

    /**
     * @var (CustomerRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerRepositoryMock;

    /**
     * @var (SearchCriteriaBuilder&\PHPUnit\Framework\MockObject\MockObject)|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchCriteriaBuilderMock;

    /**
     * @var Customer
     */
    protected $customerModel;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->getMock();

        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerModel = new Customer(
            $this->customerRepositoryMock,
            $this->searchCriteriaBuilderMock
        );
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function testGetCustomerDataByTaxvat()
    {
        $taxvat = '123456789';

        $searchCriteria = new SearchCriteria();
        $searchCriteria->setFilterGroups(["taxvat" => $taxvat]);

        $searchResultsMock = $this->getMockBuilder(SearchResultsInterface::class)
            ->getMock();

        $this->searchCriteriaBuilderMock->method('addFilter')->with('taxvat', $taxvat)
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock->method('create')->willReturn($searchCriteria);

        $this->customerRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($this->equalTo($searchCriteria))
            ->willReturn($searchResultsMock);

        $searchResultsMock->expects($this->once())
            ->method('getItems')
            ->willReturn([]);

        $result = $this->customerModel->getCustomerDataByTaxvat($taxvat);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
