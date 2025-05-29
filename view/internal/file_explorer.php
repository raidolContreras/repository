<?php
// views/internal/file_explorer.php
require_once __DIR__ . '/../../controllers/FileExplorerController.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Explorador de Archivos</title>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css">
    <style>
      /* Mueve aquí tu CSS de .floating-notification, .pdf-container, etc. */
    </style>
</head>
<body>
  <div class="container-unimo mt-5">
    <h2>Explorador de Archivos</h2>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <?php echo createBreadcrumb($controller->directory, $controller->baseDir); ?>
      </ol>
    </nav>

    <!-- Formularios de crear carpeta, subir archivos, renombrado masivo -->
    <div class="row mb-3">
      <div class="col">
        <form method="POST">
          <input type="hidden" name="currentDir"
            value="<?php echo htmlspecialchars($controller->directory); ?>">
          <div class="input-group">
            <span class="input-group-text">Nueva Carpeta</span>
            <input type="text" name="newFolder" class="form-control"
              placeholder="Nombre carpeta" required>
            <button class="btn btn-primary">Crear</button>
          </div>
        </form>
      </div>
      <div class="col">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="currentDir"
            value="<?php echo htmlspecialchars($controller->directory); ?>">
          <div class="input-group">
            <input type="file" name="uploadFiles[]" class="form-control"
              multiple required>
            <button class="btn btn-primary">Subir</button>
          </div>
        </form>
      </div>
      <div class="col">
        <form id="bulkRenameForm" method="POST">
          <input type="hidden" name="currentDir"
            value="<?php echo htmlspecialchars($controller->directory); ?>">
          <button name="bulkRename" value="1"
            class="btn btn-warning w-100">Normalizar archivos</button>
        </form>
      </div>
      <div class="col">
        <form id="bulkNormalizeDirsForm" method="POST">
          <input type="hidden" name="currentDir"
            value="<?php echo htmlspecialchars($controller->directory); ?>">
          <button name="bulkNormalizeDirs" value="1"
            class="btn btn-info w-100">Normalizar “Unidad”</button>
        </form>
      </div>
    </div>

    <?php listFiles($controller->directory); ?>

    <!-- Modales de renombrar y mover (idénticos a tu versión) -->

  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"
    defer></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"
    defer></script>
  <script src="/assets/js/fileExplorer.js" defer></script>
</body>
</html>
