<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 18/05/2019
 * Time: 19:04
 */

namespace SallePW\SlimApp\Controller;

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
        if(!isset($_SESSION['id']) && isset($_COOKIE['userId'])){
            $_SESSION['id'] = $_COOKIE['userId'];
        }

        $logged = isset($_SESSION['id']);

        if(!isset($_SESSION['id'])){
            $errors['notLogged'] = 'You need to be logged in to access this content';

            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(403);
        }
        $image_name = $this->getImageName();

        $repository = $this->container->get('user_repo');
        $user = $repository->loadUser($_SESSION['id']);
        $user['birthdate'] = date('Y-m-d', strtotime($user['birthdate']));
        return $this->container->get('view')->render($response, 'profile.twig', ['image' => $image_name, 'logged'  => $logged, 'user' => $user]);
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

    public function registerAction(Request $request, Response $response): Response
    {
        $logged = isset($_SESSION['id']);
        $repository = $this->container->get('user_repo');
        // This method decodes the received json
        $data = $request->getParsedBody();
        $name = $this->getImageName();
        if (empty($data)){
            $errors['empty'] = "There is no information to update";
            return $this->container->get('view')->render($response, 'profile.twig', ['errors' => $errors, 'logged'  => $logged, 'image' => $name])->withStatus(404);

        }
        $errors = $this->validate($data);

        if (count($errors) > 0) {
            return $this->container->get('view')->render($response, 'profile.twig', ['errors' => $errors, 'logged'  => $logged, 'image' => $name])->withStatus(404);
        }

        $repository->updateProfile($data, $_SESSION['id']);
        return $this->container->get('view')->render($response, 'profile.twig', ['logged'  => $logged, 'image' => $name])->withStatus(200);
    }


    private function validate(array $data): array
    {
        $errors = [];

        if (!empty($data['name']) && !ctype_alnum($data['name'] )){
            $errors['nameFormat'] = sprintf('The name must contain only alphanumerical characters');
        }

        if (!empty($data['email']) && false === filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = sprintf('The email %s is not valid', $data['email']);
        }

        return $errors;
    }

    public function uploadAction(Request $request, Response $response): Response
    {
        $uploadedFiles = $request->getUploadedFiles();

        $logged = isset($_SESSION['id']);

        $errors = [];

        $name = "";

        /** @var UploadedFileInterface $uploadedFile */
        if ($uploadedFiles['files']['0']->getSize() < 500000){
            foreach ($uploadedFiles['files'] as $uploadedFile) {
                if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                    $errors['file'] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                    continue;
                }

                $name = $uploadedFile->getClientFilename();

                $fileInfo = pathinfo($name);

                $format = $fileInfo['extension'];

                if (!$this->isValidFormat($format)) {
                    $errors['file'] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
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
        }
        if ($uploadedFiles['files']['0']->getSize() > 500000){
            $errors['file'] = "The file can't exceed 500KB";
        }
        if (!empty($errors)){
            $name = $this->getImageName();
        }
        return $this->container->get('view')->render($response, 'profile.twig', [
            'errors' => $errors,
            'image' => $name,
            'logged'  => $logged,
        ]);
    }

    public function getImageName(): String
    {
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