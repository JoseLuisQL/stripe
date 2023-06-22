<script>
  $(document).ready(function(){

    

    // Muestra un mensaje emergente con la librería Swal (SweetAlert2)
    Swal.fire({
        title: "DKB Premiun Autocheckout", // Título del mensaje emergente
        text: 'Click here to join my telegram channel', // Texto del mensaje emergente
        icon: 'success', // Icono del mensaje emergente (tipo 'success')
        showCancelButton: true, // Muestra un botón para cancelar
        confirmButtonClass: 'btn btn-success', // Clase del botón de confirmación
        cancelButtonClass: 'btn btn-link', // Clase del botón de cancelación
        confirmButtonText: 'Click here to join my channel', // Texto del botón de confirmación
        cancelButtonText: 'Already joined', // Texto del botón de cancelación
        buttonsStyling: false, // Desactiva el estilo predeterminado de los botones
        reverseButtons: true, // Invierte el orden de los botones
        allowOutsideClick: false, // No permite cerrar el mensaje haciendo clic fuera de él
        // Función que se ejecuta antes de confirmar
        preConfirm: () => {
            // Cambia la ubicación de la ventana al enlace del canal de Telegram
            window.location.href = 'https://t.me/yourtelegramchannel';
        }
    }).then((result) => {
        // Si el resultado es la cancelación del mensaje
        if (result.dismiss === Swal.DismissReason.cancel) {
            // Muestra otro mensaje emergente
            Swal.fire({
                title: "Enjoy ❤️", // Título del mensaje emergente
                text: "Message @skyeisnotdead on telegram if problem happens", // Texto del mensaje emergente
                icon: "success", // Icono del mensaje emergente (tipo 'success')
                confirmButtonClass: "btn btn-primary", // Clase del botón de confirmación
                buttonsStyling: false, // Desactiva el estilo predeterminado de los botones
            });
        }
    });
  
  
    // Cuando se hace clic en el elemento con la clase 'show-charge'
    $('.show-charge').click(function(){
        // Obtén el tipo de atributo 'type'
        var type = $('.show-charge').attr('type');
        // Alterna la visibilidad del elemento con id 'cards_charge'
        $('#cards_charge').slideToggle();
        // Si el tipo es 'show'
        if(type == 'show'){
            // Cambia el contenido del elemento con clase 'show-charge' a un ícono de ojo
            $('.show-charge').html('<i class="fa fa-eye"></i>');
            // Establece el atributo 'type' en 'hidden'
            $('.show-charge').attr('type', 'hidden');
        }else{
            // Cambia el contenido del elemento con clase 'show-charge' a un ícono de ojo tachado
            $('.show-charge').html('<i class="fa fa-eye-slash"></i>');
            // Establece el atributo 'type' en 'show'
            $('.show-charge').attr('type', 'show');
        }
    });
    
    // Lo mismo que en el bloque anterior, pero para el elemento con clase 'show-lives'
    $('.show-lives').click(function(){
        var type = $('.show-lives').attr('type');
        $('#cards_aprovadas').slideToggle();
        if(type == 'show'){
            $('.show-lives').html('<i class="fa fa-eye"></i>');
            $('.show-lives').attr('type', 'hidden');
        }else{
            $('.show-lives').html('<i class="fa fa-eye-slash"></i>');
            $('.show-lives').attr('type', 'show');
        }
    });
    
    // Lo mismo que en el bloque anterior, pero para el elemento con clase 'show-dies'
    $('.show-dies').click(function(){
        var type = $('.show-dies').attr('type');
        $('#cards_reprovadas').slideToggle();
        if(type == 'show'){
            $('.show-dies').html('<i class="fa fa-eye"></i>');
            $('.show-dies').attr('type', 'hidden');
        }else{
            $('.show-dies').html('<i class="fa fa-eye-slash"></i>');
            $('.show-dies').attr('type', 'show');
        }
    });
    
    // Cuando se hace clic en el elemento con la clase 'btn-trash'
    $('.btn-trash').click(function(){
        // Muestra un mensaje emergente indicando el éxito en la eliminación de las tarjetas muertas
        Swal.fire({
            title: 'REMOVE CC DEAD SUCCESS',
            icon: 'success',
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timer: 3000
        });
        // Vacía el contenido del elemento con id 'cards_reprovadas'
        $('#cards_reprovadas').text('');
    });
    
    // Cuando se hace clic en el elemento con la clase 'btn-copy1'
    $('.btn-copy1').click(function(){
        // Muestra un mensaje emergente indicando el éxito en la copia de tarjetas cargadas
        Swal.fire({
            title: 'COPY CC CHARGED SUCCESS',
            icon: 'success',
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timer: 3000
        });
        // Copia el contenido del elemento con id 'cards_charge' al portapapeles
        var cards_charge = document.getElementById('cards_charge').innerText;
        var textarea = document.createElement("textarea");
        textarea.value = cards_charge;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    });
    
    // Lo mismo que en el bloque anterior, pero para el elemento con clase 'btn-copy'
    $('.btn-copy').click(function(){
        Swal.fire({
            title: 'COPY CC LIVE SUCCESS',
            icon: 'success',
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timer: 3000
        });
        // Copia el contenido del elemento con id 'cards_aprovadas' al portapapeles
        var cards_lives = document.getElementById('cards_aprovadas').innerText;
        var textarea = document.createElement("textarea");
        textarea.value = cards_lives;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    });
    

    // Cuando se hace clic en el elemento con la clase 'btn-play'
    $('.btn-play').click(function(){
        // Obtén los valores de los campos de entrada y elimina los espacios en blanco
        var cards = $('.form-checker').val().trim();
        var array = cards.split('\n');
        var pklive = $("#pklive").val().trim();
        var cslive = $("#cslive").val().trim();
        var xamount = $("#xamount").val().trim();
        var xemail = $("#xemail").val().trim();
        var xurl = $("#xurl").val().trim();
        var charge = 0, lives = 0, dies = 0, testadas = 0, txt = '';
    
        // Comprueba si los campos de entrada están vacíos y muestra mensajes de error si lo están
        if(!cards){
            Swal.fire({
                title: 'Wheres your card?? please add a card!!',
                icon: 'error',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        if(!pklive){
            Swal.fire({
                title: 'Wheres your pklive?? please add a pklive!!',
                icon: 'error',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        if(!cslive){
            Swal.fire({
                title: 'Wheres your cslive?? please add a cslive!!',
                icon: 'error',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        if(!xamount){
            Swal.fire({
                title: 'Wheres the amount?? please add a amount!!',
                icon: 'error',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        if(!xemail){
            Swal.fire({
                title: 'Wheres the email?? please add a email!!',
                icon: 'error',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        // Muestra un mensaje de espera mientras se procesa la tarjeta
        Swal.fire({
            title: 'Please wait for the card to be processed !!',
            icon: 'success',
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timer: 3000
        });
    
        // Filtra las líneas que no están vacías y las guarda en un array
        var line = array.filter(function(value){
            if(value.trim() !== ""){
                txt += value.trim() + '\n';
                return value.trim();
            }
        });
    
        var total = line.length;
    
        // Establece el valor del área de texto de las tarjetas con el contenido filtrado
        $('.form-checker').val(txt.trim());
    
        // Si el total de tarjetas es mayor a 100, muestra un mensaje de advertencia y detiene la ejecución
        if(total > 100){
            Swal.fire({
                title: 'CHECK ONLY 100 CC',
                icon: 'warning',
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                timer: 3000
            });
            return false;
        }
    
        // Establece los contadores de tarjetas
        $('.carregadas').text(total);
        $('.btn-play').attr('disabled', true);
        $('.btn-stop').attr('disabled', true);
    
        // Procesa cada tarjeta del array con un intervalo de tiempo entre cada una
        line.every(function(data, index){
            setTimeout( function() {
                var callBack = $.ajax({
                    url: 'chk.php?cards=' + data + '&cslive=' + cslive + '&pklive=' + pklive + '&xamount=' + xamount + '&xemail=' + xemail + '&xurl=' + xurl + '&referrer=Auth',
                    success: function(retorno){
                        // Si la tarjeta se carga correctamente, actualiza los contadores y muestra un mensaje
                        if(retorno.indexOf("#CHARGED") >= 0){
                            Swal.fire({
                                title: '+1 CHARGED CC',
                                icon: 'success',
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timer: 3000
                            });
                            $('#cards_charge').append(retorno);
                            removelinha();
                            charge = charge +1;
                        } else if(retorno.indexOf("#LIVE") >= 0){
                            Swal.fire({
                                title: '+1 LIVE CC',
                                icon: 'success',
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timer: 3000
                            });
                            $('#cards_aprovadas').append(retorno);
                            removelinha();
                            lives = lives +1;
                        }else{
                            $('#cards_reprovadas').append(retorno);
                            removelinha();
                            dies = dies +1;
                        }
    
                        // Actualiza los contadores de tarjetas
                        testadas = charge + lives + dies;
                        $('.charge').text(charge);
                        $('.aprovadas').text(lives);
                        $('.reprovadas').text(dies);
                        $('.testadas').text(testadas);
    
                        // Si todas las tarjetas han sido procesadas, muestra un mensaje final
                        if(testadas == total){
                            Swal.fire({
                                title: 'HAVE BEEN DISPOSED',
                                icon: 'success',
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timer: 3000
                            });
                            $('.btn-play').attr('disabled', false);
                            $('.btn-stop').attr('disabled', false);
                        }
                    }
                });
            }, 15000 * index);
            return true;
        });
    });
    

    // Función para eliminar la primera línea del área de texto con la clase 'form-checker'
    function removelinha() {
        // Divide el contenido del área de texto en un array de líneas
        var lines = $('.form-checker').val().split('\n');
        
        // Elimina la primera línea del array
        lines.splice(0, 1);
        
        // Une el array de líneas en un solo string y actualiza el valor del área de texto
        $('.form-checker').val(lines.join("\n"));
    }
    
  });
</script>
