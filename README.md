## PHP Custom Skeleton
Este proyecto es una simple prueba de conceptos, la idea es generar una estructura sencilla con las herramientas básicas necesarias para construir aplicaciones en PHP sin estar en el ambiente de un framework especifico.

### Dependencias
A continuación una lista con algunas de las librerías que se utilizan en este proyecto.

 * [Error Handler - Whoops](https://github.com/filp/whoops)
 * [PSR-7 HTTP Message](https://github.com/laminas/laminas-diactoros)
 * [PSR-7 Router - Dispatcher](https://route.thephpleague.com/)
 * [PSR-11 Dependency Injection Container](https://container.thephpleague.com/)
 * [PSR-15 Middleware Dispatcher](https://github.com/oscarotero/middleland)
 * [PSR-15 Session Middleware](https://github.com/psr7-sessions/storageless)
 * [Template Engine - Twig](https://twig.symfony.com)
 * [ORM - Eloquent](https://laravel.com/docs/5.1/database)
 * [Validator - Sirius Validation](https://github.com/siriusphp/validation)
 * [Environment - PHP DotEnv](https://github.com/vlucas/phpdotenv)
 * [Database Migrations - Phinx](https://github.com/cakephp/phinx)

Referirse a la documentación oficial para entender el funcionamiento.
 
### Instalación
Las dependencias del proyecto se gestionan mediante [Composer](https://getcomposer.org/) el cual se puede instalar fácilmente con un par de comandos:
```shell
curl -sS https://getcomposer.org/installer | php
```
```shell
sudo mv ~/composer.phar /usr/local/bin/composer
```
Con `composer` instalado y estando en la raíz del proyecto solo hace falta correr el siguiente comando:
```shell
composer install
```

### HTTP Server
La forma mas fácil de probar el funcionamiento de la aplicación es con el PHP built-in server (solo en desarrollo):
```sh
cd public/
php -S localhost:8000
```

### PSR-4 Autoloading
Las clases del proyecto se cargan de forma automática gracias a Composer, siempre y cuando se siga el estándar PSR-4 de [PHP-FIG](https://www.php-fig.org/psr/psr-4/).
```php
namespace App\SomeDirectory\ClassName;
```
Configuración adicional en el archivo `composer.json`.

### Environment
Los parámetros de configuración (variables de entorno) se aplican desde el archivo **.env** (renombrar el .env.example) que esta ubicado en la raíz del proyecto y siguen el formato: `SECRET_KEY="12345"`, para tener acceso a dichas variables se ocupa cualquiera de estos métodos:
```php
$key = env('SECRET_KEY');
$key = $_ENV['SECRET_KEY'];
$key = $_SERVER['SECRET_KEY'];
```
Configuración adicional en el archivo `app/Src/Core/environment.php`.

### Routes
Las rutas del proyecto se definen en el archivo `app/Http/routes.php`, estas responden a cualquier verbo Http (get|post|put|patch|delete), cada ruta esta asociada a un __handler__, este puede ser un callback o un clase:
```php
$router->get('/example', function (ServerRequestInterface $request): ResponseInterface {
    return response('Hello World!');
});

$router->get('/user/{id:number}', [App\Http\Controllers\UsersController::class, 'show']);
```
Es necesario que el __handler__ retorne un objeto [Response](#psr-7-http-response).

### Controllers
Los controladores se crean en la carpeta `app/Http/Controllers`, se recomienda heredar de la clase `App\Http\Controllers\Controller` para obtener funcionalidad extra. Las dependencias de construcción se resuelven de forma automática por el [Container](#psr-11-container) instalado en el proyecto:
```php
public function __construct(TemplateEngine $view, User $user)
{
    $this->view = $view;
    $this->user = $user;
}
```

### PSR-11 Container
El proyecto cuenta con un contenedor IoC que resuelve las dependencias de una clase de forma automática **ReflectionContainer** , se puede configurar en el archivo `app/Core/container.php`:
```php
$container
    ->add(Acme\Foo::class)
    ->setShared()
```

### Service Provider
El **ReflectionContainer** no es capaz de resolver una dependencia si esta tiene argumentos escalares o depende de una interface|contrato, para estos casos es necesario enseñarle al Container como debe resolver la dependencia, un service provider se encarga de esto. Los service providers heredan de `AbstractServiceProvider` y se almacenan en la carpeta `app/Provider`, también es necesario registrarlos en la clase `App\Application` con el método `getProviders()`.
```php
protected $provides = ['Some\ClassInterface'];
public function register()
{
    $this->getContainer()->add('Some\ClassInterface', 'Some\Class');
}
```

### PSR-7 HTTP Request
El objeto Request es un wrapper de las variables globales $_GET, $_POST, $_COOKIE, $_FILES, $_SERVER. Se utiliza (entre muchas otras cosas) para obtener datos get|post mediante una interfaz orientada a objetos, este objeto viaja a traves de toda la aplicación:
```php
public function index(ServerRequestInterface $request): ResponseInterface
{
    $query = $request->getQueryParams();
    $params = $request->getParsedBody();
    return view('home', compact('query', 'params'));
}
```
Se puede obtener de varias formas:
* El Router|Dispatcher inyecta un ServerRequest en todos los handlers.
* Pedirlo en el constructor mediante la interface `Psr\Http\Message\ServerRequestInterface`.
* Mediante la función global (helpers.php) request().

### PSR-7 HTTP Response
Este objeto es necesario para enviar algún tipo de respuesta al cliente (navegador), solo existe una instancia de Response en todo el ciclo de la aplicación y se puede obtener de varias formas:

* Creando una instancia de `Laminas\Diactoros\Response` o derivados.
* Pedirla en el constructor mediante la interface `Psr\Http\Message\ServerRequestInterface`.
* Mediante el [Template Engine](#template-engine).
* Mediante la función global (helpers.php) response().

Con el objeto Response solo hace falta setear los headers correspondientes y el contenido, el proyecto se encarga de preparar y enviar la respuesta al cliente.
```php
$response->withStatus(200);
$response->withHeader('Content-Type', 'application/json');
$response->getBody()->write(json_encode([
    "dns" => "1.1.1.1",
    "alt_dns" => "1.0.0.1"
]));
```
### PSR-15 Middleware Dispatcher
El proyecto cuenta con dos PSR-15 Middleware Dispatchers, el Router y el Kernel, los middleware se almacenan en la carpeta `app/Http/Middleware` y deben implementar la interface `Psr\Http\Server\MiddlewareInterface`. Si se van a utilizar en el Router para manipular el Request (antes) o el Response (después) al despachar un ruta, solo hace falta registrarlos en el archivo `app/Http/routes.php`:
```php
//General
$router->middleware(new Acme\AuthMiddleware);
//Especifico
$router->get('/', [Controller::class, 'create'])->middleware(new Acme\AuthMiddleware);
```
Si el middleware necesita aplicarse de forma independiente al handler del Router, es decir, que afecte otra capa de la aplicación, este debe registrarse en la clase `App\Application` con el método `vendorMiddleware()`.

### PSR-7 Sessions StorageLess
El manejo de sesiones se gestiona de una forma diferente, en PHP generalmente se utiliza la extension `ext/session` con la superglobal `$_SESSION`, internamente se crea un fichero en el servidor y un identificador que se guarda en una cookie. Una sesión `StorageLess` no crea este fichero, la información se almacena en una cookie mediante un token JWT, este tipo de sesiones tiene varias [ventajas](https://github.com/psr7-sessions/storageless#advantages) sobre las sesiones convencionales. Para crear el token JWT se utiliza una llave generada de forma aleatoria, esta llave se obtiene con la variable de entorno `APP_KEY`.
Para generar el `APP_KEY` se puede utilizar:
```sh
openssl rand -hex 32
```
La sesión se inyecta en el Request mediante un Middleware y se puede obtener de la siguiente forma:
```php
/**
 * @var \PSR7Sessions\Storageless\Session\SessionInterface $session
 */
$session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
$session->set('counter', $session->get('counter', 0) + 1);
```
Si por alguna razon se necesita ocupar las sesiones convencionales `ext/session`, se recomienda utilizar algunos de estos Middleware:
 * https://github.com/jasny/session-middleware.
 * https://github.com/mezzio/mezzio-session

Configuración adicional en el archivo `app/Src/Provider/SessionServiceProvider.php`.

### Database ORM
El proyecto utiliza el ORM Eloquent para mapear la base de datos, los modelos deben extender de la clase `App\Entities\Entity` para obtener toda la funcionalidad del ORM. También se puede obtener el objeto `Illuminate\Database\Capsule\Manager` por construcción y tener acceso al QueryBuilder.
```php
$database->table('users')->where('name', 'John')->first();
```
Configuración adicional en el archivo `app/Src/Provider/DatabaseServiceProvider.php`.

#### Database Migrations & Seeding
Se puede llevar un control de cambios sobre el diseño de la base de datos con las migraciones, solo hace falta configurar la conexión en el archivo **phinx.php**, para crear la migración hay que ejecutar el siguiente comando:
```sh
vendor/bin/phinx create CreateUsersTable
```
Esto crea una clase en la carpeta `/db/migrations` con un nombre especifico, en esta clase se define la migración. Para ejecutar las migraciones se ocupa el siguiente comando:
```sh
vendor/bin/phinx migrate  
```
Si hay algún error se pueden deshacer los cambios de la ultima migración con:
```php
vendor/bin/phinx rollback  
```
De igual forma se puede crear y ejecutar Seeders:
```sh
vendor/bin/phinx seed:create UserTableSeeder
vendor/bin/phinx seed:run -v
```

### Paginación
Para paginar elementos se puede utilizar el método `paginate()` de la clase `App\Http\Controllers\Controller`, se pueden paginar arrays genéricos y objetos tipo Collection. Este método retorna un objeto `illuminate\Pagination\LengthAwarePaginator` con toda la lógica necesaria para generar enlaces e iterar sobre la colección, también se puede utilizar el método paginate() del ORM.
```php
$users = $this->paginate($this->randomUsers(50));
//Template
$users->render();
```

### Validación
El objeto Validator se puede obtener por construcción o creando una instancia en cualquier método, toda la documentación se encuentra en la web del autor [SiriusValidation](http://www.sirius.ro/php/sirius/validation/).
Se pueden crear reglas de validación [personalizadas](https://www.sirius.ro/php/sirius/validation/rule_factory.html) para extender la funcionalidad, estas reglas se deben almacenar de forma individual en la carpeta `app/Src/Validation/Rule` y se deben registrar en el Service Provider `app/Src/Provider/ValidationServiceProvider.php`.
```php
 $validator->add([
    'email:Email' => 'required | email | unique(users,email)',
    'password:Password' => 'required | minlength(8) | maxlength(24)'
]);
if ($validator->validate($data)) {
    $auth->login($data);
}
```

### Template Engine
El Template Engine se puede obtener por construcción en un Controller utilizando la interface `App\Src\Template\TemplateEngine`, el método `render()` se encarga de buscar el template y procesarlo:
```php
$html = $this->template->render('example/home');
return response($html);
```
También se puede obtener mediante el objeto `View`, este encapsula el template compilado en un objeto `Response`:
```php
public function user(ServerRequestInterface $request): ResponseInterface
{
    $user = User::find(1);
    return $this->view->make('home', compact('user'));
}
```
Luego en `home.twig`.
```html
<p>{{ user->full_name }}</p>
```
Los templates se almacenan en la carpeta `resources/views`.

Configuración adicional en el archivo `app/Src/Provider/TemplateServiceProvider.php`.

### Debug
El error Handler del proyecto captura todos los errores-excepciones que no estén contemplados en un bloque tryCatch, indica de forma amigable el stack del error e incluso resalta el código con el error, obviamente solo se recomienda en ambiente de desarrollo, 
para habilitarlo hay que setear la variable de entorno: 
```sh
APP_DEBUG=true. 
```
Para ejecutar la consola interactiva se utiliza la siguiente función como breakpoint (solo funciona con el PHP built-in server):
```php
eval(debug());
```

### PSR-12 Validate
El código del proyecto se puede validar y corregir con el estándar PSR-12 de [PHP-FIG](https://www.php-fig.org/psr/psr-12/)
```shell
vendor/bin/phpcs -n -p --colors --report=summary --standard=psr12 app/
```

### Customization
En la carpeta `app/Src/Provider` se almacenan los Service Providers del proyecto, gran parte de la configuración se puede realizar directamente en esas clases, para extender del proyecto, agregar funcionalidades, solo es necesario crear un nuevo Service Provider sin olvidar registrarlo en la clase `App\Application`.
