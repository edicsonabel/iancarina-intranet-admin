<?php
require_once('conexion.php');

if (isset($_POST['crear_usuario'])) {
    $usuario =  $_POST['usuario'];
    $nombre =  $_POST['nombre'];
    $depto = $_POST['depto'];
    $clave = $_POST['clave'];
    $nivel = $_POST['nivel'];


    if ($usuario == NULL || $nombre == NULL || $depto == NULL || $clave == NULL || $nivel == NULL) {
        $res = [
            'status' => 422,
            'message' => 'Todos los campos son obligatorios'
        ];
        echo json_encode($res);
        return;
    }
    // Preparar la consulta SQL con marcadores de posición
    $sql = "INSERT INTO Usuarios (USUARIO,CLAVE,NOMBRE,DEPARTAMENTO,NIVEL) VALUES (:usuario,:clave,:nombre,:depto, :nivel)";
    $stmt = $conexion->prepare($sql);

    // Asociar los valores a los marcadores de posición
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':clave', $clave);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':depto', $depto);
    $stmt->bindParam(':nivel', $nivel);

    if ($stmt->execute()) {
        $rowsInserted = $stmt->rowCount();  // Verificar el número de filas insertadas

        if ($rowsInserted > 0) {
            $res = [
                'status' => 200,
                'message' => 'Usuario creado exitosamente'
            ];
            echo json_encode($res);
        } else {
            $res = [
                'status' => 500,
                'message' => 'Error al crear el usuario'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error al crear el usuario'
        ];
        echo json_encode($res);
    }
}

if (isset($_GET['usuario_id'])) {
    $usuario_id = $_GET['usuario_id'];
    // Preparar la consulta SQL con marcadores de posición
    $sql = "SELECT NOMBRE,DEPARTAMENTO,NIVEL,CLAVE FROM Usuarios WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $usuario_id);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener los resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($results) {
        $res = [
            'status' => 200,
            'message' => 'Usuario obtenido exitosamente',
            'data' => $results
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Usuario no encontrado'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['editar_usuario'])) {
    $usuario_id =  $_POST['usuario_id'];

    $nombre =  $_POST['nombre_edit'];
    $depto = $_POST['depto_edit'];
    $clave = $_POST['clave_edit'];
    $nivel = $_POST['nivel_edit'];

    if ($nombre == NULL || empty($depto) || $clave == NULL || $nivel == NULL) {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $sql = "UPDATE usuarios SET NOMBRE = :nombre, DEPARTAMENTO = :depto, CLAVE = :clave, NIVEL = :nivel WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':depto', $depto);
    $stmt->bindParam(':clave', $clave);
    $stmt->bindParam(':nivel', $nivel);
    $stmt->bindParam(':id', $usuario_id);


    if ($stmt->execute()) {
        // Actualización exitosa
        $res = [
            'status' => 200,
            'message' => 'Usuario actualizado correctamente'
        ];
        echo json_encode($res);
        return;
    } else {
        // Error en la actualización
        $res = [
            'status' => 500,
            'message' => 'Error al actualizar el usuario'
        ];
        echo json_encode($res);
        return;
    }
}

if (isset($_POST['eliminar_usuario'])) {
    $usuario_id = $_POST['usuario_id'];

    $sql = "DELETE FROM Usuarios WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $usuario_id);

    if ($stmt->execute()) {
        $rowsDeleted = $stmt->rowCount();  // Verificar el número de filas eliminadas

        if ($rowsDeleted > 0) {
            $res = [
                'status' => 200,
                'message' => 'Usuario eliminado correctamente'
            ];
            echo json_encode($res);
        } else {
            $res = [
                'status' => 404,
                'message' => 'No se encontró el usuario a eliminar'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error al eliminar el usuario'
        ];
        echo json_encode($res);
    }
}
