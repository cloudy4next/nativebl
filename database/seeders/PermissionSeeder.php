<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //Tiger Web
            // Article Titles
            ['title' => 'Tigerweb All Article'],
            ['title' => 'Tigerweb Article Create'],
            ['title' => 'Tigerweb Article Show'],
            ['title' => 'Tigerweb Article Edit'],
            ['title' => 'Tigerweb Article Delete'],
            ['title' => 'Tigerweb Article Correction'],
            ['title' => 'Tigerweb Article Raise Ticket'],
            ['title' => 'Tigerweb Article Review Submit'],
            ['title' => 'Tigerweb Article Advance Search'],
            // Article Category Titles
            ['title' => 'Tigerweb Article Category List'],
            ['title' => 'Tigerweb Article Category Create'],
            ['title' => 'Tigerweb Article Category Save'],
            ['title' => 'Tigerweb Article Category Show'],
            ['title' => 'Tigerweb Article Category Edit'],
            ['title' => 'Tigerweb Article Category Delete'],
            ['title' => 'Tigerweb Article Category Update'],
            // Campaign Titles
            ['title' => 'Tigerweb Campaign List'],
            ['title' => 'Tigerweb Campaign Create'],
            ['title' => 'Tigerweb Campaign Save'],
            ['title' => 'Tigerweb Campaign Show'],
            ['title' => 'Tigerweb Campaign Edit'],
            ['title' => 'Tigerweb Campaign Delete'],
            // Faq Titles
            ['title' => 'Tigerweb Faq List'],
            ['title' => 'Tigerweb Faq Create'],
            ['title' => 'Tigerweb Faq Save'],
            ['title' => 'Tigerweb Faq Show'],
            ['title' => 'Tigerweb Faq Edit'],
            ['title' => 'Tigerweb Faq Delete'],
            // Tag Key Titles
            ['title' => 'Tigerweb Tag Key List'],
            ['title' => 'Tigerweb Tag Key Create'],
            ['title' => 'Tigerweb Tag Key Save'],
            ['title' => 'Tigerweb Tag Key Show'],
            ['title' => 'Tigerweb Tag Key Edit'],
            ['title' => 'Tigerweb Tag Key Delete'],
            // Article Tag Titles
            ['title' => 'Tigerweb Article Tag List'],
            ['title' => 'Tigerweb Article Tag Create'],
            ['title' => 'Tigerweb Article Tag Save'],
            ['title' => 'Tigerweb Article Tag Show'],
            ['title' => 'Tigerweb Article Tag Edit'],
            ['title' => 'Tigerweb Article Tag Delete'],
            // Article Review Titles
            ['title' => 'Tigerweb Article Review List'],
            ['title' => 'Tigerweb Article Review Create'],
            ['title' => 'Tigerweb Article Review Save'],
            ['title' => 'Tigerweb Article Review Show'],
            ['title' => 'Tigerweb Article Review Edit'],
            ['title' => 'Tigerweb Article Review Raise Approve Ticket'],
            ['title' => 'Tigerweb Article Review Delete'],
            // Daily News Titles
            ['title' => 'Tigerweb Daily News List'],
            ['title' => 'Tigerweb Daily News Create'],
            ['title' => 'Tigerweb Daily News Save'],
            ['title' => 'Tigerweb Daily News Show'],
            ['title' => 'Tigerweb Daily News Edit'],
            ['title' => 'Tigerweb Daily News Delete'],
            // VAS CP Titles
            ['title' => 'Tigerweb Vas Cp List'],
            ['title' => 'Tigerweb Vas Cp Create'],
            ['title' => 'Tigerweb Vas Cp Save'],
            ['title' => 'Tigerweb Vas Cp Show'],
            ['title' => 'Tigerweb Vas Cp Edit'],
            ['title' => 'Tigerweb Vas Cp Delete'],
            // VAS Service Titles
            ['title' => 'Tigerweb Vas Service List'],
            ['title' => 'Tigerweb Vas Service Create'],
            ['title' => 'Tigerweb Vas Service Save'],
            ['title' => 'Tigerweb Vas Service Show'],
            ['title' => 'Tigerweb Vas Service Edit'],
            ['title' => 'Tigerweb Vas Service Delete'],
            // VAS Service Option Titles
            ['title' => 'Tigerweb Vas Service Option List'],
            ['title' => 'Tigerweb Vas Service Option Create'],
            ['title' => 'Tigerweb Vas Service Option Save'],
            ['title' => 'Tigerweb Vas Service Option Show'],
            ['title' => 'Tigerweb Vas Service Option Edit'],
            ['title' => 'Tigerweb Vas Service Option Delete'],
            // VAS Service Price Titles
            ['title' => 'Tigerweb Vas Service Price List'],
            ['title' => 'Tigerweb Vas Service Price Create'],
            ['title' => 'Tigerweb Vas Service Price Save'],
            ['title' => 'Tigerweb Vas Service Price Show'],
            ['title' => 'Tigerweb Vas Service Price Edit'],
            ['title' => 'Tigerweb Vas Service Price Delete'],

            // Toffee Analytics
            // All Campaign Titles
            ['title' => 'Toffee All Campaign'],
            // Brand Titles
            ['title' => 'Toffee Brand List'],
            ['title' => 'Toffee Brand Create'],
            ['title' => 'Toffee Brand Show'],
            ['title' => 'Toffee Brand Edit'],
            ['title' => 'Toffee Brand Delete'],
            ['title' => 'Toffee Brand User Map Delete'],
            // Agency Titles
            ['title' => 'Toffee Agency'],
            ['title' => 'Toffee Agency Create'],
            ['title' => 'Toffee Agency Show'],
            ['title' => 'Toffee Agency Edit'],
            ['title' => 'Toffee Agency Delete'],
            ['title' => 'Toffee Agency User Map Delete'],
            // Map Campaigns Titles
            ['title' => 'Toffee Map Campaigns'],
            ['title' => 'Toffee Map Campaigns Create'],
            ['title' => 'Toffee Map Campaigns Show'],
            ['title' => 'Toffee Map Campaigns Edit'],
            ['title' => 'Toffee Map Campaigns Delete'],
        ];

        foreach($permissions as $permission)
        {
            DB::table('app_permissions')->insert([
                [   'application_id' => 5,
                    'title' => $permission['title'],
                    'short_description' => $permission['title'],
                    'name' => Str::slug($permission['title']),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => "8932fcd2-23b6-11ee-be56-0242ac120002",
                ]
            ]);
        }
    }

}
