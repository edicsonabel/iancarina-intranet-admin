<?php
require_once('conexion.php');
session_start();
$_SESSION['Departamento'] = "Tecnologia";

if (isset($_POST['crear_noticia'])) {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $depto =  $_SESSION['Departamento'];
    $fecha = $_POST['fecha'];
    $autor = $_POST['autor'];

    if ($titulo == '' || $contenido == '' || $depto == '' || $fecha == '' || $autor == '') {
        $res = [
            'status' => 422,
            'message' => 'Todos los campos son obligatorios'
        ];
        echo json_encode($res);
        return;
    }

    // Verificar si se recibió correctamente el archivo adjunto
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];

        // Validar la extensión del archivo  .jpg, .jpeg, .png
        $allowedExtensions = array('.jpg', '.jpge', '.png');
        $fileExtension = strtolower(strrchr($imagen['name'], '.'));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $res = [
                'status' => 422,
                'message' => 'La extensión del archivo no es válida'
            ];
            echo json_encode($res);
            return;
        }

        // Validar el tamaño del archivo (en bytes)
        $maxSizeInBytes = 5242880; // 5 MB
        if ($imagen['size'] > $maxSizeInBytes) {
            $res = [
                'status' => 422,
                'message' => 'El tamaño del archivo supera el límite permitido'
            ];
            echo json_encode($res);
            return;
        }

        // Obtener el nombre del archivo y cambiarlo por el título
        $nombreArchivo = $titulo . $fileExtension;
        $rutaArchivo = 'Imagenes/' . $nombreArchivo;

        // Mover el archivo a la carpeta de destino
        if (!move_uploaded_file($imagen['tmp_name'], $rutaArchivo)) {
            $res = [
                'status' => 500,
                'message' => 'Error al mover el archivo a la carpeta de destino'
            ];
            echo json_encode($res);
            return;
        }
    } else {
        $res = [
            'status' => 422,
            'message' => 'No se recibió el archivo adjunto'
        ];
        echo json_encode($res);
        return;
    }

    // Preparar la consulta SQL con marcadores de posición
    $sql = "INSERT INTO Noticias (TITULO,CONTENIDO,IMAGEN,FECHA,AUTOR,DEPARTAMENTO) VALUES (:titulo,:contenido,:imagen,:fecha,:autor,:depto)";
    $stmt = $conexion->prepare($sql);

    // Asociar los valores a los marcadores de posición
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':contenido', $contenido);
    $stmt->bindParam(':imagen', $rutaArchivo);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':depto', $depto);

    if ($stmt->execute()) {
        $rowsInserted = $stmt->rowCount(); // Obtener el número de filas afectadas

        if ($rowsInserted > 0) {
            $res = [
                'status' => 200,
                'message' => 'Documento creado exitosamente'
            ];
            echo json_encode($res);
        } else {
            $res = [
                'status' => 500,
                'message' => 'Error al crear el documento'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error al crear el documento'
        ];
        echo json_encode($res);
    }
}

if (isset($_POST['eliminar_noticia'])) {
    $noticia_id = $_POST['noticia_id'];
    //buscar el nombre actual del documento
    $stmt_doc = $conexion->prepare("SELECT IMAGEN FROM noticias WHERE id = :noticia_id");
    $stmt_doc->bindParam(':noticia_id', $noticia_id);
    $stmt_doc->execute();
    $rutaDocumento = $stmt_doc->fetchColumn();

    if (unlink($rutaDocumento)) {
        // El documento se ha eliminado correctamente
        $sql = "DELETE FROM noticias WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $noticia_id);

        if ($stmt->execute()) {
            $rowsDeleted = $stmt->rowCount();  // Verificar el número de filas eliminadas

            if ($rowsDeleted > 0) {
                $res = [
                    'status' => 200,
                    'message' => 'noticia eliminada correctamente'
                ];
                echo json_encode($res);
            } else {
                $res = [
                    'status' => 404,
                    'message' => 'No se encontró el noticia a eliminar'
                ];
                echo json_encode($res);
            }
        } else {
            $res = [
                'status' => 500,
                'message' => 'Error al eliminar la noticia'
            ];
            echo json_encode($res);
        }
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error al eliminar la noticia'
        ];
        echo json_encode($res);
    }
}

if (isset($_GET['noticia_id'])) {
    $noticia_id = $_GET['noticia_id'];
    // Preparar la consulta SQL con marcadores de posición
    $sql = "SELECT ID, TITULO, CONTENIDO, IMAGEN, DATE_FORMAT(FECHA, '%d/%m/%Y') as FECHA, AUTOR, DEPARTAMENTO FROM Noticias WHERE id = :id;";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $noticia_id);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener los resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($results) {
        $res = [
            'status' => 200,
            'message' => 'Noticia obtenido exitosamente',
            'data' => $results
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Noticia no encontrado'
        ];
        echo json_encode($res);
        return;
    }
}


if (isset($_POST['editar_noticia'])) {
    $noticia_id = $_POST['noticia_id'];
    $titulo = $_POST['titulo_edit'];
    $autor = $_POST['autor_edit'];
    $fecha = $_POST['fecha_edit'];
    $contenido = $_POST['contenido_edit'];
    $depto = $_SESSION['Departamento'];

    if ($titulo == '' || $contenido == '' || $autor == '' || $fecha == '') {
        $res = [
            'status' => 422,
            'message' => 'Todos los campos son obligatorios'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    // // Verificar si se recibió correctamente un nuevo archivo adjunto
    if (isset($_FILES['imagen_edit']) && $_FILES['imagen_edit']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen_edit'];
        // Validar la extensión del archivo  .jpg, .jpeg, .png
        $allowedExtensions = array('.jpg', '.jpeg', '.png');
        $fileExtension = strtolower(strrchr($imagen['name'], '.'));
        if (!in_array($fileExtension, $allowedExtensions)) {
            $res = [
                'status' => 422,
                'message' => 'La extensión del archivo no es válida'
            ];
            echo json_encode($res);
            return;
        }

        // Validar el tamaño del archivo (en bytes)
        $maxSizeInBytes = 5242880; // 5 MB
        if ($imagen['size'] > $maxSizeInBytes) {
            $res = [
                'status' => 422,
                'message' => 'El tamaño del archivo supera el límite permitido'
            ];
            echo json_encode($res);
            return;
        }
        //Buscar y Eliminar la imagen anterior
        $stmt_doc = $conexion->prepare("SELECT IMAGEN FROM noticias WHERE id = :noticia_id");
        $stmt_doc->bindParam(':noticia_id', $noticia_id);
        $stmt_doc->execute();
        $rutaImagenAntigua = $stmt_doc->fetchColumn();
        unlink($rutaImagenAntigua);

        //Guardar la imagen nueva 
        // Obtener el nombre del archivo y cambiarlo por el título
        $nombreArchivo = $titulo . $fileExtension;
        $rutaArchivo = 'Imagenes/' . $nombreArchivo;

        // Mover el archivo a la carpeta de destino
        if (!move_uploaded_file($imagen['tmp_name'], $rutaArchivo)) {
            $res = [
                'status' => 500,
                'message' => 'Error al mover el archivo a la carpeta de destino'
            ];
            echo json_encode($res);
            return;
        }

        $sql = "UPDATE noticias SET TITULO = :titulo, DEPARTAMENTO = :depto, CONTENIDO = :contenido, IMAGEN = :imagen, AUTOR = :autor, FECHA = :fecha WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':depto', $depto);
        $stmt->bindParam(':contenido', $contenido);
        $stmt->bindParam(':imagen', $rutaArchivo);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id', $noticia_id);


        if ($stmt->execute()) {
            // Actualización exitosa
            $res = [
                'status' => 200,
                'message' => 'La Noticia ha sido actualizada correctamente'
            ];
            echo json_encode($res);
            return;
        } else {
            // Error en la actualización
            $res = [
                'status' => 500,
                'message' => 'Error al actualizar la noticia'
            ];
            echo json_encode($res);
            return;
        }
    }
}
