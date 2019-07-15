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
        if(!isset($_SESSION['id'])){
            $errors['notLogged'] = 'You need to be logged in to access this content';
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(403);
        }

        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'addItem.twig', ['logged'  => $logged]);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $errors = $this->validate($data);

            if (count($errors) > 0) {
                $logged = isset($_SESSION['id']);

                return $this->container->get('view')->render($response, 'addItem.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
            }

            // We should validate the information before creating the entity
            $item = new Item(
                $data['title'],
                $_SESSION['id'],
                $data['description'],
                $data['price'],
                -1,
                $data['category'],
                true,
                new DateTime(),
                new DateTime()
            );

            $repository->saveItem($item);
            var_dump($item->getId());
            //$repository->insertProductImage($item->getId());

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        echo'<script type="text/javascript">
                alert("You have successfully added an item");
        </script>';

        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'addItem.twig', ['logged'  => $logged])->withStatus(201);
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

        if (!preg_match("/^\d+(\.\d{1,2})?$/" , $data['price'])) {
            $errors['price'] = 'The price must have a correct format';
        }

        return $errors;
    }
}