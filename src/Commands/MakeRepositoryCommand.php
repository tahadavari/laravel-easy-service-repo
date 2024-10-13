<?php

namespace TahaDavari\LaravelEasyServiceRepo\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name} {--model=}';
    protected $description = 'Create a new repository with interface and model support';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $model = $this->option('model') ?: $name;

        $folderPath = app_path("Repositories/{$name}");
        $interfacePath = "{$folderPath}/I{$name}Repository.php";
        $repositoryPath = "{$folderPath}/{$name}Repository.php";


        if (!$this->files->isDirectory($folderPath)) {
            $this->files->makeDirectory($folderPath, 0755, true);
            $this->info("Directory {$folderPath} created.");
        } else {
            $this->info("Directory {$folderPath} already exists.");
        }


        if (!$this->files->exists($interfacePath)) {
            $this->files->put($interfacePath, $this->getInterfaceStub($name));
            $this->info("Interface I{$name}Repository created.");
        } else {
            $this->info("Interface I{$name}Repository already exists.");
        }


        if (!$this->files->exists($repositoryPath)) {
            $this->files->put($repositoryPath, $this->getRepositoryStub($name, $model));
            $this->info("Repository {$name}Repository created.");
        } else {
            $this->info("Repository {$name}Repository already exists.");
        }
    }

    protected function getInterfaceStub($name)
    {
        return <<<EOT
<?php

namespace App\Repositories\\{$name};

interface I{$name}Repository
{
}

EOT;
    }

    protected function getRepositoryStub($name, $model)
    {
        return <<<EOT
<?php

namespace App\Repositories\\{$name};

use App\Models\\{$model};

class {$name}Repository  implements I{$name}Repository
{
    private readonly {$model} \${$this->camelCase($model)};

    public function __construct()
    {
        \$this->{$this->camelCase($model)} = new {$model}();
    }
}

EOT;
    }

    protected function camelCase($string)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string))));
    }
}
