<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Model\Item;

final class OverviewController
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

        if(!isset($_SESSION['id'])){
            $errors['notLogged'] = 'You need to be logged in to access this content';
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(403);
        }

        try {

            $mine = 0;
            $data = $request->getParsedBody();

            $item = $this->itemize($data['image']);
            if($item->getSold()){
                $logged = isset($_SESSION['id']);

                return $this->container->get('view')->render($response, 'error404.twig', ['logged'  => $logged])->withStatus(404);
            }
            if ($item->getOwner() == $_SESSION['id']) {
                $mine = 1;
            }

            // We should validate the information before creating the entity

            $response->withStatus(201);
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'overview.twig', ['item' => $item, 'logged'  => $logged, 'mine' => $mine]);

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }

    public function updateItem(Request $request, Response $response): Response
    {

    }
    public function itemize(int $index): Item
    {
        $repository = $this->container->get('user_repo');

        $data = $repository->loadItem($index);

        $item = new Item(
            $data['title'],
            $data['owner'],
            $data['description'],
            $data['price'],
            $data['product_image'],
            $data['category'],
            $data['is_active'],
            $data['sold'],
            new DateTime(),
            new DateTime()
        );
        $item->setId($index);
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

        return $item;
    }
}
