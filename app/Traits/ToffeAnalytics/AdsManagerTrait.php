<?php

namespace App\Traits\ToffeAnalytics;

use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\v202211\Goal;
use Google\AdsApi\AdManager\v202211\Stats;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdManager\v202308\ServiceFactory;
use Google\AdsApi\AdManager\v202308\LineItem;
use Google\AdsApi\AdManager\v202308\Date;
use Google\AdsApi\AdManager\v202308\DateTime;


trait AdsManagerTrait
{
    public function getAdsSession(): object
    {
        $path = config_path('adsapi_php.ini');
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($path)
            ->build();

        $session = (new AdManagerSessionBuilder())
            ->fromFile($path)
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        return $session;
    }

    public function service(): object
    {
        return new ServiceFactory();
    }

    public function getCompanyService(): object
    {
        return $this->service()->createCompanyService($this->getAdsSession());
    }

    public function getUserService(): object
    {
        return $this->service()->createUserService($this->getAdsSession());
    }
    public function NetworkService(): object
    {
        return $this->service()->createNetworkService($this->getAdsSession());
    }

    public function getLineItemService(): object
    {
        return $this->service()->createLineItemService($this->getAdsSession());
    }

    /**
     * @param \ReflectionClass $item A reflection class of LineItem.
     * @param LineItem $object single from LineItem array.
     * @param string $property LineItem class property.
     * @return string|int|array
     */
    public function getValueFromClass(\ReflectionClass $item, LineItem|DateTime|Goal|Stats|Date $object, string $property): mixed
    {
        $idProperty = $item->getProperty($property);
        $idProperty->setAccessible(true);
        return $idProperty->getValue($object);
    }
    public function calculatePercentage(int $achivedUnit, int $totalUnit): string
    {
        $result = ($achivedUnit / $totalUnit) * 100;
        return number_format($result, 2) ;
    }
    public function customDateTimeFormat($date, $month, $year)
    {
        return $date . '-' . $month . '-' . $year;
    }

    public function reflectionClass($object): \ReflectionClass
    {
        return new \ReflectionClass($object);
    }
    public function makeDateTime(string $datetime): \DateTime
    {
        return new \DateTime($datetime, new \DateTimeZone('Asia/Dhaka'));
    }

    public function googleDateTimeToString($date)
    {
        $year = $date->getDate()->getYear();
        $month = $date->getDate()->getMonth();
        $day = $date->getDate()->getDay();

        $hour = $date->getHour();
        $minute = $date->getMinute();
        $second = $date->getSecond();

        $dateTimeString = sprintf(
            '%04d-%02d-%02d %02d:%02d:%02d',
            $year,
            $month,
            $day,
            $hour,
            $minute,
            $second
        );
        return $dateTimeString;
    }

}
