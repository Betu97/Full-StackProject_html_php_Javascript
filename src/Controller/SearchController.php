<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Model\Item;

final class SearchController
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

            $items = $this->itemize($_POST["title"]);
            // We should validate the information before creating the entity


            $response->withStatus(201);
            return $this->container->get('view')->render($response, 'home.twig', ['items' => $items]);


        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }

    public function itemize(string $title): array
    {
        $repository = $this->container->get('user_repo');

        $data = $repository->searchItem($title);
        $count = 0;
        $items = array();


        foreach ($data as $parts) if ($count++ < 5) {
            $item = new Item(
                $parts['title'],
                $parts['owner'],
                $parts['description'],
                $parts['price'],
                $parts['product_image'],
                $parts['is_active'],
                $parts['category'],
                new DateTime(),
                new DateTime()
            );

            $image_name = "";
            $extensions = array('jpg', 'png');
            foreach ($extensions as $ext) {
                $file_name = __DIR__ . '/../../public/assets/Images/' . $item->getProductImage() . '.' . $ext;
                if (file_exists($file_name)) {
                    $image_name = $item->getProductImage() . '.' . $ext;
                    break;
                }
            }
            $item->setProductImage($image_name);

            array_push($items, $item);
        }

        return $items;
    }
}
