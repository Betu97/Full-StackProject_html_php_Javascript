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

    public function loadAction(Request $request, Response $response): Response
    {
        try {

            $items = $this->itemize($_POST["title"]);
            // We should validate the information before creating the entity


            $response->withStatus(201);

            if(!isset($_SESSION['id']) && isset($_COOKIE['userId'])){
                $_SESSION['id'] = $_COOKIE['userId'];
            }

            $logged = isset($_SESSION['id']);

            if($logged) {
                return $this->container->get('view')->render($response, 'home.twig',
                    ['items' => $items, 'logged' => $logged, 'mine' => 0, 'user' => $_SESSION['id']]);
            }
            return $this->container->get('view')->render($response, 'home.twig', ['items' => $items, 'logged' => $logged, 'mine' => 0, 'user' => -2]);


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
                $parts['category'],
                $parts['is_active'],
                $parts['sold'],
                new DateTime(),
                new DateTime()
            );

            $item->setId($parts['id']);
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

            if($item->getIsActive()) {
                array_push($items, $item);
            }
        }

        return $items;
    }
}
