<?php

namespace App\Console\Commands;

use App\Helpers\CustomHelper;
use App\Mail\CronMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UnaproveCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unaprove:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $user = User::where('status',CustomHelper::NOTAPPROVE)->where('role_id','!=',CustomHelper::ADMIN)->get();

        $details = [
            'title' => "UnApproved List",
            'data' => $user
        ];

        Mail::to('vaibhavhestabit01@gmail.com')->send(new CronMail($details));
        Log::info("CRON IS WORKING");
    }
}
