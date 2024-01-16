<?php
class Conexion
{
    public static function Conectar()
    { {
            define('servidor', '');
            define('nombrebd', '');
            define('usuario', '');
            define('password', '');
        }


        try {
            $conexion = new PDO("pgsql:host=" . servidor . ";dbname=" . nombrebd, usuario, password);
            //echo "Se conecto correctamente"; //solo se usa para probar la conexión con el servidor en el CRUD
            return $conexion;
        } catch (PDOException $e) {
            die("El error de conexión es: " . $e->getMessage());  // captura el mensaje de error de conexión con el servidor
        }
    }
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
