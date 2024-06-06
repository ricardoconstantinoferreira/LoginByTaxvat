<?php

declare(strict_types=1);

namespace RCFerreira\LoginByTaxvat\Model\Data;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Customer
{
    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private SearchCriteriaBuilder $searchCriteriaBuilder,
    ) {}

    /**
     * Get customer data by taxvat whether he has taxvat
     *
     * @param string $taxvat
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomerDataByTaxvat(string $taxvat): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter("taxvat", $taxvat)
            ->create();

        $result = $this->customerRepository->getList($searchCriteria);

        if (!empty($result)) {
            return $result->getItems();
        }

        return [];
    }

}
