<?php

namespace App\Http\Controllers;

use Faker\Factory;
use App\Entities\User;
use App\Src\Response\View;
use Sirius\Validation\Validator;
use PSR7Sessions\Storageless\Http\SessionMiddleware;
use Laminas\Diactoros\Response\{JsonResponse,RedirectResponse};
use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};

class HomeController extends Controller
{
    private View $view;
    private Validator $validator;

    /**
     * El contenedor IoC se encarga de resolver las dependencias.
     * @param View $view
     * @param Validator $validator
     */
    public function __construct(View $view, Validator $validator)
    {
        $this->view = $view;
        $this->validator = $validator;
    }

    /*
     * El objeto View encapsula el template engine con un objeto Response.
     */
    public function index(): ResponseInterface
    {
        return $this->view->make('example/home');
    }

    /*
     * El objeto RedirectResponse se encarga de setear el status http y los headers necesarios para
     * redireccionar un recurso.
     */
    public function redirect(): ResponseInterface
    {
        return new RedirectResponse(getUrl('/app/data?name=John+Doe'));
    }

    /*
     * El método getQueryParams del Request permite acceder a los valores del QueryString.
     */
    public function data(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->make('example/data', [
            'name' => $request->getQueryParams()['name']
        ]);
    }

    /*
     * El objeto JsonResponse setea los headers necesarios y serialíza el array para obtener
     * un Response tipo JSON.
     */
    public function json(): ResponseInterface
    {
        return new JsonResponse($this->fakePerson());
    }

    /**
     * Todos los Handlers de un Controller reciben una instancia de ServerRequest y deben retornar
     * un Response, en el Request se puede obtener un objeto de Session que fue inyectado previamente
     * mediante un Middleware.
     */
    public function session(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var \PSR7Sessions\Storageless\Session\SessionInterface $session
         */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        $session->set('counter', $session->get('counter', 0) + 1);
        return $this->view->make('example/session', [
            'counter' => $session->get('counter')
        ]);
    }

    /*
     * El método paginate permite paginar sobre un array genérico o un objeto Collection.
     */
    public function arrayPaginate(): ResponseInterface
    {
        $users = $this->paginate($this->fakePerson(50));
        return $this->view->make('example/paginate', compact('users'));
    }

    /*
     * El método paginate de Eloquent resuelve el tema de la paginación internamente.
     */
    public function ormPaginate(): ResponseInterface
    {
        $users = User::orderBy('id', 'desc')->paginate(5);
        return $this->view->make('example/paginate', compact('users'));
    }

    public function create(): ResponseInterface
    {
        return $this->view->make('example/create');
    }

    /**
     * El objeto Validator permite validar los datos que se obtienen en el Request(form).
     */
    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $errors = [];
        $result = false;
        $data = $request->getParsedBody();
        $this->validator->add([
            'first_name:First Name' => 'required | alpha | minlength(3) | maxlength(50)',
            'last_name:Last Name' => 'required | alpha | minlength(3) | maxlength(50)',
            'email:Email' => 'required | email | unique(users,email)',
            'password:Password' => 'required | minlength(8) | maxlength(24)'
        ]);
        if ($this->validator->validate($data)) {
            $user = new User([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            $result = $user->save();
        } else {
            $errors = $this->validator->getMessages();
        }
        return $this->view->make('example/create', compact('result', 'errors', 'data'));
    }

    /**
     * Se utiliza Faker para generar datos falsos que pueden ser de utilidad para testear los features de la app.
     * @param int $total
     * @return array
     */
    private function fakePerson(int $total = 10, array $result = []): array
    {
        $faker = Factory::create();
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
