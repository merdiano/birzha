<?php namespace Tps\Birzha\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DatabaseBackUp extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'birzha:databasebackup';

    /**
     * @var string The console command description.
     */
    protected $description = 'Makes a DB backup every week.';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $filename = "backup-" . Carbon::now()->format('Y-m-d_H_i_s') . ".sql";
  
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " > " . storage_path() . "/app/backup/" . $filename;
  
        $returnVar = NULL;
        $output  = NULL;
  
        exec($command, $output, $returnVar);
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
