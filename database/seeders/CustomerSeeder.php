<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $msisdnList = ['01962424629','01962424630','01962424631','01962424621','01962424622'];
        foreach($msisdnList as $msisdn){
          $records[] = [
            'full_name' => Str::random(10),
            'msisdn' => $msisdn,
          ];
        }
        DB::table('customers')->insert($records);
    }
}
