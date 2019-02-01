<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class InvoiceStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoice_status')->truncate();

        $status=[
       		['name' => 'Billed', 'created_at'=>Carbon::now()],
       		['name' => 'Delivered', 'created_at'=>Carbon::now()],
       		['name' => 'Canceled', 'created_at'=>Carbon::now()]
        ];

        Db::table('invoice_status')->insert($status);
    }
}
