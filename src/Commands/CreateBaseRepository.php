<?php

namespace Myw\ModelRepository\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateBaseRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:base-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the base repository for all the models in the application.';

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
     * @return void
     */
    public function handle()
    {

        if(!File::isDirectory(app_path() . '/Repositories'))
        {
            File::makeDirectory(app_path() . '/Repositories', 0775, true, true);
        }

        $base_repository_class = file_get_contents(base_path('vendor/myw/modelrepository/src/resources/stubs/Repository.stub'));

        file_put_contents(app_path('/Repositories/BaseRepository.php'), $base_repository_class);

        $this->info('Base Repository Successfully Created');
    }
}
