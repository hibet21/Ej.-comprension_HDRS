<?php
//gestor dependencias es composer cargo todas las dependencias de la carpeta vendor
require dirname(__DIR__).'/vendor/autoload.php';
use App\Config\Errorlogs; //cargando del nombre de espacios Config la clase Errorlogs
Errorlogs::activar_error_logs(); //llamando a la clase Errorlogs y al metodo estatico activar_error_logs
use App\Config\ResponseHTTP; //cargando del nombre de espacios Config la clase ResponseHTTP

if(isset($_GET['route'])){ //verifica si existe la ruta (isset vs empty)
    //print_r($_GET);
    $url = explode('/',$_GET['route']); //separa la ruta en un array 0 = route
    //print_r($url);
    $lista = ['auth', 'users', 'productos', 'ventas', 'inventario']; // lista de rutas permitidas
    $file = dirname(__DIR__) . '/Src/Routes/' . $url[0] . '.php'; //endpoint de la ruta 
                
    if(!in_array($url[0], $lista)){  //arg A se busca en el array arg B si no existe devuelve false
        echo ResponseHTTP::status200('No existe la ruta solicitada!'); //llamando a la clase ResponseHTTP y al metodo estatico status404
        error_log('No existe la ruta solicitada!'); //registrando el error en el archivo de log
        exit;
    }else{ //si existe la ruta se verifica si el archivo existe y es legible
        if(!(file_exists($file)) || !(is_readable($file))){
            echo ResponseHTTP::status404('El archivo no existe o no es legible!'); //llamando a la clase ResponseHTTP y al metodo estatico status500
            exit;
        }else{            
            require $file; //si existe el archivo se carga
            exit;
        }
    }
}else{
    echo ResponseHTTP::status200('No existe la ruta solicitada!'); //llamando a la clase ResponseHTTP y al metodo estatico status404
}
?>