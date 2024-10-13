<?php

namespace TahaDavari\LaravelEasyServiceRepo\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceAndRepositoryCommand extends Command
{
    protected $signature = 'make:service-repository {name}';
    protected $description = 'Create a service and repository for a model';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');

        $this->call('make:service', ['name' => $name]);

        $this->call('make:repository', ['name' => $name, '--model' => $name]);

        $servicePath = app_path("Services/{$name}/{$name}Service.php");

        if ($this->files->exists($servicePath)) {
            $content = $this->files->get($servicePath);
            $repositoryInterface = "App\\Repositories\\{$name}\\I{$name}Repository";
            $repositoryVariable = lcfirst($name) . 'Repository';
            $injectedContent = <<<EOT
use {$repositoryInterface};

class {$name}Service implements I{$name}Service
{
    protected I{$name}Repository \${$repositoryVariable};

    public function __construct(I{$name}Repository \${$repositoryVariable})
    {
        \$this->{$repositoryVariable} = \${$repositoryVariable};
    }
}
EOT;

            $this->files->put($servicePath, $injectedContent);
            $this->info("Repository injected into {$name}Service.");
        }
    }
}
