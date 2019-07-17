<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 22:32
 */

namespace SallePW\SlimApp\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DeleteAccountController
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
        $repository->deleteAccount($_SESSION['id']);
        $repository->deleteItems($_SESSION['id']);
        echo'<script type="text/javascript">
                alert("You have deleted your account successfully");
        </script>';
        $home = new HomeController($this->container);
        unset($_SESSION['id']);
        $home->loadAction($request, $response);

        return $response;
    }
}