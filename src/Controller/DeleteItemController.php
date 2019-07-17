<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 14:34
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Model\Item;


final class DeleteItemController
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

    public function deleteAction(Request $request, Response $response): Response
    {
        $repository = $this->container->get('user_repo');
        $data = $request->getParsedBody();
        $items = array();
        for ($i = 1; $i <= $repository->getMaxId(); $i++) {
            $item = $this->itemize($i);
            if ($item->getOwner() == $_SESSION['id'] && $item->getIsActive()) {
                array_push($items, $item);
            }
        }
        $logged = isset($_SESSION['id']);
        $repository->deleteItem($data['item']);
        echo'<script type="text/javascript">
                alert("You have deleted the item successfully");
        </script>';

        return $this->container->get('view')->render($response, 'home.twig', ['items' => $items, 'logged'  => $logged, 'mine' => 1]);
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