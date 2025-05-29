<?php
header('Content-Type: application/json');

function moveDir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                moveDir($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
            } else {
                rename($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
            }
        }
    }
    closedir($dir);
    rmdir($src); // Remove the source directory
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movePath = $_POST['movePath'];
    $newLocation = $_POST['newLocation'];

    if (isset($movePath) && isset($newLocation)) {
        $newLocationPath = realpath(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . $newLocation);

        if ($newLocationPath && is_dir($newLocationPath)) {
            $decodedMovePath = json_decode($movePath, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedMovePath)) {
                // movePath es un JSON de una lista de rutas
                foreach ($decodedMovePath as $path) {
                    if (is_dir($path)) {
                        // Move directory
                        $newPath = $newLocationPath . DIRECTORY_SEPARATOR . basename($path);
                        moveDir($path, $newPath);
                    } elseif (is_file($path)) {
                        // Move file
                        $newPath = $newLocationPath . DIRECTORY_SEPARATOR . basename($path);
                        if (!rename($path, $newPath)) {
                            echo json_encode(['message' => 'Error al mover el archivo ' . $path], JSON_THROW_ON_ERROR);
                            exit;
                        }
                    } else {
                        echo json_encode(['message' => 'El elemento a mover no es un archivo ni una carpeta válida: ' . $path], JSON_THROW_ON_ERROR);
                        exit;
                    }
                }
                echo json_encode(['message' => 'Elementos movidos con éxito']);
            } else {
                // movePath es solo un texto
                $newPath = $newLocationPath . DIRECTORY_SEPARATOR . basename($movePath);
                if (is_dir($movePath)) {
                    // Move directory
                    moveDir($movePath, $newPath);
                    echo json_encode(['message' => 'Carpeta movida con éxito']);
                } elseif (is_file($movePath)) {
                    // Move file
                    if (rename($movePath, $newPath)) {
                        echo json_encode(['message' => 'Archivo movido con éxito']);
                    } else {
                        echo json_encode(['message' => 'Error al mover el archivo'], JSON_THROW_ON_ERROR);
                    }
                } else {
                    echo json_encode(['message' => 'El elemento a mover no es un archivo ni una carpeta válida'], JSON_THROW_ON_ERROR);
                }
            }
        } else {
            echo json_encode(['message' => 'Nueva ubicación no válida'], JSON_THROW_ON_ERROR);
        }
    } else {
        echo json_encode(['message' => 'Datos incompletos'], JSON_THROW_ON_ERROR);
    }
} else {
    echo json_encode(['message' => 'Método no permitido'], JSON_THROW_ON_ERROR);
}
