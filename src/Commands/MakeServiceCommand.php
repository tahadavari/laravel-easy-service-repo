<?php

namespace TahaDavari\LaravelEasyServiceRepo\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service with interface';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $folderPath = app_path("Services/{$name}");
        $interfacePath = "{$folderPath}/I{$name}Service.php";
        $servicePath = "{$folderPath}/{$name}Service.php";


        if (!$this->files->isDirectory($folderPath)) {
            $this->files->makeDirectory($folderPath, 0755, true);
            $this->info("Directory {$folderPath} created.");
        } else {
            $this->info("Directory {$folderPath} already exists.");
        }


        if (!$this->files->exists($interfacePath)) {
            $this->files->put($interfacePath, $this->getInterfaceStub($name));
            $this->info("Interface I{$name}Service created.");
        } else {
            $this->info("Interface I{$name}Service already exists.");
        }


        if (!$this->files->exists($servicePath)) {
            $this->files->put($servicePath, $this->getServiceStub($name));
            $this->info("Service {$name}Service created.");
        } else {
            $this->info("Service {$name}Service already exists.");
        }
    }

    protected function getInterfaceStub($name)
    {
        return <<<EOT
<?php

namespace App\Services\\{$name};

interface I{$name}Service
{
}

EOT;
    }

    protected function getServiceStub($name)
    {
        return <<<EOT
<?php

namespace App\Services\\{$name};

class {$name}Service implements I{$name}Service
{
}

EOT;
    }
}
