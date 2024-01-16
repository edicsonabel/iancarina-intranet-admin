<?php
require_once('conexion.php');
$sql = "SELECT ID,TITULO,CONTENIDO,IMAGEN,CONVERT(varchar,FECHA,3) as FECHA, AUTOR, DEPARTAMENTO FROM Noticias";
$stmt = $conexion->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devuelve los datos como JSON
header("Content-Type: application/json");
echo json_encode($data);
