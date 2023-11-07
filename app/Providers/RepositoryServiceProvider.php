<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use File;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->repositoryServiceBinding();
    }

    /**
     * @cloudy4next
     * @repositoryServiceBinding
     * Here.. Repo refer's to data layer and Service refer's to logical layer...
     * So bind Carefully
     **/

    protected function repositoryServiceBinding(): void
    {
        /* -------------------------Tiger Web Application  ------------------------ */
        $tigerWebRepo = [
            'Customer',
            'ArticleCategory',
            'Article',
            'ArticleAdvanceSearch',
            'Campaign',
            'TagKey',
            'ArticleTag',
            'ArticleReview',
            'DailyNews',
            'VasCp',
            'VasService',
            'VasServiceOption',
            'VasServicePrice',
            'Faq',
        ];

        $tigerWebService = [
            'ArticleCategory',
            'Article',
            'ArticleAdvanceSearch',
            'Campaign',
            'TagKey',
            'ArticleTag',
            'ArticleReview',
            'DailyNews',
            'VasCp',
            'VasService',
            'VasServiceOption',
            'VasServicePrice',
            'Faq',
        ];
        $tmsRepo = ['Search'];
        $tmsService = ['Search', 'DailyReport', 'HourlyReport', 'Report'];

        /* -------------------------Serttings Service  ------------------------ */
        $settingsService = [
            'User',
            'Permission',
            'Menu',
            'Role'
        ];

        /* -------------------------Toffee Analytics Application  ------------------------ */
        $toffeAnalyticsrepo = [
            'CampaignManagement',
            'Agency',
            'Campaign',
            'Brand',
            'UserCampaign',
        ];
        $toffeAnalyticsService = [
            'CampaignManagement',
            'Agency',
            'Campaign',
            'Brand',
        ];

        /* -------------------------E-Sim Application  ------------------------ */
        $dBSSESimRepo = ['ESim'];
        $dBSSESimServcie = ['ESim'];

        /* -------------------------DND Application  ------------------------ */
        $baseDNDRepo = ['BaseDND', 'BulkUploadDND'];
        $baseDNDService = ['BaseDND', 'BulkUploadDND'];

        // set of repository annd service for each application controller
        $applicationPath =
            [
                'TigerWeb' => [$tigerWebRepo, $tigerWebService],
                'TMS' => [$tmsRepo, $tmsService],
                'Settings' => [null, $settingsService],
                'ToffeAnalytics' => [$toffeAnalyticsrepo, $toffeAnalyticsService],
                'DBSSESim' => [$dBSSESimRepo, $dBSSESimServcie],
                'DND' => [$baseDNDRepo, $baseDNDService],
            ];

        // now bind the repo & interface for respective application
        foreach ($applicationPath as $key => $value) {
            $this->bindingService($key, $value[0], $value[1]);
        }
    }

    /**
     * @param string $applicationPath specify application path
     * @param array $repository application respository
     * @param array $services application service
     */
    protected function bindingService(string $applicationPath, array $repository = null, array $services): void
    {
        if ($repository != null) {

            foreach ($repository as $repo) {
                $this->app->bind("App\\Contracts\\$applicationPath\\{$repo}RepositoryInterface", "App\\Repositories\\$applicationPath\\{$repo}Repository");
            }
        }


        foreach ($services as $serv) {
            $this->app->bind("App\\Contracts\\Services\\$applicationPath\\{$serv}ServiceInterface", "App\\Services\\$applicationPath\\{$serv}Service");
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
