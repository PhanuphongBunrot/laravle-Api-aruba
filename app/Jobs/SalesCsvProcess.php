<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Http;
use MongoDB\Client as Mongo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SalesCsvProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $ip ="172.16.0.50";
            $resp = Http::timeout(5)->withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8'
            ])
                ->withOptions(["verify" => false])
                ->post('https://' . $ip . ':4343/rest/login', [
                    'user' => 'admin',
                    'passwd' => 'ssit1234'
                ]);
            $sid = $resp->json()['sid'];
            echo $ip;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            //echo $ip." offline";
        }
    }
}
