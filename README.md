## PHP Custom Skeleton
Este proyecto es una simple prueba de conceptos, la idea es generar una estructura
sencilla con las herramientas básicas necesarias para construir aplicaciones en PHP
sin estar en el ambiente de un framework especifico. En el camino, al ver como 
interactúan estas 'herramientas' se puede entender (al menos de forma superficial)
el funcionamiento interno de un framework.

### Herramientas
A continuación una lista con todas las librerías que se utilizan en este proyecto.

 * [Error Handler - Whoops](https://github.com/filp/whoops)
 * [IoC Dependency Injection - Auryn](https://github.com/rdlowrey/Auryn)
 * [Http Definition - Request & Response](https://github.com/PatrickLouys/http)
 * [Router - Phroute](https://github.com/mrjgreen/phroute)
 * [Template Engine - Twig](https://twig.symfony.com)
 * [ORM - Eloquent](https://laravel.com/docs/5.5/database)
 * [Validator - Sirius Validation](https://github.com/siriusphp/validation)
 * [Environment - PHP DotEnv](https://laravel.com/docs/5.5/database)
 
### Instalación
Las dependencias del proyecto se gestionan mediante [Composer](https://getcomposer.org/)
el cual se puede instalar fácilmente con un par de comandos:
```shell
curl -sS https://getcomposer.org/installer | php
```
```shell
sudo mv ~/composer.phar /usr/local/bin/composer
```
Con `composer` instalado y estando en la raíz del proyecto, solo hace falta 
correr el siguiente comando:
```shell
composer install
```

### Configuración
Los parámetros de configuración (variables de entorno) se aplican desde el
archivo **.env** (renombrar el .env.example) que esta ubicado en la raíz del
proyecto y siguen el formato: `SECRET_KEY="12345"`, para tener acceso a dichas
variables se ocupa cualquiera de estos métodos:
```php
$key = getenv('SECRET_KEY');
$key = $_ENV['SECRET_KEY'];
$key = $_SERVER['SECRET_KEY'];
```

### Rutas
Las rutas de la aplicación se definen en el archivo `app/Http/routes.php`, estas
responden a cualquier verbo Http (get|post|put|patch|delete), cada ruta esta asociada 
a un __handler__, este puede ser un callback o un clase:
```php
$router->get('/example', function() {
    response()->setContent('Hello World!');
});

$router->get('/user/{id:i}', [App\Http\Controllers\UsersController::class, 'show']);
```
No es necesario que el __handler__ retorne algún valor, para obtener output en el
navegador es necesario setear el contenido en el objeto [Response](#http-response).

### Controladores
Los controladores se crean en la carpeta `app/Http/Controllers`, se recomienda heredar de la clase `App\Http\Controller` para obtener funcionalidad extra. Las 
dependencias de construcción se resuelven de forma automática por el contendedor IoC
instalado en la aplicación:
```php
public function __construct(Renderer $view, User $user)
{
    $this->view = $view;
    $this->user = $user;
}
```

### Autocarga PSR-4
Las clases de la aplicación se cargan de forma automática gracias a Composer, 
siempre y cuando se siga el estándar PSR-4 de [PHP-FIG](https://www.php-fig.org/psr/psr-4/).
```php
namespace App\SomeDirectory\ClassName;
```

### Validación PSR-2
El codigo del proyecto se puede validar y corregir con el estándar PSR-2 de [PHP-FIG](https://www.php-fig.org/psr/psr-2/)
```shell
./vendor/bin/phpcs --standard=psr2 app/
```

### Inyección de dependencias
La aplicación cuenta con un contenedor IoC que puede instanciar clases de forma 
automática siempre y cuando las dependencias no sean escalares, si este es el 
caso es necesario definir esta lógica en el archivo `app/Core/container.php`: 
```php
$injector->define('PDO', [
    ':dsn' => 'mysql:dbname=testdb;host=127.0.0.1',
    ':username' => 'dbuser',
    ':passwd' => 'dbpass'
]);
$db = $injector->make('PDO');
```

### Http Request
El objeto Request es un wrapper de las variables globales $_GET, $_POST, $_COOKIE,
$_FILES, $_SERVER. Se utiliza principalmente para obtener datos get|post
mediante una interfaz orientada a objetos:
```php
$this->request->getParameter('page', '1');
```
Se puede obtener de tres formas:
* Por herencia de la clase Controller con la propiedad `$request`.
* Pedirla en el constructor mediante la interface `Http\Request`.
* Mediante la función global (helpers.php) request().

### Http Response
Este objeto es necesario para enviar algún tipo de respuesta al cliente (navegador),
solo existe una instancia de Response en toda la aplicación (Injector Shared) y se 
puede obtener de tres formas:

* Por herencia de la clase Controller con la propiedad `$response`.
* Pedirla en el constructor mediante la interface `Http\Response`.
* Mediante la función global (helpers.php) response().

Con el objeto Response solo hace falta setear los headers correspondientes y el 
contenido, la aplicación se encarga de preparar y enviar la respuesta al cliente.
~~~PHP
$this->response->setHeader('Content-Type', 'application/json');
$this->response->setContent(json_encode([
    "bestDns" => "1.1.1.1"
]));
~~~

### Database ORM
El proyecto utiliza Eloquent para mapear la base de datos, los modelos deben extender
de la clase `App\Entities\Entity` para obtener toda la funcionalidad del ORM.

### Paginación
Para paginar elementos se puede utilizar el método `paginate()` del `App\Http\Controllers\Controller`,
se pueden paginar arrays genéricos y objetos tipo Laravel Collection. Este método retorna un objeto 
`illuminate\Pagination\LengthAwarePaginator` con toda la lógica necesaria para generar enlaces e iterar 
sobre la colección.

### Validación
El objeto Validator se puede obtener por construcción o creando una instancia en
cualquier método, toda la documentación se encuentra en la web del autor:
[SiriusValidation](http://www.sirius.ro/php/sirius/validation/).

### Templates
El template Engine se puede obtener por construcción en los controllers utilizando la 
interface `App\Core\Template\Renderer`, el método `render()` se encarga de buscar
el template y procesarlo:
~~~PHP
$html = $this->template->render('example/home');
~~~

### Debug
El error Handler del proyecto captura todos los errores-excepciones que no estén 
contemplados en un bloque tryCatch, indica de forma amigable el stack del error 
e incluso resalta el código con el error, obviamente solo se recomienda en desarrollo, 
para habilitarlo hay que setear la variable de entorno: 
```
APP_DEBUG=true. 
```