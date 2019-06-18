<?php

namespace App\Console\Commands;

use App\Services\Clinic2ApiService;
use App\Services\Clinic1ApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from API-s';

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
     * @return mixed
     */
    public function handle()
    {
        $service1 = new Clinic1ApiService();
        $service1->getData()
                 ->mapData()
                 ->filterBy('dateOfBirth', '2001-01-11', '>' )
                 ->filterBy('booked_date', Carbon::now()->addDays(30), '<' )
                 ->store();

        $service2 = new Clinic2ApiService();
        $service2->getData()
                 ->mapData()
                 ->filterBy('dateOfBirth', '2001-01-11', '>' )
                 ->filterBy('dateOfBirth', Carbon::now()->addDays(30), '<' )
                 ->store();
    }
}
