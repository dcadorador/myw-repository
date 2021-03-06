# MindYerWebsite(MYW) Model Repository

This is an internal package for Mind Yer Website, that will create the commands for creating the base and model repository classes that is commonly used in shopify projects.

## Installation

Via Composer

``` bash
$ composer require myw/modelrepository
```

## Configuration

Add in the config/app.php in the providers array

```
Myw\ModelRepository\ModelRepositoryServiceProvider::class
```

## Usage

This will create two console commands for creating the required files for using a repository pattern for the application, all files will be under App\Repositories folder.

This command will create the specific base repository:
``` bash
$ php artisan make:base-repository
```


This command will create the specific repository class for the model:
``` bash
$ php artisan make:model-repository {name : Model(Singular)}
```
