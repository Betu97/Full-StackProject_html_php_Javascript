<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
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

    public function loadAction(Request $request, Response $response): Response
    {
        try {

            $repository = $this->container->get('user_repo');
            $items = array();
            $i = $repository->getMaxId();
            $count = 0;
            while($count < 5 && $i > 0){
                $item = $this->itemize($i);
                $i = $i - 1;
                if(!isset($_SESSION['id'])) {
                    if (!$item->getSold()) {
                        array_push($items, $item);
                        $count = $count + 1;
                    }
                }else{
                    if (!$item->getSold() && $item->getOwner() != $_SESSION['id']) {
                        array_push($items, $item);
                        $count = $count + 1;
                    }
                }
            }

            // We should validate the information before creating the entity

            $response->withStatus(201);

            $logged = isset($_SESSION['id']);

            if($logged) {
                return $this->container->get('view')->render($response, 'home.twig',
                    ['items' => $items, 'logged' => $logged, 'mine' => 0, 'user' => $_SESSION['id']]);
            }
            return $this->container->get('view')->render($response, 'home.twig',
                ['items' => $items, 'logged' => $logged, 'mine' => 0, 'user' => -2]);

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

    public function signOutAction(Request $request, Response $response): Response
    {
        unset($_SESSION['id']);

        return $this->loadAction($request, $response);
    }
}
