<?php
// controller/ajax/folders.php

header('Content-Type: application/json');

// Acción enviada por AJAX
$action   = $_POST['action'] ?? '';

// Archivo donde se almacenarán las carpetas ocultas
$jsonFile = __DIR__ . '/folder_permissions.json';

// Ruta base donde se encuentran las carpetas que quieres listar
// Ajusta '../..' hasta llegar a tu directorio de carpetas
$baseDir  = realpath(__DIR__ . '/../..');

// Helper: obtener lista de carpetas en $baseDir
function listFolders(string $dir): array {
    return array_filter(scandir($dir), function($item) use ($dir) {
        return $item !== '.' 
            && $item !== '..' 
            && is_dir($dir . DIRECTORY_SEPARATOR . $item);
    });
}

if ($action === 'get_folders') {
    // 1) Cargar lista de ocultas desde JSON
    $hidden = [];
    if (file_exists($jsonFile)) {
        $data = json_decode(file_get_contents($jsonFile), true);
        if (is_array($data)) {
            $hidden = $data;
        }
    }

    // 2) Escanear carpetas y armar respuesta
    $folders = listFolders($baseDir);
    $out = [];
    foreach ($folders as $folder) {
        $out[] = [
            'id'      => $folder,
            'name'    => $folder,
            'visible' => !in_array($folder, $hidden, true)
        ];
    }

    echo json_encode($out);
    exit;
}

if ($action === 'update_permissions') {
    try {
        // 1) Recopilar carpetas que vienen marcadas (visibles)
        $checked = [];
        foreach ($_POST as $key => $val) {
            if (strpos($key, 'folder_') === 0) {
                $checked[] = substr($key, 7); // quita "folder_"
            }
        }

        // 2) Escanear todas las carpetas y calcular las ocultas
        $all = listFolders($baseDir);
        $hidden = array_values(array_diff($all, $checked));

        // 3) Guardar JSON
        $saved = file_put_contents($jsonFile, json_encode($hidden, JSON_PRETTY_PRINT));
        if ($saved === false) {
            throw new Exception('No se pudo escribir el archivo de permisos.');
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => $e->getMessage()
        ]);
    }
    exit;
}

// Acción no válida
http_response_code(400);
echo json_encode([
    'success' => false,
    'error'   => 'Acción inválida'
]);
exit;
