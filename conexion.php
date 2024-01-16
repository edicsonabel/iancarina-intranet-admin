<?php
class Conexion
{
    public static function Conectar()
    { {
            define('servidor', 'localhost');
            define('nombrebd', 'BD_MARY');
            define('usuario', 'sa');
            define('password', 'whatTh3fuck**');
        }


        try {
            $conexion = new PDO("sqlsrv:Server=" . servidor . ";Database=" . nombrebd . ";TrustServerCertificate=true", usuario, password);
            //echo "Se conecto correctamente"; //solo se usa para probar la conexion con el servidor en el crud
            return $conexion;
        } catch (Exception $e) {
            die("El error de conexión es: " . $e->getMessage());  // captura el mensaje de error de conexión con el servidor
        }
    }
}

$objeto = new Conexion();
$conexion = $objeto->Conectar();
