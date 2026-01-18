<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Console\GenerateRepository;
use App\Repositories\Console\GenerateRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (glob(app_path().'/Helpers/*.php') as $filename){
            require_once($filename);
        }

        $this->registerRepositories();
    }


    protected function registerRepositories(): void
    {
        $namespace = 'App\\Repositories\\';
        $repositoryPath = app_path('Repositories');

        $repositoryFiles = File::files($repositoryPath);

        foreach ($repositoryFiles as $repositoryFile) {
            $className = pathinfo($repositoryFile, PATHINFO_FILENAME);
            $class = $namespace . $className;

            if (class_exists($class) && ! ($reflector = new \ReflectionClass($class))->isAbstract()) {
                $interfaces = $reflector->getInterfaces();
                $directInterface = next($interfaces);
                $interfaceName = $directInterface->getName();

                if (interface_exists($interfaceName)) {
                    $this->app->bind($interfaceName, $class);
                }
            }
        }

        $this->commands(GenerateRepository::class);
        $this->commands(GenerateRepositoryInterface::class);
    }
}
