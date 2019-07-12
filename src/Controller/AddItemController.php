<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 11/07/2019
 * Time: 16:31
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Model\Item;


final class AddItemController
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
        return $this->container->get('view')->render($response, 'addItem.twig', []);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $errors = $this->validate($data);

            if (count($errors) > 0) {
                return $this->container->get('view')->render($response, 'addItem.twig', ['errors' => $errors])->withStatus(404);
            }

            // We should validate the information before creating the entity
            $item = new Item(
                $data['title'],
                $data['owner'],
                $data['description'],
                $data['price'],
                $data['product_image'],
                $data['category'],
                $data['is_active'],
                new DateTime(),
                new DateTime()
            );

            $repository->saveItem($item);
        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
        $errors['notLogged'] = 'You have successfully added an item';
        return $this->container->get('view')->render($response, 'addItem.twig', ['errors' => $errors])->withStatus(201);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'The title cannot be empty';
        }

        if (empty($data['description']) || strlen($data['description']) < 20) {
            $errors['description'] = 'The description must have 20 characters minimum';
        }

        if (preg_match("/^[0-9]+(\.[0-9]{2})?$/" , $data['price'])) {
            $errors['price'] = 'The price must have a correct format';
        }

        if (false === filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = sprintf('The email %s is not valid', $data['email']);
        }

        if (strcmp($data['confirm_password'], $data['password'])) {
            $errors['repPassword'] = "Password confirmation doesn't match password";
        }


        return $errors;
    }
}