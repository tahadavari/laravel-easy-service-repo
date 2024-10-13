### README for LaravelEasyServiceRepo

---

# LaravelEasyServiceRepo

**LaravelEasyServiceRepo** is a Laravel package that helps you quickly generate service and repository classes along with their interfaces. This package simplifies the structure of your Laravel applications by providing convenient Artisan commands to create repositories and services with ease.

## Features

- Generate repository and service classes with interfaces via Artisan commands.
- Automatically inject the repository into the service class.
- Support for polymorphic relationships in repositories.
- Automatically register the created services and repositories with the Laravel IoC container.

## Installation

To get started, install the package via Composer:

```bash
composer require your-namespace/laravel-easy-service-repo
```

After installation, the package will automatically register the following service providers:

- `ServiceServiceProvider`
- `RepositoryServiceProvider`

These providers are responsible for automatically binding services and repositories to the Laravel service container.

## Usage

This package provides three Artisan commands that help you generate services and repositories quickly:

### 1. `make:service {name}`

Generates a new service class with an interface.

#### Example:

```bash
php artisan make:service User
```

This command will generate the following files:
- `app/Services/User/IUserService.php` (Service Interface)
- `app/Services/User/UserService.php` (Service Implementation)

### 2. `make:repository {name} {--model=}`

Generates a new repository class with an interface. Optionally, you can specify the model that this repository will handle.

#### Example:

```bash
php artisan make:repository User --model=User
```

This command will generate the following files:
- `app/Repositories/User/IUserRepository.php` (Repository Interface)
- `app/Repositories/User/UserRepository.php` (Repository Implementation)

### 3. `make:service-repository {name}`

This command creates both the service and repository for the given name, and automatically injects the repository into the service class.

#### Example:

```bash
php artisan make:service-repository User
```

This command will generate the following:
- Service and repository with their interfaces.
- Inject the repository into the service class.

After running the command, the generated service class will look like this:

```php
namespace App\Services\User;

use App\Repositories\User\IUserRepository;

class UserService implements IUserService
{
    protected IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}
```

## Service and Repository Providers

Two service providers, `ServiceServiceProvider` and `RepositoryServiceProvider`, are responsible for registering the service and repository classes with Laravel's IoC container.

These providers automatically scan the `app/Services/` and `app/Repositories/` directories for service and repository interfaces and bind them to their corresponding implementations.

### Custom Service and Repository Binding

If you need to add custom binding logic, you can modify the service providers or override them by manually registering your classes in `AppServiceProvider` or any other provider in your application.

## Example

Here’s an example of how to use the generated service and repository:

```php
use App\Services\User\IUserService;

class UserController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }
}
```

In the above example, `IUserService` is injected into the controller, and the service uses the `IUserRepository` to retrieve data from the database.

## Contributing

Contributions are welcome! Please submit a pull request or create an issue to discuss any changes.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

---

### Conclusion

**LaravelEasyServiceRepo** is designed to make it easy for you to generate services and repositories in a clean and maintainable structure for your Laravel application. Start using it today to speed up your development process!

