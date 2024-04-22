<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunPythonScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-python-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run python script within laravel project';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptPath = base_path('scripts/example_script.py');

        $process = new Process(['python', $scriptPath]);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('The command failed to run: '.$process->getErrorOutput());
            return;
        }

        $output = $process->getOutput();
        $this->info($output);
    }
}
