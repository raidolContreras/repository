<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedFiles = $_POST['files'];

    $response = array();

    foreach ($selectedFiles as $file) {
        // Perform logic to delete the file, for example:
        if (unlink($file)) {
            $response['message'] = 'Archivos eliminados correctamente';
        } else {
            $response['message'] = 'Error al eliminar los archivos seleccionados';
            break;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>