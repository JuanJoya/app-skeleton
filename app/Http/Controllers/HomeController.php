<?php

namespace App\Http\Controllers;

use App\Entities\User;
use Faker\Factory;
use Illuminate\Pagination\Paginator;
use Sirius\Validation\Validator;

class HomeController extends Controller
{
    /**
     * @var Validator objeto para validar Request.
     */
    private $validator;

    /**
     * HomeController constructor.
     * @param Validator $validator
     * El contenedor IoC se encarga de resolver las dependencias.
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /*
     * El objeto View es una Response genérica que internamente
     * utiliza el template engine para renderizar las vistas.
     */
    public function getIndex()
    {
        $this->view->make('example/home');
    }

    /*
     * El método redirect se encarga de setear los headers necesarios
     * y el http status para hacer la redirección de un recurso.
     */
    public function getRedirect()
    {
        $this->response->redirect(getUrl('/data?name=John+Doe'));
    }

    /*
     * El método getParameter del Request permite acceder a los valores
     * que se envíen por GET o POST.
     */
    public function getData()
    {
        $this->view->make('example/data', [
            'name' => $this->request->getParameter('name', 'Guest')
        ]);
    }

    /*
     * El objeto Json es una Response genérica que internamente
     * setea los headers necesarios y serialíza el array en formato
     * JSON.
     */
    public function getJson()
    {
        $this->json->make($this->fakePerson());
    }

    /*
     * El método paginate permite paginar sobre un array genérico
     * o una Collection de Laravel.
     */
    public function getArrayPaginate()
    {
        $users = $this->paginate($this->fakePerson(50));
        $this->view->make('example/paginate', compact('users'));
    }

    /*
     * El método paginate de Eloquent resuelve el tema de la paginación
     * internamente aunque es necesarios setear ciertos parámetros.
     */
    public function getOrmPaginate()
    {
        Paginator::currentPathResolver(function () {
            return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            $page = isset($_REQUEST[$pageName]) ? $_REQUEST[$pageName] : 1;
            return $page;
        });

        $users = User::orderBy('id', 'desc')->paginate(5);

        $this->view->make('example/paginate', compact('users'));
    }

    public function getCreate()
    {
        $this->view->make('example/create');
    }

    public function postCreate()
    {
        $errors = [];
        $result = false;
        $data = $this->request->getBodyParameters();

        $this->validator->add([
            'first_name:First Name' => 'required | alpha | minlength(3) | maxlength(50)',
            'last_name:Last Name' => 'required | alpha | minlength(3) | maxlength(50)',
            'email:Email' => 'required | email',
            'password:Password' => 'required | minlength(8) | maxlength(24)'
        ]);

        if ($this->validator->validate($data)) {
            $user = new User([
                'first_name' => $this->request->getParameter('first_name'),
                'last_name'  => $this->request->getParameter('last_name'),
                'email'      => $this->request->getParameter('email'),
                'password'   => password_hash(
                    $this->request->getParameter('password'),
                    PASSWORD_DEFAULT
                )
            ]);
            $result = $user->save();
        } else {
            $errors = $this->validator->getMessages();
        }

        $this->view->make('example/create', compact('result', 'errors', 'data'));
    }

    /**
     * Se utiliza Faker para generar datos falsos que pueden ser
     * de utilidad para testear los features de la app.
     * @param int $total
     * @return array
     */
    private function fakePerson(int $total = 10)
    {
        $result = [];
        $faker  = Factory::create();

        for ($i = 1; $i <= $total; $i++) {
            $result[] = [
                'id' => $i,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email
            ];
        }
        return $result;
    }
}
