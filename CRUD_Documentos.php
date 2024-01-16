<?php
require_once('conexion.php');
session_start();
$_SESSION['Departamento'] = "Tecnologia";

if (isset($_GET['documento_id'])) {
    $documento_id = $_GET['documento_id'];
    // Preparar la consulta SQL con marcadores de posición
    $sql = "SELECT TITULO,DESCRIPCION,UBICACION FROM Documentos WHERE id = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $documento_id);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener los resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if ($results) {
        $res = [
            'status' => 200,
            'message' => 'Documento obtenido exitosamente',
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

if (isset($_POST['eliminar_documento'])) {
    $documento_id = $_POST['documento_id'];
    //buscar el nombre actual del documento
    $stmt_doc = $conexion->prepare("SELECT UBICACION FROM documentos WHERE id = :documento_id");
    $stmt_doc->bindParam(':documento_id', $documento_id);
    $stmt_doc->execute();
    $rutaDocumento = $stmt_doc->fetchColumn();

    if (unlink($rutaDocumento)) {
        // El documento se ha eliminado correctamente
        $sql = "DELETE FROM Documentos WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $documento_id);

        if ($stmt->execute()) {
            $rowsDeleted = $stmt->rowCount();  // Verificar el número de filas eliminadas

            if ($rowsDeleted > 0) {
                $res = [
                    'status' => 200,
                    'message' => 'documento eliminado correctamente'
                ];
                echo json_encode($res);
            } else {
                $res = [
                    'status' => 404,
                    'message' => 'No se encontró el documento a eliminar'
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
    } else {
        $res = [
            'status' => 500,
            'message' => 'Error al eliminar el usuario'
        ];
        echo json_encode($res);
    }
}

if (isset($_POST['crear_documento'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $depto =  $_SESSION['Departamento'];

    if ($titulo == '' || $descripcion == '' || $depto == '') {
        $res = [
            'status' => 422,
            'message' => 'Todos los campos son obligatorios'
        ];
        echo json_encode($res);
        return;
    }

    // Verificar si se recibió correctamente el archivo adjunto
    if (isset($_FILES['documento']) && $_FILES['documento']['error'] === UPLOAD_ERR_OK) {
        $documento = $_FILES['documento'];

        // Validar la extensión del archivo
        $allowedExtensions = array('.pdf', '.doc', '.docx');
        $fileExtension = strtolower(strrchr($documento['name'], '.'));
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
        if ($documento['size'] > $maxSizeInBytes) {
            $res = [
                'status' => 422,
                'message' => 'El tamaño del archivo supera el límite permitido'
            ];
            echo json_encode($res);
            return;
        }

        // Obtener el nombre del archivo y cambiarlo por el título
        $nombreArchivo = $titulo . $fileExtension;
        $rutaArchivo = 'Documentos/' . $nombreArchivo;

        // Mover el archivo a la carpeta de destino
        if (!move_uploaded_file($documento['tmp_name'], $rutaArchivo)) {
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
    $sql = "INSERT INTO Documentos (TITULO,DESCRIPCION,UBICACION,DEPARTAMENTO) VALUES (:titulo,:descripcion,:ubicacion,:depto)";
    $stmt = $conexion->prepare($sql);

    // Asociar los valores a los marcadores de posición
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':ubicacion', $rutaArchivo);
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

if (isset($_POST['editar_documento'])) {
    $documento_id = $_POST['documento_id'];
    $titulo = $_POST['titulo_edit'];
    $descripcion = $_POST['descripcion_edit'];
    $depto = $_SESSION['Departamento'];

    if ($titulo == '' || $descripcion == '' || $documento_id == '') {
        $res = [
            'status' => 422,
            'message' => 'El título y la descripción son obligatorios'
        ];
        header('Content-Type: application/json');
        echo json_encode($res);
        return;
    }

    // Verificar si se recibió correctamente un nuevo archivo adjunto
    if (isset($_FILES['documento_edit']) && $_FILES['documento_edit']['error'] === UPLOAD_ERR_OK) {
        $documento = $_FILES['documento_edit'];

        // Validar la extensión del archivo
        $allowedExtensions = array('.pdf', '.doc', '.docx');
        $fileExtension = strtolower(strrchr($documento['name'], '.'));
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
        if ($documento['size'] > $maxSizeInBytes) {
            $res = [
                'status' => 422,
                'message' => 'El tamaño del archivo supera el límite permitido'
            ];
            echo json_encode($res);
            return;
        }


        //Buscar y Eliminar el documento anterior
        $stmt_doc = $conexion->prepare("SELECT UBICACION FROM documentos WHERE id = :documento_id");
        $stmt_doc->bindParam(':documento_id', $documento_id);
        $stmt_doc->execute();
        $rutaDocumentoAntigua = $stmt_doc->fetchColumn();
        unlink($rutaDocumentoAntigua);


        // Obtener el nombre del archivo y cambiarlo por el título
        $nombreArchivo = $titulo . $fileExtension;
        $rutaArchivo = 'Documentos/' . $nombreArchivo;

        // Mover el archivo a la carpeta de destino
        if (!move_uploaded_file($documento['tmp_name'], $rutaArchivo)) {
            $res = [
                'status' => 500,
                'message' => 'Error al mover el archivo a la carpeta de destino'
            ];
            echo json_encode($res);
            return;
        }

        $sql = "UPDATE documentos SET TITULO = :titulo, DEPARTAMENTO = :depto, DESCRIPCION = :descripcion, UBICACION = :ubicacion WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':nombre', $titulo);
        $stmt->bindParam(':depto', $depto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':ubicacion', $rutaArchivo);
        $stmt->bindParam(':id', $documento_id);


        if ($stmt->execute()) {
            // Actualización exitosa
            $res = [
                'status' => 200,
                'message' => 'Documento actualizado correctamente'
            ];
            echo json_encode($res);
            return;
        } else {
            // Error en la actualización
            $res = [
                'status' => 500,
                'message' => 'Error al actualizar el documento'
            ];
            echo json_encode($res);
            return;
        }
    } else {
        // Si no se recibió un nuevo archivo adjunto, actualiza solo el título y la descripción en la base de datos
        //buscar el nombre actual del documento
        $stmt_doc = $conexion->prepare("SELECT UBICACION FROM documentos WHERE id = :documento_id");
        $stmt_doc->bindParam(':documento_id', $documento_id);
        $stmt_doc->execute();
        $rutaDocumento = $stmt_doc->fetchColumn();
        $extension = substr($rutaDocumento, strrpos($rutaDocumento, '.') + 1);
        //renombramos el documento
        $rutaDocumentoNueva = 'Documentos/' . $titulo . '.' . $extension;
        rename($rutaDocumento, $rutaDocumentoNueva);

        $sql = "UPDATE documentos SET TITULO = :titulo, DESCRIPCION = :descripcion, UBICACION = :ubicacion WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':ubicacion', $rutaDocumentoNueva);
        $stmt->bindParam(':id', $documento_id);
        if ($stmt->execute()) {
            // Actualización exitosa
            $res = [
                'status' => 200,
                'message' => 'Documento editado exitosamente (sin cambios en el archivo)'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        } else {
            // Error en la actualización
            $res = [
                'status' => 500,
                'message' => 'Error al actualizar el documento'
            ];
            header('Content-Type: application/json');
            echo json_encode($res);
            return;
        }
    }
}
