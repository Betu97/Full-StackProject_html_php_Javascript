<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 22:54
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Model\Item;

final class UpdateItemController
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

    public function updateItem(Request $request, Response $response): Response
    {
        $logged = isset($_SESSION['id']);
        $mine = 1;
        /** @var PDORepository $repository */
        $repository = $this->container->get('user_repo');
        $data = $request->getParsedBody();
        $errors = $this->validate($data);

        $item = $this->itemize($data['item']);
        if (count($errors) > 0) {
            return $this->container->get('view')->render($response, 'overview.twig', ['errors' => $errors, 'logged'  => $logged, 'item' => $item, 'mine' => $mine])->withStatus(404);
        }
        if (!empty($data)){
            $repository->updateOverview($data);
        }

        $item = $this->itemize($data['item']);

        echo'<script type="text/javascript">
                alert("You have successfully update the item");
            </script>';

        return $this->container->get('view')->render($response, 'overview.twig', ['errors' => $errors, 'logged'  => $logged, 'item' => $item, 'mine' => $mine])->withStatus(200);
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

    private function validate(array $data): array
    {
        $errors = [];

        $categories = array("Computers and electronic", "Cars", "Sports", "Games", "Fashion", "Home", "Other");

        if (!empty($data['description']) && strlen($data['description']) < 20) {
            $errors['description'] = 'The description must have 20 characters minimum';
        }

        if (!empty($data['price']) && !preg_match("/^\d+(\.\d{1,2})?$/" , $data['price'])) {
            $errors['price'] = 'The price must have a correct format';
        }

        if (!empty($data['category'])){
            if(!in_array($data['category'], $categories)){
                $errors['category'] = 'The category does not exist';
            }
        }

        return $errors;
    }
}