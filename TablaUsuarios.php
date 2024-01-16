<?php
require_once('conexion.php');
$sql = "SELECT ID,USUARIO,CLAVE,NOMBRE,DEPARTAMENTO,NIVEL FROM Usuarios";
$stmt = $conexion->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devuelve los datos como JSON
header("Content-Type: application/json");
echo json_encode($data);
