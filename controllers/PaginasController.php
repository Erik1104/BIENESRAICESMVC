<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
    
    public static function index (Router $router) {
        $propiedades = Propiedad::get(3); //traer 3 propiedades
        $inicio = true; //usar el header principal

        $router->view('paginas/index', [
            "propiedades" => $propiedades,
            "inicio" => $inicio
        ]);
    }

    public static function nosotros (Router $router) {
        
        $router->view('paginas/nosotros', []);
    }

    public static function propiedades (Router $router) {

        $propiedades = Propiedad::all();

        $router->view('paginas/propiedades', [
            "propiedades" => $propiedades
        ]);
    }

    public static function propiedad (Router $router) {

        $id = validarOredireccionar('/propiedades');
        $propiedad = Propiedad::find($id); //encontrar propiedad por su id

        $router->view('paginas/propiedad', [
            "propiedad" => $propiedad
        ]);
    }

    public static function blog (Router $router) {
        
        $router->view('paginas/blog', []);
    }

    public static function entrada (Router $router) {

        $router->view('paginas/entrada', []);
    }

    public static function contacto (Router $router) {

        $mensaje = null;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $respuestas = $_POST['contacto'];

            //Crear una instancia de php mailer
            $mail = new PHPMailer();

            //configurar smtp
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '2e2107df9c88f8';
            $mail->Password = 'd43d47c44c4e23';
            $mail->SMTPSecure = 'tls'; //vayan seguros los emails
            $mail->Port = 2525;

            //configurar el contenido del email
            $mail->setFrom('admin@bienesraices.com'); //quien envia el email
            $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com'); //a quien le llega el email
            $mail->Subject = 'Tienes un nuevo mensaje';

            //Habilitar html
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            //Definir el contenido
            $contenido = '<html>';
            $contenido .= '<p>tienes un nuevo mensaje</p>'>
            $contenido .= '<p>Nombre: '. $respuestas['nombre']  .' </p>';
            

            //enviar de forma condicional algunos campos de email o telefono
            if($respuestas['contacto'] === 'telefono') {

                $contenido .= '<p>Eligio ser contactado por telefono </p>';
                $contenido .= '<p>Telefono: '. $respuestas['telefono']  .' </p>';
                $contenido .= '<p>Fecha contacto: '. $respuestas['fecha']  .' </p>'; 
                $contenido .= '<p>Hora: '. $respuestas['hora']  .' </p>'; 
                
            } else {

                //agregamos el campo de email
                $contenido .= '<p>Eligio ser contactado por email </p>';
                $contenido .= '<p>Email: '. $respuestas['email']  .' </p>';
            }

            $contenido .= '<p>Mensaje: '. $respuestas['mensaje']  .' </p>';
            $contenido .= '<p>Vende o compra: '. $respuestas['tipo']  .' </p>';
            $contenido .= '<p>Precio o presupuesto: $'. $respuestas['precio']  .' </p>'; 
            $contenido .= '<p>Prefiere ser contactado por: '. $respuestas['contacto']  .' </p>';  
            $contenido .= '</html>';

            $mail->Body = $contenido;
            $mail->AltBody = 'Esto es texto alternativo sin html';

            //enviar el email
            if($mail->send()) {
                $mensaje =  'mensaje enviado correctamente';
            } else {
                $mensaje = 'mensaje no se pudo enviar';
            }
        }
        
        $router->view('paginas/contacto', [
            'mensaje' => $mensaje
        ]);
    }
}