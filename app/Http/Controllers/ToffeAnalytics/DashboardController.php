<?php

namespace App\Http\Controllers\ToffeAnalytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ToffeAnalytics\AdsManagerTrait;
use Google\AdsApi\AdManager\v202308\ServiceFactory;
use Google\AdsApi\AdManager\Util\v202308\AdManagerDateTimes;
use Google\AdsApi\AdManager\Util\v202308\StatementBuilder;
use Google\AdsApi\AdManager\v202308\CompanyType;

use Carbon;

class DashboardController extends Controller
{
    use AdsManagerTrait;

    public function lineItem()
    {
        $serviceFactory = new ServiceFactory();
        $lineItemService = $serviceFactory->createLineItemService($this->getAdsSession());

        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $id = '6371465500';
        $statementBuilder = (new StatementBuilder())->orderBy('id ASC')->where('id=' . $id)
            ->limit($pageSize);
        $testLineItemArray = [];

        $totalResultSetSize = 0;
        do {
            $page = $lineItemService->getLineItemsByStatement(
                $statementBuilder->toStatement()
            );

            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $lineItem) {
                    $testLineItemArray[$i++] =
                        $lineItem;
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);
        dd($testLineItemArray);
        dd("Number of results found:", $totalResultSetSize);
    }

    public function getLineItemByOrderID()
    {
        $serviceFactory = new ServiceFactory();
        $lineItemService = $serviceFactory->createLineItemService($this->getAdsSession());

        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $statementBuilder = (new StatementBuilder())->orderBy('id ASC')->where('orderId=2987397345')
            ->limit($pageSize);
        $testLineItemArray = [];

        $totalResultSetSize = 0;
        do {
            $page = $lineItemService->getLineItemsByStatement(
                $statementBuilder->toStatement()
            );

            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $lineItem) {
                    $testLineItemArray[$i++] =
                        $lineItem;
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);
        dd($testLineItemArray);
        dd("Number of results found:", $totalResultSetSize);
    }

    public function getAdvertisers()
    {
        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $statementBuilder = (new StatementBuilder())->where('type = :type')
            ->orderBy('id ASC')
            ->limit($pageSize)
            ->withBindVariableValue('type', CompanyType::ADVERTISER);

        $totalResultSetSize = 0;
        do {
            $page = $this->getCompanyService()->getCompaniesByStatement(
                $statementBuilder->toStatement()
            );
            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $company) {
                    printf(
                        "%d) Company with ID %d, name '%s', and type '%s' was"
                        . " found.%s",
                        $i++,
                        $company->getId(),
                        $company->getName(),
                        $company->getType(),
                        PHP_EOL
                    );
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);

        printf("Number of results found: %d%s", $totalResultSetSize, PHP_EOL);
    }

    public function getAllCompany()
    {
        $serviceFactory = new ServiceFactory();
        $companyService = $serviceFactory->createCompanyService($this->getAdsSession());

        // Create a statement to select companies.
        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $statementBuilder = (new StatementBuilder())->orderBy('id ASC')
            ->limit($pageSize);

        $totalResultSetSize = 0;
        do {
            $page = $companyService->getCompaniesByStatement(
                $statementBuilder->toStatement()
            );

            // Print out some information for each company.
            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $company) {
                    printf(
                        "%d) Company with ID %d, name '%s', and type '%s' was"
                        . " found.%s",
                        $i++,
                        $company->getId(),
                        $company->getName(),
                        $company->getType(),
                        PHP_EOL
                    );
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);

        printf("Number of results found: %d%s", $totalResultSetSize, PHP_EOL);
    }

    public function orderItem()
    {
        $serviceFactory = new ServiceFactory();
        $lineItemService = $serviceFactory->createOrderService($this->getAdsSession());

        $pageSize = StatementBuilder::SUGGESTED_PAGE_LIMIT;
        $statementBuilder = (new StatementBuilder())->orderBy('id ASC')->where('id=2987397345')
            ->limit(2);
        $testLineItemArray = [];

        $totalResultSetSize = 0;
        do {
            $page = $lineItemService->getOrdersByStatement(
                $statementBuilder->toStatement()
            );

            if ($page->getResults() !== null) {
                $totalResultSetSize = $page->getTotalResultSetSize();
                $i = $page->getStartIndex();
                foreach ($page->getResults() as $lineItem) {
                    $testLineItemArray[$i++] =
                        $lineItem;
                }
            }

            $statementBuilder->increaseOffsetBy($pageSize);
        } while ($statementBuilder->getOffset() < $totalResultSetSize);
        dd($testLineItemArray);
        dd("Number of results found:", $totalResultSetSize);
    }
}
