$(document).on('click', '#newUser', function() {

    var userName = $("input[name='userName']").val();
    var email = $("input[name='email']").val();
    var password = $("input[name='password']").val();
    var level = $("select[name='level']").val();
    
    $.ajax({
        type: "POST",
        url: "controller/ajax/ajax.form.php",
        data: {
            function: 2,
            userName: userName,
            email: email,
            password: password,
            level: level,
        },
        success: function (response) {
            $("input[name='userName']").val('');
            $("input[name='email']").val('');
            $("input[name='password']").val('');
            $("select[name='level']").val('Seleccione el nivel');
            $('.message').html('<div class="alert alert-success" role="alert">Usuario ' + userName + ' creado excitosamente</div>');
            
            // Borra el mensaje después de 5 segundos
            setTimeout(function() {
                $('.message').empty();
            }, 5000);
        },
        error: function (error) {
            console.log("Error en la solicitud Ajax:", error);
        }
    });

});
