<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js" defer></script>

<!-- Tu otro código y estilos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js" defer></script>


<style>
    .dropdown-menu.show {
        z-index: 1001;
    }

    .floating-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: rgba(13, 130, 149, 0.9);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 20px 30px;
        border-radius: 18px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3), 0 0 15px rgba(13, 130, 149, 0.5);
        z-index: 1000;
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
        transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.3s ease;
        font-weight: bold;
        font-family: 'Arial', sans-serif;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Icono de notificación */
    .floating-notification .icon {
        width: 28px;
        height: 28px;
        background: white;
        mask-image: url('assets/notification.svg');
        mask-size: contain;
        mask-repeat: no-repeat;
        mask-position: center;
        opacity: 0.9;
        transition: transform 0.4s ease;
    }

    /* Efecto gradual en el texto */
    .floating-notification .text {
        max-width: 250px;
        line-height: 1.5;
        background: linear-gradient(135deg, #ffffff, #cceeff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Efecto de entrada */
    .floating-notification.show {
        opacity: 1;
        transform: translateY(0) scale(1);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3), 0 0 30px rgba(13, 130, 149, 0.7);
    }

    /* Efecto hover para aumentar el brillo */
    .floating-notification:hover {
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3), 0 0 40px rgba(13, 130, 149, 0.9);
    }

    /* Efecto de pulso en el icono */
    .floating-notification.show .icon {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }
    }

    /* Animación de entrada y salida */
    .floating-notification.show {
        animation: slideFadeIn 0.6s ease forwards;
    }

    @keyframes slideFadeIn {
        0% {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Animación de salida */
    .floating-notification.hide {
        animation: slideFadeOut 0.4s ease forwards;
    }

    @keyframes slideFadeOut {
        0% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        100% {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }
    }

    .pdf-thumbnail {
        width: 100%;
        cursor: pointer;
    }

    .pdf-container {
        position: relative;
    }

    .pdf-container .pdf-thumbnail {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .pdf-container .file-checkbox {
        position: absolute;
        top: -5px;
        left: 4px;
        width: 20px;
        height: 20px;
        z-index: 2;
        cursor: pointer;
        display: none;
        appearance: none;
        /* Elimina el estilo por defecto */
        border: 2px solid #aaa;
        border-radius: 15px;
        background-color: white;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    /* Hover: muestra el checkbox */
    .file-item:hover .file-checkbox {
        display: block;
    }

    /* Animación y color cuando se selecciona */
    .pdf-container .file-checkbox:checked {
        display: block;
        background-color: #01643d;
        /* Cambia el color al hacer check */
        border-color: #01643d;
        transform: scale(1.2);
        /* Hace un efecto de agrandamiento */
    }

    /* Añade una marca de verificación visual */
    .pdf-container .file-checkbox:checked::after {
        content: '✔';
        color: white;
        font-size: 16px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Cambia el fondo de .file-item cuando el checkbox dentro está marcado */
    .file-item:has(.file-checkbox:checked) {
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }
</style>

<div class="container-unimo mt-5">
    <h2>Explorador de Archivos</h2>
    <?php
    function debug($message)
    {
        echo "<script>console.log('Debug: " . addslashes($message) . "');</script>";
    }

    function formatSize($bytes)
    {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . @$sizes[$factor];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Renombrar
        if (isset($_POST['newName']) && isset($_POST['oldName'])) {
            $oldName = $_POST['oldName'];
            $newName = $_POST['newName'];
            $newPath = dirname($oldName) . DIRECTORY_SEPARATOR . $newName;
            if (rename($oldName, $newPath)) {
                echo "<script>showAlert('El nombre del directorio/archivo ha sido cambiado a $newName');</script>";
            } else {
                echo "<script>showAlert('Error al cambiar el nombre del directorio/archivo');</script>";
                debug("Error al renombrar: $oldName a $newPath");
            }
        }
        // Crear carpeta
        if (isset($_POST['newFolder'], $_POST['currentDir'])) {
            // 1) Quitar espacios al inicio y final
            $newFolderRaw  = $_POST['newFolder'];
            $newFolder     = trim($newFolderRaw);

            // 2) Validar que no quede vacío
            if ($newFolder === '') {
                echo "<script>showAlert('El nombre de la carpeta no puede estar vacío.');</script>";
                exit;
            }

            // (Opcional) 3) Sanitizar caracteres peligrosos
            // Por ejemplo, eliminar barras o puntos extrañas
            $newFolder = preg_replace('/[\/\\\\]+/', '', $newFolder);

            $currentDir     = $_POST['currentDir'];
            $newFolderPath  = $currentDir . DIRECTORY_SEPARATOR . $newFolder;

            // 4) Crear si no existe
            if (!is_dir($newFolderPath)) {
                if (mkdir($newFolderPath, 0755)) {
                    echo "<script>showAlert('Carpeta “{$newFolder}” creada con éxito');</script>";
                } else {
                    echo "<script>showAlert('Error al crear la carpeta “{$newFolder}”');</script>";
                    debug("Error al crear la carpeta: $newFolderPath");
                }
            } else {
                echo "<script>showAlert('La carpeta “{$newFolder}” ya existe');</script>";
            }
        }

        // Borrar archivo o carpeta (solo si viene deletePath)
        if (isset($_POST['deletePath'])) {
            $deletePath = $_POST['deletePath'];

            if ($_SESSION['level'] == '0') {
                // usuario administrador: puede borrar
                if (is_dir($deletePath)) {
                    function deleteDirectory($dir)
                    {
                        if (!is_dir($dir)) {
                            return false;
                        }
                        $files = array_diff(scandir($dir), array('.', '..'));
                        foreach ($files as $file) {
                            $fullPath = "$dir/$file";
                            if (is_dir($fullPath)) {
                                deleteDirectory($fullPath);
                            } else {
                                unlink($fullPath);
                            }
                        }
                        return rmdir($dir);
                    }
                    // tu función deleteDirectory()...
                    if (deleteDirectory($deletePath)) {
                        echo "<script>showAlert('Carpeta eliminada con éxito');</script>";
                    } else {
                        echo "<script>showAlert('Error al eliminar la carpeta');</script>";
                        debug("Error al eliminar la carpeta: $deletePath");
                    }
                } elseif (is_file($deletePath)) {
                    if (unlink($deletePath)) {
                        echo "<script>showAlert('Archivo eliminado con éxito');</script>";
                    } else {
                        echo "<script>showAlert('Error al eliminar el archivo');</script>";
                        debug("Error al eliminar el archivo: $deletePath");
                    }
                }
            } else {
                // usuario no administrador intentó borrar
                echo "<script>showAlert('No tienes permisos para eliminar archivos o carpetas');</script>";
                debug("Intento de eliminación sin permisos: $deletePath");
            }
        }

        // Subir archivos
        if (isset($_FILES['uploadFiles']) && isset($_POST['currentDir'])) {
            $currentDir = $_POST['currentDir'];
            $files = $_FILES['uploadFiles'];
            $uploadSuccess = true;
            for ($i = 0; $i < count($files['name']); $i++) {
                $fileName = basename($files['name'][$i]);
                $targetFilePath = $currentDir . DIRECTORY_SEPARATOR . $fileName;
                if (!move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                    $uploadSuccess = false;
                    $error_message = '';
                    switch ($files['error'][$i]) {
                        case UPLOAD_ERR_INI_SIZE:
                            $error_message = 'El archivo excede el tamaño permitido por upload_max_filesize en php.ini.';
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $error_message = 'El archivo excede el tamaño permitido por el formulario HTML.';
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $error_message = 'El archivo fue solo parcialmente subido.';
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $error_message = 'No se subió ningún archivo.';
                            break;
                        case UPLOAD_ERR_NO_TMP_DIR:
                            $error_message = 'Falta una carpeta temporal.';
                            break;
                        case UPLOAD_ERR_CANT_WRITE:
                            $error_message = 'No se pudo escribir el archivo en el disco.';
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $error_message = 'Una extensión de PHP detuvo la subida del archivo.';
                            break;
                        default:
                            $error_message = 'Error desconocido al subir el archivo.';
                            break;
                    }
                    echo "<script>showAlert('Error al subir el archivo: $fileName - $error_message');</script>";
                    debug("Error al subir el archivo: " . $files['error'][$i]);
                    break;
                }
            }
            if ($uploadSuccess) {
                echo "<script>showAlert('Archivos subidos con éxito');</script>";
            }
        }
        // Descomprimir archivos zip
        if (isset($_POST['unzipPath'])) {
            $zipPath = $_POST['unzipPath'];
            $extractTo = dirname($zipPath);
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $zip->extractTo($extractTo);
                $zip->close();
                echo "<script>showAlert('Archivo descomprimido con éxito');</script>";
            } else {
                echo "<script>showAlert('Error al descomprimir el archivo');</script>";
                debug("Error al descomprimir: $zipPath");
            }
        }
        // Renombrado masivo
        if (isset($_POST['bulkRename']) && isset($_POST['currentDir'])) {
            $dir = realpath($_POST['currentDir']);
            if ($dir && is_dir($dir)) {
                $files = array_diff(scandir($dir), ['.', '..']);
                $renamedCount = 0;
                foreach ($files as $file) {
                    $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
                    if (!is_file($fullPath)) continue;

                    // Extraer nombre y extensión
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    $name = pathinfo($file, PATHINFO_FILENAME);

                    // 1) Recortar espacios al inicio y final
                    $name = trim($name);

                    // 2) Reemplazar cualquier secuencia de espacios por guión bajo
                    $name = preg_replace('/\s+/', '_', $name);

                    // 3) Nuevo nombre completo
                    $newFile = $name . ($ext ? ".{$ext}" : '');

                    // Si cambia el nombre, hacer rename
                    if ($newFile !== $file) {
                        $newPath = $dir . DIRECTORY_SEPARATOR . $newFile;
                        if (!file_exists($newPath) && rename($fullPath, $newPath)) {
                            $renamedCount++;
                        }
                    }
                }
                echo "<script>showAlert('Renombrados $renamedCount archivos.');</script>";
            } else {
                echo "<script>showAlert('Directorio no válido para renombrar.');</script>";
            }
            
            if ($dir && is_dir($dir)) {
                $items = array_diff(scandir($dir), ['.', '..']);
                $renamedCount = 0;
    
                foreach ($items as $item) {
                    $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
                    if (!is_dir($fullPath)) {
                        continue;
                    }
    
                    // 1) Determinar el nuevo nombre
                    if (preg_match('/^\s*unidad\s*(\d+)/i', $item, $m)) {
                        // Unidad X → Unidad_{X}
                        $newName = "Unidad_{$m[1]}";
                    } else {
                        // Cualquier otro: trim, colapsar espacios, pasar a MAYÚSCULAS
                        $temp = trim($item);
                        $temp = preg_replace('/\s+/', '_', $temp);
                        $newName = strtoupper($temp);
                    }
    
                    // 2) Si realmente cambia algo...
                    if ($newName !== $item) {
                        $oldPath = $fullPath;
                        $newPath = $dir . DIRECTORY_SEPARATOR . $newName;
    
                        // 3) Detectar renombrado CASE-ONLY (Windows)
                        if (strcasecmp($item, $newName) === 0 && $item !== $newName) {
                            // Generamos un nombre temporal
                            $tmpName = $item . '__TMP__' . uniqid();
                            $tmpPath = $dir . DIRECTORY_SEPARATOR . $tmpName;
                            if (!rename($oldPath, $tmpPath)) {
                                // si falla el intermedio, saltamos
                                continue;
                            }
                            // ahora el “viejo” pasa a ser el tmp
                            $oldPath = $tmpPath;
                        }
    
                        // 4) Renombrar al nombre final
                        if (!file_exists($newPath) && rename($oldPath, $newPath)) {
                            $renamedCount++;
                        }
                    }
                }
    
                echo "<script>showAlert('Normalizadas $renamedCount carpetas.');</script>";
            } else {
                echo "<script>showAlert('Directorio no válido.');</script>";
            }
        }
    }

    function listFiles($dir)
    {
        if ($_SESSION['level'] != '0') {
            // cargar el archivo controller/ajax/folder_permissions.json
            $permissionsFile = 'controller/ajax/folder_permissions.json';
            // combiertelo en un array
            $permissions = json_decode(file_get_contents($permissionsFile), true);
        } else {
            $permissions = ['App_Data', 'assets', '.user.ini', 'favicon.ico', 'index.php', 'web.config', '.htaccess', 'config', 'controller', 'model', 'view'];
        }
        $directories = [];
        $files = [];
        $i = 0;
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
                        if (is_dir($fullPath)) {
                            $directories[] = $file;
                            $i++;
                        } else {
                            $files[] = $file;
                        }
                    }
                }
                closedir($dh);
            }
        }
        if ($i > 0) {
            echo "<div class='recent-files mt-4 folders'>
                        <h5>Carpetas</h5>
                        <div class='file-list'>";
            foreach ($directories as $file) {
                $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
                if (!in_array($file, $permissions)) {
                    echo "
                        <div class='file-item row folder-file'>
                            <a href='?dir=" . urlencode($fullPath) . "' class='row col-11 text-decoration-none' style='align-items: center;'>
                                <div class='d-flex align-items-center col-3'>
                                    <i class='fas fa-folder file-icon text-primary' aria-hidden='true'></i>
                                </div>
                                <div class='file-name-content col-9'>
                                    <span class='file-name'>$file</span>
                                </div>
                            </a>
                            <div class='dropdown dropdown-unimo col-1'>
                                <button class='btn-drop dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fa-duotone fa-ellipsis-vertical' aria-hidden='true'></i>
                                </button>
                                <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
                                    <li><a class='dropdown-item' href='#' onclick='openFolder(\"" . urlencode($fullPath) . "\")'>Abrir</a></li>
                                    <li>
                                        <a class='dropdown-item rename-button' data-type='folder' data-oldname='$fullPath' data-filename='$file' data-bs-toggle='modal' data-bs-target='#renameModal'>
                                            Renombrar
                                        </a>
                                    </li>";
                    if ($_SESSION['level'] == '0') {
                        echo "
                                    <li>
                                        <a class='dropdown-item delete-button-folder'>
                                            Borrar
                                        </a>
                                        <form class='delete-form' method='POST' action='' style='display: none;'>
                                            <input type='hidden' name='deletePath' value='$fullPath'>
                                        </form>
                                    </li><li>
                                        <a class='dropdown-item move-button' data-fullpath='$fullPath' data-filename='$file' data-bs-toggle='modal' data-bs-target='#moveModal'>
                                            Mover
                                        </a>
                                    </li>";
                    } else {
                        echo "";
                    }
                    echo "</ul>
                            </div>
                        </div>";
                }
            }
            echo "</div>
                    </div>
                    <hr>";
        }
        echo "<div class='file-list'>";
        foreach ($files as $file) {
            $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
            $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath($fullPath));
            $downloadUrl = 'https://' . $_SERVER['HTTP_HOST'] . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
            if (!in_array($file, ['App_Data', 'assets', '.user.ini', 'favicon.ico', 'index.php', 'web.config', '.htaccess'])) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                echo "<div class='file-item row item-file' data-download-url='$downloadUrl' style='padding: 20px; border-radius:16px;'>
                        <div class='row col-10' style='align-items: center;'>";
                // Si es un PDF, mostramos el contenedor de la miniatura (canvas)
                if ($extension === 'pdf') {
                    echo "<div class='col-3 pdf-container'>
                            <input type='checkbox' class='file-checkbox' data-path='$fullPath'>
                            <canvas class='pdf-thumbnail' data-pdf-url='$downloadUrl'></canvas>
                          </div>";
                } else {
                    // Para otros archivos mostramos el ícono
                    $fileIcon = getFileIcon($file);
                    echo "<div class='d-flex align-items-center col-3 pdf-container'>
                            <input type='checkbox' class='file-checkbox' data-path='$fullPath'>
                            <i class='$fileIcon file-icon text-primary' aria-hidden='true'></i>
                          </div>";
                }
                echo "      <div class='file-name-content col-9'>
                                <span class='file-name'>$file</span>
                            </div>
                        </div>
                        <div class='dropdown dropdown-unimo col-1'>
                            <button class='btn-drop dropdown-toggle' type='button' id='dropdownMenuButton1' data-bs-toggle='dropdown' aria-expanded='false'>
                                <i class='fa-duotone fa-ellipsis-vertical' aria-hidden='true'></i>
                            </button>
                            <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton1'>
                                <li><a class='dropdown-item' href='$downloadUrl' target='_blank'>Abrir</a></li>
                                <li>
                                    <a class='dropdown-item copy-link-button' data-download-url='$downloadUrl'>
                                        Copiar link
                                    </a>
                                </li>
                                <li>
                                    <a class='dropdown-item rename-button' data-type='document' data-oldname='$fullPath' data-filename='$file' data-bs-toggle='modal' data-bs-target='#renameModal'>
                                        Renombrar
                                    </a>
                                </li>
                                ";
                if ($_SESSION['level'] == '0') {
                    echo "
                                <li>
                                    <a class='dropdown-item delete-button'>
                                        Borrar
                                    </a>
                                    <form class='delete-form' method='POST' action='' style='display: none;'>
                                        <input type='hidden' name='deletePath' value='$fullPath'>
                                    </form>
                                </li><li>
                                            <a class='dropdown-item move-button' data-fullpath='$fullPath' data-filename='$file' data-bs-toggle='modal' data-bs-target='#moveModal'>
                                                Mover
                                            </a>
                                        </li>";
                } else {
                    echo "";
                }
                echo "
                                <li class='file-size'><i class='fa-duotone fa-files' aria-hidden='true'></i> Tamaño: " . formatSize(filesize($fullPath)) . "</li>
                                <li class='file-size'><i class='fa-duotone fa-calendar-days' aria-hidden='true'></i> Fecha: " . date("d.m.y H:i:s", filemtime($fullPath)) . "</li>
                            </ul>";
                if ($extension === 'zip') {
                    echo "<form class='unzip-form' method='POST' action=''>
                                <input type='hidden' name='unzipPath' value='$fullPath'>
                                <button type='submit' class='btn btn-sm btn-outline-success'><i class='fa-duotone fa-file-archive'></i></button>
                          </form>";
                }
                echo "  </div>
                    </div>";
            }
        }
        echo "</div>
                </div>";
    }

    function getFileIcon($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        switch (strtolower($extension)) {
            case 'pdf':
                return 'fad fa-file-pdf text-danger';
            case 'mp4':
            case 'mov':
            case 'avi':
            case 'mkv':
                return 'fad fa-file-video text-warning';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return 'fad fa-file-image text-success';
            default:
                return 'fad fa-file text-secondary';
        }
    }

    function createBreadcrumb($directory, $baseDir)
    {
        $relativePath = str_replace($baseDir, '', $directory);
        $parts = explode(DIRECTORY_SEPARATOR, $relativePath);
        $breadcrumb = [];
        $path = $baseDir;
        $breadcrumb[] = "<li class='breadcrumb-item'><a href='./'>Inicio</a></li>";
        foreach ($parts as $part) {
            if ($part != "") {
                $path .= DIRECTORY_SEPARATOR . $part;
                if ($part == 'D:' || $part == 'Plesk' || $part == 'Vhosts' || $part == 'unimontrer.edu.mx' || $part == 'repositorio.unimontrer.edu.mx') {
                    continue;
                } else {
                    $breadcrumb[] = "<li class='breadcrumb-item'><a href='?dir=" . urlencode(realpath($path)) . "'>$part</a></li>";
                }
            }
        }
        return implode("", $breadcrumb);
    }

    $baseDir = getcwd();
    $directory = isset($_GET['dir']) ? $_GET['dir'] : $baseDir;
    if ($directory !== false && strpos(realpath($directory), realpath($baseDir)) === 0) {
        $directory = realpath($directory);
    } else {
        $directory = $baseDir;
    }
    echo "<nav aria-label='breadcrumb'><ol class='breadcrumb'>";
    echo createBreadcrumb($directory, $baseDir);
    echo "</ol></nav>";
    ?>
    <div class="row">
        <div class="col">
            <form class="new-folder-form" method="POST" action="">
                <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($directory); ?>">
                <input type="text" name="newFolder" class="form-control" placeholder="Nombre de la nueva carpeta" required>
                <button type="submit" class="btn btn-primary">Crear Carpeta</button>
            </form>
        </div>
        <div class="col">
            <form class="upload-file-form" method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($directory); ?>">
                <input type="file" name="uploadFiles[]" class="form-control" multiple required>
                <button type="submit" class="btn btn-primary">Subir Archivos</button>
            </form>
            <div id="progressWrapper" class="progress" style="display:none;">
                <div id="progressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>
        <div class="d-none">
            <!-- Bulk Rename Form -->
            <form id="bulkRenameForm" method="POST">
                <input type="hidden" name="currentDir" value="<?php echo htmlspecialchars($directory); ?>">
                <input type="hidden" name="bulkRename" value="1">
            </form>

        </div>
    </div>
    <?php listFiles($directory); ?>
    <div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="renameModalLabel">Renombrar Archivo/Carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="renameForm" method="POST" action="">
                        <input type="hidden" name="oldName" id="modalOldName">
                        <div id="dropdownContainer"></div>
                        <div class="mb-3">
                            <div class="presentName"></div>
                            <label for="modalNewName" class="form-label">Nuevo nombre</label>
                            <input type="text" class="form-control" id="modalNewName" name="newName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Renombrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($_SESSION['level'] == '0') : ?>
    <div class="modal fade" id="moveModal" tabindex="-1" aria-labelledby="moveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveModalLabel">Mover archivos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Está a punto de mover archivos/directorios.</p>
                    <form id="moveForm" method="POST" action="">
                        <input type="hidden" name="movePath" id="modalMovePath">
                        <div class="mb-3">
                            <label for="modalNewLocation" class="form-label">Mover al siguiente directorio:</label>
                            <div id="folderTree" class="tree-view"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script>
    $(document).ready(function() {
        // Manejo de clic derecho para copiar URL
        $('.item-file').on('contextmenu', function(event) {
            event.preventDefault();
            var downloadUrl = $(this).data('download-url');
            if (downloadUrl) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(downloadUrl).then(function() {
                        showAlert("URL copiada al portapapeles: " + downloadUrl);
                    }, function(err) {
                        console.error('Error al copiar la URL: ', err);
                    });
                } else {
                    var tempInput = $('<input>');
                    $('body').append(tempInput);
                    tempInput.val(downloadUrl).select();
                    document.execCommand('copy');
                    tempInput.remove();
                    showAlert("URL copiada al portapapeles: " + downloadUrl);
                }
            }
        });
        // Función para mostrar alertas
        function showAlert(message) {
            var alertDiv = $('<div></div>', {
                class: 'floating-notification',
                html: `<div class="icon"></div><div class="text">${message}</div>`
            });
            $('body').append(alertDiv);
            setTimeout(function() {
                alertDiv.addClass('show');
            }, 100);
            setTimeout(function() {
                alertDiv.removeClass('show').addClass('hide');
                setTimeout(function() {
                    alertDiv.remove();
                }, 400);
            }, 3000);
        }
        // Configurar el worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

        const renderPDFThumbnails = () => {
            const canvases = document.querySelectorAll('.pdf-thumbnail');
            canvases.forEach(canvas => {
                const pdfUrl = canvas.getAttribute('data-pdf-url');
                if (!pdfUrl) return;

                pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
                    return pdf.getPage(1);
                }).then(page => {
                    const scale = 0.5;
                    const viewport = page.getViewport({
                        scale: scale
                    });
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                }).catch(error => {
                    console.error('Error al renderizar el PDF:', error);
                });
            });
        };

        // Llamada inicial
        renderPDFThumbnails();

        $(document).on('keydown', function(e) {
            // Ctrl + Shift + F
            if (e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'f') {
                e.preventDefault();
                $('#bulkRenameForm').submit();
            }
        });

        // Captura click derecho en cualquier item
        $(document).on('contextmenu', '.folder-file', function(e) {
            e.preventDefault();
            // Cerrar otros context menus abiertos
            $('.dropdown-menu.show')
                .removeClass('show')
                .css({
                    display: ''
                });

            // Mostrar el menú de este item en la posición del mouse
            var $menu = $(this).find('.dropdown-menu');
            $menu
                .css({
                    position: 'fixed', // relativo a viewport
                    top: e.clientY + 'px',
                    left: e.clientX + 'px',
                    display: 'block'
                })
                .addClass('show');
        });

        
        // *** Control de clic fuera del dropdown (mejora) ***
        $(document).on('click', function(e) {
            // Si el clic NO ocurre dentro de un dropdown-menu, ni en su botón (.btn-drop),
            // ni sobre el elemento .folder-file, ni en un .file-item (para no interferir
            // con la expansión de archivos), entonces cerramos los menús abiertos:
            if (!$(e.target).closest('.dropdown-menu, .btn-drop, .folder-file, .file-item').length) {
                $('.dropdown-menu.show').removeClass('show').css({ display: '' });
            }
        });

    });
</script>

<?php
if ($_SESSION['level'] == '0') {
    require_once "view/pages/modals.php";
}
?>
