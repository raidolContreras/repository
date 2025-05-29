<?php
function listFiles($dir) {
    $directories = [];
    $files = [];
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
                    if (is_dir($fullPath)) {
                        $directories[] = $file;
                    } else {
                        $files[] = $file;
                    }
                }
            }
            closedir($dh);
        }
    }

    echo "<div class='file-list'>";
    foreach ($directories as $file) {
        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        echo "<div class='file-item row'>
                <a href='#' data-path='" . urlencode($fullPath) . "' class='folder-link text-decoration-none'>
                    <i class='fas fa-folder'></i> $file
                </a>
              </div>";
    }

    foreach ($files as $file) {
        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        echo "<div class='file-item row'>
                <i class='fas fa-file'></i> $file
              </div>";
    }
    echo "</div>";
}

if (isset($_GET['dir'])) {
    $directory = realpath($_GET['dir']);
    listFiles($directory);
}
