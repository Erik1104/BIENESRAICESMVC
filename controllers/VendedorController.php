<?php 

namespace Controllers;

use MVC\Router;
use Model\Vendedor;

class VendedorController {

    public static function crear (Router $router) {
        $vendedor = new Vendedor;
        $errores = Vendedor::getErrores();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            /** Crea una nueva instancia */
            $vendedor = new Vendedor($_POST['vendedor']);
    
            // Validar
            $errores = $vendedor->validar();
    
            if(empty($errores)) {
               $resultado = $vendedor->guardar();

               if($resultado) {
                header('Location: /admin?resultado=4');
               }
            }
        }

        $router->view('vendedores/crear', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function actualizar (Router $router) {
      
       $errores = Vendedor::getErrores();
       $id = validarOredireccionar('/admin');
       $vendedor = Vendedor::find($id);

       if($_SERVER['REQUEST_METHOD'] === 'POST') {

          // Asignar los atributos
          $args = $_POST['vendedor']; 
          $vendedor->sincronizar($args);

          // Validación
          $errores = $vendedor->validar();
       
          if(empty($errores)) {
            $resultado = $vendedor->guardar();

            if($resultado) {
                header('Location: /admin?resultado=5');
            }
          }
        }

        $router->view('vendedores/actualizar', [
            'vendedor' => $vendedor,
            'errores' => $errores
        ]);
    }

    public static function eliminar (Router $router) {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //validar Id
            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if($id) {
               $tipo = $_POST['tipo'];
    
              // peticiones validas
              if( validarTipoContenido($tipo) ) {
                $vendedor = Vendedor::find($id);
                $resultado = $vendedor->eliminar();

                if($resultado) {
                   header('Location: /admin?resultado=6');
                }
              }
            }
        }

        $router->view('vendedores/eliminar', [
            'vendedor' => $vendedor
        ]);
    }
}