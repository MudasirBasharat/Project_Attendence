<?php

namespace App\Console\Commands;
use App\Models\office_record;
use App\Models\remote_record;
use Illuminate\Console\Command;

class DeleteWeeklyRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-weekly-records';

    protected $description = 'Delete remote and office records weekly';

    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Execute the console command.
     */ 
    public function handle()
    {
        office_record::query()->delete();
        remote_record::query()->delete();

        $this->info('Weekly records deleted successfully.');
    }
}
