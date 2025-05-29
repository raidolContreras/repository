
<script>
function openFolder(encodedPath) {
    // Decodifica la URL
    var folderPath = decodeURIComponent(encodedPath);
    // Redirige a la carpeta
    window.location.href = '?dir=' + folderPath;
}

$(document).ready(function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.getElementById('navbarNav');
    navbarToggler.addEventListener('click', function() {
        navbarToggler.classList.toggle('active');
        navbarCollapse.classList.toggle('show');
    });

    // Cerrar el menú al hacer clic en un enlace del menú
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            navbarToggler.classList.remove('active');
            navbarCollapse.classList.remove('show');
        });
    });
    
    document.querySelector('.navbar-collapse').addEventListener('click', function() {
      this.classList.toggle('active');
    });

});

function logout() {
    $.ajax({
        type: 'POST',
        url: 'controller/ajax/logout.php',
        success: function(response) {
            if (response === 'ok') {
                window.location.href = 'login';
            } else {
                alert('Error al intentar cerrar sesión. Inténtalo de nuevo más tarde.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cerrar sesión:', error);
            alert('Hubo un error al intentar cerrar sesión. Inténtalo de nuevo más tarde.');
        }
    });
}
$(document).ready(function() {
    $('a.disabled').on('click', function(event) {
        event.preventDefault(); // Evita que el enlace navegue a otra página
        return false; // Detiene la ejecución del evento
    });
    
    $('.addFolder').on('click', function(event) {
        event.preventDefault(); // Evita que el enlace navegue a otra página
        $('#createFolderModal').modal('show');
    });

    
    $('#saveFolderButton').on('click',function () {
        const folder = $('#folderName').val();
        const idFolder = $('#idFolder').val();
        if (folder == '') {
            alert('Ingrese un nombre para la carpeta.');
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: 'controller/ajax/add_folder.php',
                data: { folder: folder, idFolder: idFolder },
                success: function(response) {
                    if (response === 'ok') {
                        alert('Carpeta agregada exitosamente.');
                        location.reload();
                    } else {
                        alert('Error al agregar la carpeta. Inténtalo de nuevo más tarde.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al agregar carpeta:', error);
                    alert('Hubo un error al agregar la carpeta. Inténtalo de nuevo más tarde.');
                }
            });
        }
    });
});

function openModal(idModal) {
    
}

</script>
    
<script src="view/assets/js/bootstrap.bundle.min.js"></script>

<script>
    
    function closeMenu() {
        document.querySelector('.navbar-collapse').classList.remove('show');
        document.querySelector('.navbar-toggler').classList.remove('active');
    }

</script>
