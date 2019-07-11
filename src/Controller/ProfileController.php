<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 18/05/2019
 * Time: 19:04
 */

namespace SallePW\SlimApp\Controller;

use SallePW\SlimApp\Controller\HomeController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;


class ProfileController
{
    private const UPLOADS_DIR = __DIR__ . '/../../public/uploads';

    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file, upload the file again and then press Change Image";

    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";

    // We use this const to define the extensions that we are going to allow
    private const ALLOWED_EXTENSIONS = ['jpg', 'png'];



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

    public function formAction(Request $request, Response $response): Response
    {
        if(!isset($_SESSION['id'])){
            $errors['notLogged'] = 'You need to be logged in to access this content';
            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors])->withStatus(403);
        }
        $image_name = $this->getImageName();

        return $this->container->get('view')->render($response, 'profile.twig', ['image' => $image_name]);
    }

    public function deleteAction(Request $request, Response $response): Response
    {
        $repository = $this->container->get('user_repo');
        $repository->deleteAccount($_SESSION['id']);
        $errors['delete'] = 'You have deleted your account successfully';
        $home = new HomeController($this->container);
        $home->loadAction($request, $response);
        return $this->container->get('view')->render($response, 'home.twig', ['errors' => $errors])->withStatus(201);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        // This method decodes the received json
        $data = $request->getParsedBody();

        $errors = $this->validate($data);

        if (count($errors) > 0) {
            return $response->withJson(['errors' => $errors,], 404);
        }

        return $response->withJson([], 200);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'The username cannot be empty.';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters.';
        }

        return $errors;
    }

    public function uploadAction(Request $request, Response $response): Response
    {
        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];

        $name = "";

        /** @var UploadedFileInterface $uploadedFile */
        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }

            $name = $_SESSION['username'] . '.' . $format;

            $extensions = array('jpg', 'png');
            foreach ($extensions as $ext) {
                $file_name = __DIR__ . '/../../public/uploads/' . $_SESSION['username'] . '.' . $ext;
                if (file_exists($file_name)) {
                    unlink($file_name);
                    continue;
                }
            }
            // We generate a custom name here instead of using the one coming form the form
            $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $name);
        }

        if (!empty($errors)){
            $name = $this->getImageName();
        }
        return $this->container->get('view')->render($response, 'profile.twig', [
            'errors' => $errors,
            'image' => $name,
        ]);
    }

    public function getImageName(): String
    {
        $_SESSION['username'] = 'username';
        $image_name = "";
        $extensions = array('jpg', 'png');
        foreach ($extensions as $ext) {
            $file_name = __DIR__ . '/../../public/uploads/' . $_SESSION['username'] . '.' . $ext;
            if (file_exists($file_name)) {
                $image_name = $_SESSION['username'] . '.' . $ext;
                break;
            }
        }

        return $image_name;
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}