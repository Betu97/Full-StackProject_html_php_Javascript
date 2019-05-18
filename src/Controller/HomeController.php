<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Model\Database\PDORepository;
use SallePW\SlimApp\Model\Item;

final class HomeController
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

    /** public function registerAction(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            /** @var PDORepository $repository * /
            $repository = $this->container->get('user_repo');

            // We should validate the information before creating the entity
            $user = new User(
                $data['email'],
                $data['password'],
                new DateTime(),
                new DateTime()
            );

            $repository->save($user);
        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
        return $response->withStatus(201);
    } */

    public function loadAction(Request $request, Response $response): Response
    {
        try {

            $item = $this->itemize(1);
            $items = array( $item);
            for ($i = 2; $i <= 5; $i++) {
                $item = $this->itemize($i);
                array_push($items, $item);
            }


            // We should validate the information before creating the entity


            $response->withStatus(201);
            return $this->container->get('view')->render($response, 'home.twig', ['items' => $items]);


        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }

    public function itemize(int $index): Item
    {
        $repository = $this->container->get('user_repo');

        $data = $repository->loadItem($index);

        $item = new Item(
            $data['title'],
            $data['description'],
            $data['price'],
            $data['product_image'],
            $data['category'],
            new DateTime(),
            new DateTime()
        );

        return $item;
    }
}
