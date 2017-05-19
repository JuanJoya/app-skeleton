# Mini Framework MVC
Proyecto experimental para tener templates 100% libres de código, solo para propósitos de ámbito personal, en producción no tiene sentido alguno, construir la lógica de las vistas resulta engorroso y no permite código escalable. 
## Info
Para generar el proyecto:
````
composer install
````
Para correr los tests:
````
{global}  phpunit --bootstrap vendor/autoload.php tests
{local}     ./vendor/bin/phpunit tests
````
Parámetros de configuración:
````
URL - constante en public/index.php
variables de conexión con la DB en app/Core/DBAbstractModel
````