<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 11/07/2019
 * Time: 16:31
 */

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use SallePW\SlimApp\Model\Item;
use Psr\Http\Message\UploadedFileInterface;


final class AddItemController
{
    private const UPLOADS_DIR = __DIR__ . '/../../public/assets/Images';

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
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(403);
        }

        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'addItem.twig', ['logged'  => $logged]);
    }

    public function registerAction(Request $request, Response $response): Response
    {
        try {
            $logged = isset($_SESSION['id']);

            /** @var PDORepository $repository */
            $repository = $this->container->get('user_repo');
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

                $name = $repository->getMaxId() . '.' . $format;

                $extensions = array('jpg', 'png');
                foreach ($extensions as $ext) {
                    $file_name = __DIR__ . '/../../public/assets/Images' . $repository->getMaxId() . '.' . $ext;
                    if (file_exists($file_name)) {
                        unlink($file_name);
                        continue;
                    }
                }
                // We generate a custom name here instead of using the one coming form the form
                $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $name);
            }
            if (!empty($errors)){
                return $this->container->get('view')->render($response, 'addItem.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
            }

            $data = $request->getParsedBody();
            $errors = $this->validate($data);

            if (count($errors) > 0) {
                return $this->container->get('view')->render($response, 'addItem.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(404);
            }

            // We should validate the information before creating the entity
            $item = new Item(
                $data['title'],
                $_SESSION['id'],
                $data['description'],
                $data['price'],
                -1,
                $data['category'],
                true,
                false,
                new DateTime(),
                new DateTime()
            );

            $repository->saveItem($item);
            $repository->insertProductImage();

        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }

        echo'<script type="text/javascript">
                alert("You have successfully added an item");
        </script>';

        $logged = isset($_SESSION['id']);

        return $this->container->get('view')->render($response, 'addItem.twig', ['logged'  => $logged])->withStatus(201);
    }

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'The title cannot be empty';
        }

        if (empty($data['description']) || strlen($data['description']) < 20) {
            $errors['description'] = 'The description must have 20 characters minimum';
        }

        if (!preg_match("/^\d+(\.\d{1,2})?$/" , $data['price'])) {
            $errors['price'] = 'The price must have a correct format';
        }

        return $errors;
    }

    private function isValidFormat(string $extension): bool
    {
        return in_array($extension, self::ALLOWED_EXTENSIONS, true);
    }
}