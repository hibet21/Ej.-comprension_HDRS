<?php
namespace App\Models;
use App\DB\ConnectionDB;
use App\DB\Sql;
use App\Config\ResponseHTTP;

class UserModel extends ConnectionDB{
    private static $nombre;
    private static $dni;
    private static $email;
    private static $rol;
    private static $clave;
    private static $confirmaClave;
    private static $IDToken;
    private static $fecha;

    public function __construct(array $data){
        self::$nombre = $data['nombre'];
        self::$dni = $data['dni'];
        self::$email = $data['email'];
        self::$rol = $data['rol'];
        self::$clave = $data['clave'];
        self::$confirmaClave = $data['ConfirmaClave'];
        self::$IDToken = $data['IDToken'];
        self::$fecha = $data['fecha'];
    }
    //GET
    final public static function getNombre(){return self::$nombre;}
    final public static function getDni(){return self::$dni;}
    final public static function getEmail(){return self::$email;}
    final public static function getRol(){return self::$rol;}
    final public static function getClave(){return self::$clave;}
    final public static function getConfirmaClave(){return self::$confirmaClave;}
    final public static function getIDToken(){return self::$IDToken;}
    final public static function getFecha(){return self::$fecha;}
    //SET
    final public static function setNombre($nombre){self::$nombre = $nombre;}
    final public static function setDni($dni){self::$dni = $dni;}
    final public static function setEmail($email){self::$email = $email;}
    final public static function setRol($rol){self::$rol = $rol;}
    final public static function setClave($clave){self::$clave = $clave;}
    final public static function setConfirmaClave($confirmaClave){self::$confirmaClave = $confirmaClave;}
    final public static function setIDToken($IDToken){self::$IDToken = $IDToken;}
    final public static function setFecha($fecha){self::$fecha = $fecha;}




    final public static function UserSave(){
        
        //verificamos que el usuario no existe (SQL donde tengo un metodo para verificar dinamicamente si existe o no un registro )
        if(Sql::verificarRegistro('CALL ConsultarAbogadoDNI(:dni)', ':dni', self::getDni())){
            return ResponseHTTP::status400("El DNI esta registrado en la BD!");
        }else{
            //datos necesarios para almacenar Token y la fecha 
            self::setIDToken(hash('sha512', self::getDni().self::getEmail()));
            self::setFecha(date('d-m-y H:i:s'));

            try{
                $con = self::getConnection();
                $query = "CALL RegistrarAbogado(:nombre, :dni, :email, :rol, :clave, :confirmaClave, :IDToken, :fecha)";
                $stmt = $con->prepare($query);
                $stmt->execute([
                    ':nombre' => self::getNombre(),
                    ':dni' => self::getDni(),
                    ':email' => self::getEmail(),
                    ':rol' => self::getRol(),
                    ':clave' => self::getClave(),
                    ':confirmaClave' => self::getConfirmaClave(),
                    ':IDToken' => self::getIDToken(),
                    ':fecha' => self::getFecha()
                ]);

                if($stmt->rowCount() > 0){ //resp SGBD  = 1
                    return ResponseHTTP::status201("El usuario fue registrado exitosamente!");
                }else{
                    return ResponseHTTP::status200("El usuario no se registro por favor verifique!");
                }
            }catch(\PDOException $e){
                error_log('Error en el UserModel: '.$e);
                die(ResponseHTTP::status500("Hubo un error al almacenar el registro verificar!"));
            }
        }
    }

}
?>