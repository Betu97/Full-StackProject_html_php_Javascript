<?php
/**
 * Created by PhpStorm.
 * User: msigr
 * Date: 17/07/2019
 * Time: 22:38
 */

namespace SallePW\SlimApp\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

final class UploadImageController
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