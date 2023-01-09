<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            "Closed",
            "Created",
            "Resolved",
            "Inprogress",
            "Postponed",
            "Rejected",
            "Feedback"
         ];
 
        foreach($statuses as  $status){
            $st = new Status();
            $st->name = $status;
            $st->save();
        }
    }
}
