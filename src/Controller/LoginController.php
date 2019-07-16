<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Controller\HomeController;

final class LoginController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * HelloController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function formAction(Request $request, Response $response): Response
    {
        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'login.twig', ['logged'  => $logged]);
    }

    public function loginAction(Request $request, Response $response): Response
    {
        // This method decodes the received json
        $data = $request->getParsedBody();

        $errors = $this->validate($data);

        if (count($errors) > 0) {
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'login.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
        }

        /** @var PDORepository $repository */
        $repository = $this->container->get('user_repo');

        $user = $repository->signIn($data['username'], $data['password']);

        if($user != -1 && $user != -2) {
            $_SESSION['id'] = $user;
            $_SESSION['username'] = $data['username'];
            if(isset($data['remember'])){
                $info = strval($user);
                setcookie("userId", $info, time() + 60 * 120);
            }else{
                setcookie("userId", "", time() - 3600);
            }
            $home = new HomeController($this->container);
            $home->loadAction($request, $response);

            return $response;
        }

        unset($_SESSION['id']);
        if($user == -2) {
            $errors['notFound'] = 'The user is not already registered';
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'login.twig',
                ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
        }else{
            $errors['notFound'] = 'The password is not correct';
            $logged = isset($_SESSION['id']);
            return $this->container->get('view')->render($response, 'login.twig',
                ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
        }

    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'The username cannot be empty.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters.';
        }



        return $errors;
    }
}
