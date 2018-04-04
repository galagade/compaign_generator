<?php

use Illuminate\Database\Seeder;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('campaign_status')->insert([
          ['id' => 1, 'name' => 'Draft'],
          ['id' => 2, 'name' => 'Pending'],
          ['id' => 3, 'name' => 'Approved'],
          ['id' => 4, 'name' => 'Rejected'],
          ['id' => 5, 'name' => 'Closed'],
        ]);
    }
}
