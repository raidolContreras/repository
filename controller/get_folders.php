<?php
header('Content-Type: application/json');

function getFolders($baseDir, $currentDir = '', $excludedDirs = []) {
    $folders = [];
    $dir = realpath($baseDir . DIRECTORY_SEPARATOR . $currentDir);

    if ($dir === false || !is_dir($dir)) {
        return $folders;
    }

    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $absolutePath = $dir . DIRECTORY_SEPARATOR . $file;
            $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $absolutePath);
            if (is_dir($absolutePath) && !in_array($relativePath, $excludedDirs)) {
                $folders[] = [
                    'text' => $file,
                    'id' => $relativePath,
                    'children' => getFolders($baseDir, $relativePath, $excludedDirs)
                ];
            }
        }
        closedir($dh);
    }

    return $folders;
}

$baseDir = dirname(__DIR__, 1);
$excludedDirs = ['config', 'controller', 'model', 'view'];

$folders = getFolders($baseDir, '', $excludedDirs);

// Incluye la raíz explícitamente
$foldersWithRoot = [
    [
        'text' => basename($baseDir),
        'id' => '/',
        'children' => $folders
    ]
];

echo json_encode($foldersWithRoot);
