<?php

namespace Myw\ModelRepository\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateModelRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model-repository {name : Model(Singular), e.g. User, Product, Variant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the model repository in the application.';

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
        $name  = $this->argument('name');

        if(!$name)
        {
            $this->error('Model name required.');
            return null;
        }

        $location = $this->ask('What is the location of the Model? (e.g App\\Models or App) ');

        if(!File::isDirectory(app_path() . '/Repositories'))
        {
            File::makeDirectory(app_path() . '/Repositories', 0775, true, true);
        }

        $model_location = rtrim($location, "\\") .  "\\" . $name;

        $model_repository_class =
            str_replace(
                'Location',
                $model_location,
                str_replace(
                    'Dummy',
                    $name,
                    file_get_contents(base_path('vendor/myw/modelrepository/src/resources/stubs/ModelRepository.stub'))
                )
            );


        file_put_contents(app_path('/Repositories/'. $name .'Repository.php'), $model_repository_class);

        $this->info('Model Repository Successfully Created');
    }
}
