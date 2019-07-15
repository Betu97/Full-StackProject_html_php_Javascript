<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 25/04/2019
 * Time: 21:04
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Model\User;
use Psr\Http\Message\UploadedFileInterface;

final class RegisterController
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
        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'register.twig', ['logged'  => $logged]);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {
            $logged = isset($_SESSION['id']);
            $data = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();

            /** @var UploadedFileInterface $uploadedFile */
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

                $name = $data['username'] . '.' . $format;

                $extensions = array('jpg', 'png');
                foreach ($extensions as $ext) {
                    $file_name = __DIR__ . '/../../public/uploads/' . $data['username'] . '.' . $ext;
                    if (file_exists($file_name)) {
                        unlink($file_name);
                        continue;
                    }
                }
                // We generate a custom name here instead of using the one coming form the form
                $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $name);
            }
            if (!empty($errors)){
                return $this->container->get('view')->render($response, 'register.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
            }


            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');

            $errors = $this->validate($data, $repository->checkUser($data['username']));

            if (count($errors) > 0) {
                return $this->container->get('view')->render($response, 'register.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
            }

            // We should validate the information before creating the entity
            $user = new User(
                $data['name'],
                $data['username'],
                $data['email'],
                new DateTime($data['birthdate']),
                $data['phone_number'],
                $data['password'],
                true,
                new DateTime(),
                new DateTime()
            );

            $repository->save($user);
        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
        $errors['notLogged'] = 'You have been successfully registered';
        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'login.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(201);
    }

    private function validate(array $data, int $unique): array
    {
        $errors = [];

        if (empty($data['name'])){
            $errors['name'] = 'The name cannot be empty';
        }

        if (!ctype_alnum($data['name'] )){
            $errors['nameFormat'] = sprintf('The name must contain only alphanumerical characters');
        }

        if (empty($data['username']) || strlen($data['username']) > 20) {
            $errors['username'] = 'The username must be between 1 and 20 characters';
        }

        if (!ctype_alnum($data['username'] )){
            $errors['usernameFormat'] = sprintf('The username must contain only alphanumerical characters');
        }

        if ($unique != -1) {
            $errors['usernameCaught'] = 'The username is already in use';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'The password must contain at least 6 characters';
        }

        if (false === filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = sprintf('The email %s is not valid', $data['email']);
        }

        if (strcmp($data['confirm_password'], $data['password'])) {
            $errors['repPassword'] = "Password confirmation doesn't match password";
        }


        return $errors;
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