<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 25/04/2019
 * Time: 21:04
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Model\User;

final class RegisterController
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
        return $this->container->get('view')->render($response, 'register.twig', []);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            $errors = $this->validate($data);

            if (count($errors) > 0) {
                return $this->container->get('view')->render($response, 'register.twig', ['errors' => $errors])->withStatus(404);
            }

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            // We should validate the information before creating the entity
            $user = new User(
                $data['name'],
                $data['username'],
                $data['email'],
                new DateTime($data['birthdate']),
                $data['phone_number'],
                $data['password'],
                new DateTime(),
                new DateTime()
            );

            $repository->save($user);
        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
        $errors['notLogged'] = 'You have been successfully registered';
        return $this->container->get('view')->render($response, 'login.twig', ['errors' => $errors])->withStatus(201);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['name'])){
            //$error = { $message = 'The name cannot be empty.', $type = 'name'};
            $errors['name'] = 'The name cannot be empty.';
        }

        if (empty($data['username'])) {
            $errors['username'] = 'The username cannot be empty.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters.';
        }

        return $errors;
    }
}