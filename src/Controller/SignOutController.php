<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 22:47
 */

namespace SallePW\SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;


final class SignOutController
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

    public function signOutAction(Request $request, Response $response): Response
    {
        unset($_SESSION['id']);
        setcookie("userId", "", time() - 3600);

        $home = new HomeController($this->container);
        $home->loadAction($request, $response);

        return $response;
    }
}