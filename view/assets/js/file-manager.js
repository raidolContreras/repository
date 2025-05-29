$(function(){

  // helper: ejecutar acción y refrescar lista
  function callAPI(data, onSuccess) {
    $.ajax({
      url: 'api/file-manager.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      contentType: false,
      processData: false,
    }).done(function(res){
      showAlert(res.message, res.success ? 'success' : 'danger');
      if(res.success && typeof onSuccess==='function') onSuccess();
    }).fail(function(){
      showAlert('Error de comunicación', 'danger');
    });
  }

  // Renombrar
  $('#renameForm').submit(function(e){
    e.preventDefault();
    const oldName = $('#modalOldName').val();
    const newName = $('#modalNewName').val();
    
    const fd = new FormData();
    fd.append('action','rename');
    fd.append('oldName', decodeURIComponent(oldName));
    fd.append('newName', newName);

    callAPI(fd, function(){
      // Recargar lista de archivos
      location.reload();
    });
  });

  // Crear carpeta
  $('.new-folder-form').submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append('action','mkdir');
    callAPI(fd, ()=>location.reload());
  });

  // Subir archivos con progreso
  $('.upload-file-form').submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append('action','upload');
    $.ajax({
      url: '/api/file-manager.php',
      type: 'POST',
      data: fd,
      contentType: false,
      processData: false,
      xhr: function(){
        const xhr = $.ajaxSettings.xhr();
        xhr.upload.onprogress = function(e){
          if(e.lengthComputable){
            let p = Math.round(e.loaded/e.total*100);
            $('#progressWrapper').show();
            $('#progressBar').css('width',p+'%').text(p+'%');
          }
        };
        return xhr;
      }
    }).done(function(res){
      showAlert(res.message, res.success?'success':'danger');
      if(res.success) location.reload();
    }).fail(function(){
      showAlert('Error de subida','danger');
    });
  });

  // Borrar (delegado)
  $(document).on('click','.delete-button, .delete-button-folder',function(){
    const path = $(this).closest('.file-item, .file-item-folder')
                          .find('input[name="deletePath"], input[name="deletePath"]').val();
    const fd = new FormData();
    fd.append('action','delete');
    fd.append('deletePath', decodeURIComponent(path));
    callAPI(fd, ()=>location.reload());
  });

  // Descomprimir
  $(document).on('submit','.unzip-form',function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append('action','unzip');
    callAPI(fd, ()=>location.reload());
  });

  // Renombrado masivo (Ctrl+Shift+F)
  $('#bulkRenameForm').submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append('action','bulkRename');
    callAPI(fd, ()=>location.reload());
  });

  // Normalizar carpetas (Ctrl+Shift+U)
  $('#bulkNormalizeDirsForm').submit(function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fd.append('action','bulkNormalizeDirs');
    callAPI(fd, ()=>location.reload());
  });

  // ShowAlert genérica
  function showAlert(msg, type='info'){
    const div = $(`
      <div class="floating-notification alert alert-${type}">
        ${msg}
      </div>`).appendTo('body');
    setTimeout(()=>div.addClass('show'),50);
    setTimeout(()=>div.removeClass('show').addClass('hide'),3000);
    setTimeout(()=>div.remove(),3500);
  }

});
