<?php

    
    use App\Controllers\UserController; //cargando del nombre de espacios Controllers la clase UserController
    use App\Config\ResponseHTTP; //cargando del nombre de espacios Config la clase ResponseHTTP

    $method = strtolower($_SERVER['REQUEST_METHOD']); //capturando el metodo de la peticion y lo convertimos en minusculas
    $data = json_decode(file_get_contents('php://input'), true); //capturando los datos enviados en el body de la peticion y decodificandolos a un array asociativo
    $route = $_GET['route']; //capturando la ruta de la peticion
    $params = explode('/', $_GET['route']); //capturando los parametros enviados en la ruta de la peticion y separandolos en un array
    $headers = getallheaders(); //capturando las cabeceras de la peticion
   
    //print_r($headers);
    $user = new UserController($method, $data, $route, $params, $headers);
    $user->UserSave('users'); //llamando a la clase UserController y al metodo estatico UserSave


   /* switch($method){
        case 'post':
            UserController::UserSave();
            break;
        case 'get':
            echo ResponseHTTP::status200('Metodo GET no implementado!'); //llamando a la clase ResponseHTTP y al metodo estatico status200
            break;
        case 'put':
            echo ResponseHTTP::status200('Metodo PUT no implementado!'); //llamando a la clase ResponseHTTP y al metodo estatico status200
            break;
        case 'delete':
            echo ResponseHTTP::status200('Metodo DELETE no implementado!'); //llamando a la clase ResponseHTTP y al metodo estatico status200
            break;
        default:
            echo ResponseHTTP::status404('Metodo no permitido!'); //llamando a la clase ResponseHTTP y al metodo estatico status405
            break;
    }  */  
?>