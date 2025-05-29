$(document).ready(function() {
    function fetchFolders() {
        $.ajax({
            type: 'POST',
            url: 'controller/ajax/getFolders.php',
            dataType: 'json',
            success: function(response) {
                renderFolders(response);
            },
            error: function(xhr, status, error) {
                console.error('Error al realizar la solicitud:', error);
            }
        });
    }

    function renderFolders(folders) {
        const folderContainer = $('.folders .file-list');
        folderContainer.empty(); // Limpia cualquier contenido anterior

        folders.forEach(function(folder) {
            const fileCount = folder.files.length;
            const totalSize = folder.files.reduce((sum, file) => sum + parseInt(file.size, 10), 0); // Asumiendo que el tamaño de los archivos se proporciona en bytes

            const folderItem = `
                <div class="file-item row" ondblclick="openFolder(${folder.id})">
                    <div class="row col-11">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-folder file-icon text-primary"></i>
                        </div>
                        <div class="file-name-content">
                            <span class="file-name">${folder.nombre}</span>
                        </div>
                    </div>
                    <div class="dropdown dropdown-unimo col-1">
                        <button class="btn-drop dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-duotone fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#" onclick="openFolder(${folder.id})">Abrir</a></li>
                            <li><a class="dropdown-item" href="#">Renombrar</a></li>
                            <li><a class="dropdown-item" href="#">Copiar link</a></li>
                            <li><a class="dropdown-item" href="#">Mover a</a></li>
                            <li><a class="dropdown-item mb-2" href="#">Borrar</a></li>
                            <ul class="file-data">
                                <li class="file-size"><i class="fa-duotone fa-files"></i> ${fileCount} archivos, ${formatSize(totalSize)}</li>
                                <li class="file-date"><i class="fa-duotone fa-calendar-days"></i> ${folder.fecha_create}</li>
                            </ul>
                        </ul>
                    </div>
                </div>
            `;
            folderContainer.append(folderItem);
        });
    }

    function formatSize(bytes) {
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes === 0) return '0 Bytes';
        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
        return `${(bytes / (1024 ** i)).toFixed(2)} ${sizes[i]}`;
    }

    fetchFolders(); // Llama a la función para recuperar y mostrar las carpetas
});

function openFolder(folder) {
    // Aquí podría abrir la carpeta en el navegador
    console.log(`Abriendo carpeta con ID ${folder}`);
}
