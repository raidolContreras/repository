<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Crear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para crear usuarios -->
                <form id="createUserForm">
                    <div class="form-group">
                        <label for="name">Nombre:</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Apellidos:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico:</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="role">Rol:</label>
                        <select class="form-control" id="role" name="role">
                            <option value="1">Usuario General</option>
                            <option value="0">Administrador</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveUserBtn">Guardar Usuario</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#saveUserBtn').on('click', function(e) {
            e.preventDefault();
            let $btn = $(this);

            // 1. Construir FormData a partir del <form>
            let formData = new FormData($('#createUserForm')[0]);
            formData.append('action', 'create_user');

            // 2. Opcional: deshabilitar botón y mostrar spinner
            $btn.prop('disabled', true).text('Guardando…');

            $.ajax({
                    url: 'controller/ajax/users.php',
                    method: 'POST',
                    data: formData,
                    processData: false, // IMPORTANTE para FormData
                    contentType: false, // IMPORTANTE para FormData
                    dataType: 'json', // esperamos JSON
                })
                .done(function(res) {
                    if (res.success) {
                        // cerrar modal, refrescar tabla, etc.
                        $('#createUserModal').modal('hide');
                        // Ejemplo: recargar DataTable
                        if (window.usersTable) {
                            window.usersTable.ajax.reload();
                        }
                        alert('Usuario creado correctamente');
                    } else {
                        // mostrar errores de validación enviados por PHP
                        alert('Error: ' + (res.error || 'Error desconocido'));
                    }
                })
                .fail(function(xhr, status, err) {
                    console.error(err);
                    alert('Ocurrió un error en el servidor. Revisa la consola.');
                })
                .always(function() {
                    // restaurar botón
                    $btn.prop('disabled', false).text('Guardar Usuario');
                });
        });
    });
</script>

<div class="modal fade" id="folderPermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="folderPermissionsForm">
                <div class="modal-header">
                    <h5 class="modal-title">Permisos de carpetas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="foldersTable">
                            <thead>
                                <tr>
                                    <th>Carpeta</th>
                                    <th class="text-center">Visible</th>
                                </tr>
                            </thead>
                            <tbody class="folders-body">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inicializar DataTable para carpetas
        $('.folders-body').empty(); // Limpiar contenido existente en la tabla

        // Obtener la referencia a la tabla
        let $tableBody = $('.folders-body');

        // Realizar una petición AJAX para obtener los datos de las carpetas
        $.ajax({
            url: 'controller/ajax/folders.php',
            method: 'POST',
            data: {
                action: 'get_folders'
            },
            dataType: 'json'
        })
        .done(function(res) {
            if (res && res.length > 0) {
                // Iterar sobre los datos y construir las filas de la tabla
                res.forEach(function(folder) {
                    let $row = $('<tr>');
                    $row.append(`<td>${folder.name}</td>`);
                    $row.append(`<td class="text-center"><input type="checkbox" class="form-check-input" name="folder_${folder.id}" ${folder.visible ? 'checked' : ''}></td>`);
                    $tableBody.append($row);
                });
            } else {
                $tableBody.append('<tr><td colspan="2">No se encontraron carpetas.</td></tr>');
            }
        })
        .fail(function(xhr, status, err) {
            console.error(err);
            $tableBody.append('<tr><td colspan="2">Ocurrió un error al cargar las carpetas. Revisa la consola.</td></tr>');
        });

        // Manejar el envío del formulario
        $('#folderPermissionsForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serializeArray();
            formData.push({
                name: 'action',
                value: 'update_permissions'
            });

            $.ajax({
                    url: 'controller/ajax/folders.php',
                    method: 'POST',
                    data: formData,
                    dataType: 'json'
                })
                .done(function(res) {
                    if (res.success) {
                        alert('Permisos actualizados correctamente');
                        $('#folderPermissionsModal').modal('hide');
                    } else {
                        alert('Error al actualizar permisos: ' + res.error);
                    }
                })
                .fail(function(xhr, status, err) {
                    console.error(err);
                    alert('Ocurrió un error al guardar los permisos. Revisa la consola.');
                });
        });
    });
</script>