$(document).ready(function() {
    
    var renameModal = new bootstrap.Modal($('#renameModal')[0]);
    var moveModal = new bootstrap.Modal($('#moveModal')[0]);

    // Abrir modal de renombrar
    $('.rename-button').on('click', function () {
        var oldName = $(this).data('oldname');
        var filename = $(this).data('filename');
        var dataType = $(this).data('type');

        $('#modalOldName').val(oldName);
        $('#modalNewName').val(filename);
        $('.presentName').html(filename);

        // Generar el dropdown según el tipo
        var dropdownHtml = '';
        if (dataType === 'folder') {
            dropdownHtml = '<select id="renameDropdown" class="form-select mb-3">' +
                        '<option value="">Selecciona unidad</option>';
            for (var i = 1; i <= 10; i++) {
                dropdownHtml += '<option value="Unidad ' + i + '">Unidad ' + i + '</option>';
            }
            dropdownHtml += '</select>';
        } else if (dataType === 'document') {
            dropdownHtml = '<select id="renameDropdown" class="form-select mb-3">' +
                        '<option value="">Selecciona tipo</option>' +
                        '<option value="Video">Video</option>' +
                        '<option value="Presentacion">Presentacion</option>' +
                        '<option value="Texto">Texto</option>' +
                        '</select>';
        }

        // Insertar el dropdown en el contenedor del modal
        $('#dropdownContainer').html(dropdownHtml);

        // Manejar el evento de cambio en el dropdown
        $('#renameDropdown').on('change', function () {
            var selected = $(this).val();
            if (dataType === 'document') {
                // Conservar la extensión original
                var extension = '';
                var dotIndex = filename.lastIndexOf('.');
                if (dotIndex !== -1) {
                    extension = filename.substring(dotIndex);
                }
                $('#modalNewName').val(selected + extension);
            } else {
                $('#modalNewName').val(selected);
            }
        });

        // Eliminar cualquier backdrop existente
        $('.modal-backdrop').remove();

        renameModal.show();
        $('#renameModal').on('shown.bs.modal', function () {
            $('#modalNewName').trigger('focus').select();
        });
    });

    // Eliminar archivo/carpeta
    $('.delete-button-folder').on('click', function () {
        var form = $(this).closest('.file-item').find('.delete-form')[0];
        if (confirm('¿Estás seguro de que deseas eliminar esta carpeta y su contenido?')) {
            form.submit();
        }
    });
    // Eliminar archivo/carpeta
    $('.delete-button').on('click', function () {
        var form = $(this).closest('.file-item').find('.delete-form')[0];
        if (confirm('¿Estás seguro de que deseas eliminar este elemento?')) {
            form.submit();
        }
    });

    // Copiar enlace de descarga
    $('.copy-link-button').on('click', function () {
        var link = $(this).data('download-url');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(link).then(function() {
                showAlert("URL de descarga copiada: " + link);
            }, function(err) {
                console.error('Error al copiar la URL: ', err);
            });
        } else {
            // Método de reserva para navegadores antiguos
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(link).select();
            document.execCommand('copy');
            tempInput.remove();
            showAlert("URL de descarga copiada: " + link);
        }
    });

    // Abrir carpeta
    function openFolder(url) {
        window.location.href = "?dir=" + url;
    }

    // Función para crear opciones anidadas con indentación
    function createFolderOptions(folders, prefix = '', level = 0) {
        var options = [];
        var indent = '&nbsp;'.repeat(level * 4); // 4 spaces per level of indentation

        for (var folder in folders) {
            if (folders.hasOwnProperty(folder)) {
                var folderPath = prefix + folder;
                options.push($('<option>', {
                    value: folderPath,
                    html: indent + folder // use html instead of text to include the indent
                }));
                var subfolders = folders[folder];
                if (Object.keys(subfolders).length > 0) {
                    var suboptions = createFolderOptions(subfolders, folderPath + '/', level + 1);
                    options = options.concat(suboptions);
                }
            }
        }
        return options;
    }

    // Abrir modal de mover y cargar carpetas
    $('.move-button').on('click', function () {
        var fullPath = $(this).data('fullpath');
        $('#modalMovePath').val(fullPath);

        $('#folderTree').jstree("destroy").empty();

        async function loadFolders() {
            try {
                const response = await $.ajax({
                    url: 'controller/get_folders.php',
                    type: 'GET',
                    dataType: 'json'
                });

                $('#folderTree').jstree({
                    'core': {
                        'data': response
                    }
                });

            } catch (error) {
                console.error('Error al obtener las carpetas:', error);
            }
        }

        loadFolders();

        $('#moveModal').on('shown.bs.modal', function () {
            $('#folderTree').jstree('open_all');
        });

        $('#moveModal').modal('show');
    });

    // Enviar formulario de mover archivo/carpeta
    $('#moveForm').on('submit', function (e) {
        e.preventDefault();
        var movePath = $('#modalMovePath').val();
        var newLocation = $('#folderTree').jstree('get_selected')[0];
        var replaceFiles = $('#replaceFiles').is(':checked');

        async function moveFile() {
            try {
                const response = await $.ajax({
                    url: 'controller/ajax/move_file.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        movePath: movePath,
                        newLocation: newLocation,
                        replaceFiles: replaceFiles
                    }
                });
                showAlert(response.message);
                $('#moveModal').modal('hide');
                location.reload();
            } catch (error) {
                showAlert('Error al mover el archivo/carpeta');
            }
        }

        moveFile();
    });

    // Manejar carga de archivos
    var uploadFileForm = $('.upload-file-form');
    if (uploadFileForm.length) {
        uploadFileForm.on('submit', function(event) {
            event.preventDefault();
            var form = event.target;
            var formData = new FormData(form);
            var progressWrapper = $('#progressWrapper');
            var progressBar = $('#progressBar');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    progressBar.css('width', percentComplete + '%');
                    progressBar.text(Math.round(percentComplete) + '%');
                }
            });

            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    showAlert('Archivos subidos con éxito');
                    progressBar.css('width', '0');
                    progressWrapper.hide();
                    location.reload();
                } else {
                    showAlert('Error al subir los archivos');
                }
            });

            xhr.addEventListener('error', function() {
                showAlert('Error al subir los archivos');
            });

            progressWrapper.show();
            xhr.send(formData);
        });
    }

    // Manejar selección de archivos
    $('.file-checkbox').on('change', function() {
        if ($('.file-checkbox:checked').length > 0) {
            $('#moveSelectedFilesButton').show();
            $('#deleteSelectedFilesButton').show();
        } else {
            $('#moveSelectedFilesButton').hide();
            $('#deleteSelectedFilesButton').hide();
        }
    });

    $('#moveSelectedFilesButton').on('click', function() {
        var selectedFiles = [];
        $('.file-checkbox:checked').each(function() {
            selectedFiles.push($(this).data('path'));
        });

        $('#modalMovePath').val(JSON.stringify(selectedFiles));

        $('#folderTree').jstree("destroy").empty();

        async function loadFolders() {
            try {
                const response = await $.ajax({
                    url: 'controller/get_folders.php',
                    type: 'GET',
                    dataType: 'json'
                });

                $('#folderTree').jstree({
                    'core': {
                        'data': response
                    }
                });

            } catch (error) {
                console.error('Error al obtener las carpetas:', error);
            }
        }

        loadFolders();

        $('#moveModal').on('shown.bs.modal', function () {
            $('#folderTree').jstree('open_all');
        });

        $('#moveModal').modal('show');
    });

    $('#deleteSelectedFilesButton').on('click', function() {
        if (confirm('¿Estás seguro de que deseas eliminar los archivos seleccionados?')) {
            var selectedFiles = [];
            $('.file-checkbox:checked').each(function() {
                selectedFiles.push($(this).data('path'));
            });

            $.ajax({
                url: 'controller/delete_selected_files.php',
                type: 'POST',
                dataType: 'json',
                data: { files: selectedFiles },
                success: function(response) {
                    showAlert(response.message);
                    location.reload();
                },
                error: function() {
                    showAlert('Error al eliminar los archivos seleccionados');
                }
            });
        }
    });

});


function showAlert(message) {
    var alertDiv = $('<div></div>', {
        class: 'floating-notification',
        html: `<div class="icon"></div><div class="text">${message}</div>`
    });

    $('body').append(alertDiv);

    // Agregar clase para animación de entrada
    setTimeout(function() {
        alertDiv.addClass('show');
    }, 100);

    // Remover la notificación después de 3 segundos
    setTimeout(function() {
        // Añadir la clase para la animación de salida
        alertDiv.removeClass('show').addClass('hide');

        // Esperar a que la transición de salida termine antes de remover del DOM
        setTimeout(function() {
            alertDiv.remove();
        }, 400); // Espera 400 ms para la animación de salida
    }, 3000);
}
