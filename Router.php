<?php

namespace MVC;

class Router {

    public $rutasGET = [];
    public $rutasPOST = [];

    public function get ($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post ($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas () {

        session_start();

        $auth = $_SESSION['login'] ?? null;

        //Arreglo de rutas protegidas
        $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', 
        '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        $urlActual = $_SERVER['REQUEST_URI'] === '' ? '/' : $_SERVER['REQUEST_URI'] ; //leer y validar que es una url valida
        $metodo = $_SERVER['REQUEST_METHOD']; //revisar que metodo es
    
        if($metodo === 'GET') {
            $fn = $this->rutasGET[$urlActual] ?? null; //leer la funcion de la url actual
        } else {
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }

        //proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth) { //revisar que la url actual seauna ruta protegida, y si el usuario quiere acceder y no esta autenticada, se redirecciona a pag principal
            header('Location: /');
        }

        if($fn) {
            //La URL existe y hay una funcion asociada

            call_user_func($fn, $this); //llamar una funcion que no sabemos como se llama

        } else {
            
            echo 'PAGINA NO ENCONTRADA';
        }
    }

    //MOSTRAR UNA VISTA

    public function view ($view, $datos = []) {

        foreach($datos as $key => $value) {
            $$key = $value;
        }

        ob_start(); //almacenar datos en memoria
        include __DIR__ ."/views/$view.php";

        $contenido = ob_get_clean(); //limpiar datos en memoria, todo lo que hayamos limpiado se coloca en esta variable
        include __DIR__ . "/views/layout.php";
    }
}