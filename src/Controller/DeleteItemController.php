<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 14:34
 */

namespace SallePW\SlimApp\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        $repository->deleteItem($_SESSION['id']);
        echo'<script type="text/javascript">
                alert("You have deleted the item successfully");
        </script>';

        return $this->container->get('view')->render($response, 'home.twig', ['items' => $items, 'logged'  => $logged, 'mine' => 1]);
    }
}