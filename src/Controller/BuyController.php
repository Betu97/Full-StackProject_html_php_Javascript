<?php

namespace SallePW\SlimApp\Controller;

use DateTime;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Model\Item;
use PHPMailer\PHPMailer\PHPMailer;

final class BuyController
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

    public function loadAction(Request $request, Response $response): Response
    {

        if(!isset($_SESSION['id'])){
            $errors['notLogged'] = 'You need to be logged in to access this content';
            $logged = isset($_SESSION['id']);

            return $this->container->get('view')->render($response, 'error403.twig', ['errors' => $errors, 'logged'  => $logged])->withStatus(403);
        }

        try {

            $data = $request->getParsedBody();
            $email = [];
            $repository = $this->container->get('user_repo');
            mail('albert.m_97@hotmail.com', 'An item has been sold!', 'User has bought your product! The phone number of this user is: 651066477');

            $info = $repository->loadItem($data['item']);

            $email ['product'] = $info['title'];
            if($info['owner'] != -1) {
                $owner = $repository->loadUser($info['owner']);
                $email ['email'] = $owner['email'];
                $buyer = $repository->loadUser($_SESSION['id']);
                $email ['buyerName'] = $buyer['username'];
                $email ['buyerPhone'] = $buyer['phone_number'];
                $this->sendEmail($email);
            }
            $repository->buy($data['item']);

            // We should validate the information before creating the entity

            $response->withStatus(201);
            $home = new HomeController($this->container);
            $home->loadAction($request, $response);

            return $response;
        } catch (\Exception $e) {
            $response->getBody()->write('Unexpected error: ' . $e->getMessage());
            return $response->withStatus(500);
        }
    }

    public function sendEmail(array $email): Bool
    {
        $mail = new PHPMailer();

        //Luego tenemos que iniciar la validación por SMTP:
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com"; // A RELLENAR. Aquí pondremos el SMTP a utilizar. Por ej. mail.midominio.com
        $mail->Username = "pwpopam@gmail.com"; // A RELLENAR. Email de la cuenta de correo. ej.info@midominio.com La cuenta de correo debe ser creada previamente.
        $mail->Password = "Asdfasdf1+"; // A RELLENAR. Aqui pondremos la contraseña de la cuenta de correo
        $mail->Port = 587; // Puerto de conexión al servidor de envio.
        $mail->From = "pwpopam@gmail.com"; // A RELLENAR Desde donde enviamos (Para mostrar). Puede ser el mismo que el email creado previamente.
        $mail->FromName = "Pwpop"; //A RELLENAR Nombre a mostrar del remitente.
        $mail->AddAddress($email['email']); // Esta es la dirección a donde enviamos
        $mail->IsHTML(true); // El correo se envía como HTML
        $mail->Subject = "An item has been sold!"; // Este es el titulo del email.
        $body = $email['buyerName'];
        $body .= " has bought your product: ";
        $body .= $email ['product'];
        $body .="! The phone number of this user is: ";
        $body .=$email ['buyerPhone'];
        $mail->Body = $body;
        try {
            // Mensaje a enviar.
            $exito = $mail->Send();
            // Envía el correo.
            if ($exito) {
                return true;
            } else {
                return false;
            }
        }catch (\Exception $e){

        }
        return false;
    }

}
