
$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') 	
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();
            // validar si el elmento de foto actual y remove existen entonces al 
            // input que es foto remove se le va a poner img_producto.png
        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val('img_producto.png');
        }

    });

    // Activa campos para registrar cliente
    $('.btn_new_cliente').click(function(e){
        // preventDefault previene que se recargue la pagina
        e.preventDefault();
        $('#nombre_cli').removeAttr('disabled');
        $('#apellido_cli').removeAttr('disabled');
        $('#direccion_cli').removeAttr('disabled');
        $('#email_cli').removeAttr('disabled');
        $('#telefono_cli').removeAttr('disabled');
        
        $('#registro_cliente').slideDown();
    });

    // buscar cliente
    // keyup evento de presionar la tecla
        $ ('#cedula_cli').keyup(function(e){
            e.preventDefault();
            // el cliente va a ser igual a this por medio de la propiedad val va a extraer el valor de this
            var cl = $(this).val();
            var action= 'searchCliente';
            // archivo ajax

            $.ajax({
                url:'ajax.php',
                type: "POST",
                async: true,
                // va a enviar lo que hay en la data
                data:{action:action, clientes:cl},
                
                success: function(response){
                    // console.log(response);

                    // si la respuesta es un 0 limpia los campos 
                    if(response == 0){
                         $('#id_cliente').val('');
                       $('#nombre_cli').val('');
                        $('#apellido_cli').val('');
                        $('#direccion_cli').val('');
                        $('#email_cli').val('');
                        $('#telefono_cli').val('');
                        // mostrar boton agregar
                        $('#new_cliente').slideDown();
                    }else{
                        // parsear la respuesta para convertirlo en un objeto
                        var data = $.parseJSON(response);
                        $('#id_cliente').val(data.id_cliente);
                       $('#nombre_cli').val(data.nombre);
                        $('#apellido_cli').val(data.apellido);
                        $('#direccion_cli').val(data.direccion);
                        $('#email_cli').val(data.email);
                        $('#telefono_cli').val(data.telefono);
                        // oculta boton agregar
                         $('#new_cliente').slideUp();
                        // bloqueo de campos
                        $('#nombre_cli').attr('disabled','disabled');
                        $('#apellido_cli').attr('disabled','disabled');
                        $('#direccion_cli').attr('disabled','disabled');
                        $('#email_cli').attr('disabled','disabled');
                        $('#telefono_cli').attr('disabled','disabled');
                        // oculta boton guardar
                        $('#registro_cliente').slideUp();
                    }

                },
                error: function(error){
                    
                }

            });
        
 });
        // crear cliente
        $('#form_new_cliente_venta').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url:'ajax.php',
                type: "POST",
                async: true,
                // va a enviar lo que hay en la data
                data:$('#form_new_cliente_venta').serialize(),
                
                success: function(response){
                    if (response != 'error') {
                        // Agregar id a input hidden
                        $('#id_cliente').val(response);
                        $('#nombre_cli').attr('disabled','disabled');
                        $('#apellido_cli').attr('disabled','disabled');
                        $('#direccion_cli').attr('disabled','disabled');
                        $('#email_cli').attr('disabled','disabled');
                        $('#telefono_cli').attr('disabled','disabled');

                        // oculta boton agregar
                        $('new_cliente').slideUp();
                        $('#registro_cliente').slideUp();

                    }

                   
                },
                error: function(error){
                    
                }
            });
        });
        // Buscar producto
        $('#txt_id_producto').keyup(function(e) {
            e.preventDefault();
            var id_producto = $(this).val();
            var action = 'infoProducto';
            if (id_producto != '') {
                  $.ajax({
                url:'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, id_producto:id_producto},
                // va a enviar lo que hay en la data
                success: function(response){
                    // console.log(response);
                  if (response != 'error') {
                    var info = $.parseJSON(response);
                    // $('#txt_desc_producto').val('0');
                    $('#txt_nombre_prod').html(info.nombre);
                    $('#txt_existencia').html(info.stock);
                    $('#txt_cant_producto').val('1');
                    $('#txt_precio_producto').val('1');
                    $('#txt_precio').html(info.precio);
                    $('#txt_precio_total').html(info.precio);
                    // activar cantidad y descuento
                     $('#txt_cant_producto').removeAttr('disabled');
                     $('#txt_precio_producto').removeAttr('disabled');
                     // $('#txt_desc_producto').removeAttr('disabled');
                     // mostrar boton agregar
                     var existencia=parseInt($('#txt_existencia').html());
                     if ($('#txt_cant_producto').val()>existencia) {
                        $('#agregar_producto').slideUp();
                        }else{
                     $('#agregar_producto').slideDown();
                        }
                    
                    }else{
                    // $('#txt_desc_producto').val('0');
                    $('#txt_nombre_prod').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');
                    // bloquear cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');
                    // $('#txt_desc_producto').attr('disabled','disabled');
                    // ocultar boton agregar
                    $('#agregar_producto').slideUp();
                    }

                   
                },
                error: function(error){
                }
                });   
            
        }
        });
    
            // buscar producto compra
     $('#txt_id_productos').keyup(function(e) {
            e.preventDefault();
            var id_producto = $(this).val();
            var action = 'infoProducto';
            if (id_producto != '') {
                  $.ajax({
                url:'ajax.php',
                type: "POST",
                async: true,
                data: {action:action, id_producto:id_producto},
                // va a enviar lo que hay en la data

                success: function(response){
                     // console.log(response);
                  if (response != 'error') {
                    var info = $.parseJSON(response);
                    // $('#txt_desc_producto').val('0');
                    $('#txt_nombre_produ').html(info.nombre);
                    $('#txt_existencias').html(info.stock);
                    $('#txt_cant_productos').val('1');
                    $('#txt_precio_producto').val('1');
                   
                    $('#txt_precio_totales').html(info.precio);
                    // activar cantidad y descuento
                     $('#txt_cant_productos').removeAttr('disabled');
                     $('#txt_precio_producto').removeAttr('disabled');
                     // $('#txt_desc_producto').removeAttr('disabled');
                     // mostrar boton agregar
                     if ($('#txt_cant_productos').val()>1) {
                        $('#agregar_productos').slideUp();
                        }else{
                     $('#agregar_productos').slideDown();
                        }
                    
                    }else{
                    // $('#txt_desc_producto').val('0');
                    $('#txt_nombre_produ').html('-');
                    $('#txt_existencias').html('-');
                    $('#txt_cant_productos').val('0');
                    $('#txt_precio_producto').val('0');
                    $('#txt_precio_totales').html('0.00');
                    // bloquear cantidad
                    $('#txt_cant_productos').attr('disabled','disabled');
                      $('#txt_precio_producto').attr('disabled','disabled');
                    // $('#txt_desc_producto').attr('disabled','disabled');
                    // ocultar boton agregar
                    $('#agregar_productos').slideUp();
                    }

                   
                },
                error: function(error){
                }
                });   
            
        }
        });


        // validar cantidad de producto
         $('#txt_cant_producto').keyup(function(e)  {
            e.preventDefault();
    
             // var descuento=$('#txt_precio').html()*$('#txt_desc_producto').val()/100;
            var precio_total = $(this).val()*($('#txt_precio').html());
            var existencia=parseInt($('#txt_existencia').html());
            $('#txt_precio_total').html(precio_total);
            // oculta el boton agregar si la cantidad es menor a 1 o si no es un número la funcion isNan sirve para eso
            if (($(this).val()<1 || isNaN($(this).val()))||($(this).val()>existencia)) {
                $('#agregar_producto').slideUp();
            }else{
                $('#agregar_producto').slideDown();
            }
            
        });
         // validar cantidad de producto compra
         $('#txt_cant_productos').keyup(function(e)  {
            e.preventDefault();
    
             // var descuento=$('#txt_precio').html()*$('#txt_desc_producto').val()/100;
            var precio_total = $(this).val()*($('#txt_precio_producto').val());

            $('#txt_precio_totales').html(precio_total);
            // oculta el boton agregar si la cantidad es menor a 1 o si no es un número la funcion isNan sirve para eso
            if (($(this).val()<1 || isNaN($(this).val()))) {
                $('#agregar_productos').slideUp();
            }else{
                $('#agregar_productos').slideDown();
            }
            
        });


         // validar total de producto compra
         $('#txt_precio_producto').keyup(function(e)  {
            e.preventDefault();
    
             // var descuento=$('#txt_precio').html()*$('#txt_desc_producto').val()/100;
            var precio_total = $(this).val()*($('#txt_cant_productos').val());

            $('#txt_precio_totales').html(precio_total);
            // oculta el boton agregar si la cantidad es menor a 1 o si no es un número la funcion isNan sirve para eso
            if (($(this).val()<1 || isNaN($(this).val()))) {
                $('#agregar_productos').slideUp();
            }else{
                $('#agregar_productos').slideDown();
            }
            
        });


         // agregar producto al detalle
         $('#agregar_producto').click(function(e) {
            e.preventDefault();
            if ($('#txt_cant_producto').val() > 0) {
                var id_producto = $('#txt_id_producto').val();
                var cantidad = $('#txt_cant_producto').val();
               
                var action='agregar_producto';
                 $.ajax({
                    url:'ajax.php',
                    type: "POST",
                    async: true,
                    data: {action:action,id_producto:id_producto,cantidad:cantidad},
                    // va a enviar lo que hay en la data
                   success: function(response){
                        // console.log(response);
                        if(response != 'error'){
                            var info = $.parseJSON(response);
                            $('#detalle_venta').html(info.detalle);
                            $('#detalle_totales').html(info.totales);

                            $('#txt_id_producto').val('');
                           
                            $('#txt_nombre_prod').html('-');
                            $('#txt_existencia').html('-');
                            $('#txt_cant_producto').val('0');
                            $('#txt_precio').html('0.00');
                            $('#txt_precio_total').html('0.00'); 
                            // bloquear cantidad
                            $('#txt_cant_producto').attr('disabled','disabled');
                            
                            // ocultar boton agregar
                            $('#agregar_producto').slideUp(); 
                        }else{
                            console.log('no data');
                        }
                         viewProcesar();

                   },
                   error: function(error) {
                   }
                });
            }
        });
// agregar producto al detalle compra
         $('#agregar_productos').click(function(e) {
            e.preventDefault();
            if (($('#txt_cant_productos').val() > 0) && ($('#txt_precio_producto').val() > 0)) {
                var id_producto = $('#txt_id_productos').val();
                var cantidad = $('#txt_cant_productos').val();
               var preciocompra = $('#txt_precio_producto').val();
                var action='agregar_productos';
                 $.ajax({
                    url:'ajax.php',
                    type: "POST",
                    async: true,
                    data: {action:action,id_producto:id_producto,cantidad:cantidad,preciocompra:preciocompra},
                    // va a enviar lo que hay en la data
                   success: function(response){
                        // console.log(response);
                        if(response != 'error'){
                            var info = $.parseJSON(response);
                            $('#detalle_compra').html(info.detalle_compra);
                            $('#detalle_total').html(info.total);

                            $('#txt_id_productos').val('');
                           
                            $('#txt_nombre_produ').html('-');
                            $('#txt_existencias').html('-');
                            $('#txt_cant_productos').val('0');
                            $('txt_precio_producto').html('0.00');
                            $('#txt_precio_totales').html('0.00'); 
                            // bloquear cantidad
                            $('#txt_cant_productos').attr('disabled','disabled');
                            $('#txt_precio_producto').attr('disabled','disabled');
                            // ocultar boton agregar
                            $('#agregar_productos').slideUp(); 
                        }else{
                            console.log('no data');
                        }
                         viewProcesar();

                   },
                   error: function(error) {
                   }
                });
            }
        });

    $('#btn_anular_venta').click(function(e){
    e.preventDefault();
    // cuenta cuantas filas tiene detalle venta
    var rows = $('#detalle_venta tr').length;
    if (rows >0) {
        var action='anularVenta';
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action},
            // va a enviar lo que hay en la data
           success: function(response){
                console.log(response);
                if (response != 'error'){
                   location.reload();
                
                    }
           },
           error: function(error) {
           }
        });
    }
});
// anular compra
$('#btn_anular_compra').click(function(e){
    e.preventDefault();
    // cuenta cuantas filas tiene detalle venta
    var rows = $('#detalle_compra tr').length;
    if (rows >0) {
        var action='anularCompra';
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action},
            // va a enviar lo que hay en la data
           success: function(response){
                console.log(response);
                if (response != 'error'){
                   location.reload();
                
                    }
           },
           error: function(error) {
           }
        });
    }
});


    // procesar venta
     $('#btn_facturar_venta').click(function(e){
    e.preventDefault();
    // cuenta cuantas filas tiene detalle venta
    var rows = $('#detalle_venta tr').length;
    if (rows >0) {
        var action='procesarVenta';
        var id_cliente = $('#id_cliente').val();
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_cliente:id_cliente},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if (response != 'error'){
                    var info = $.parseJSON(response);
                    console.log(info);
                    generarPDF(info.id_cliente,info.id_venta);
                   // location.reload();
                
                    }else{
                        console.log('no data');
                    }
           },
           error: function(error) {
           }
        });
    }
});

      // procesar compra
     $('#btn_facturar_compra').click(function(e){
    e.preventDefault();
    // cuenta cuantas filas tiene detalle venta
    var rows = $('#detalle_compra tr').length;
    if (rows >0) {
        var action='procesarCompra';
        
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if (response != 'error'){
                    var info = $.parseJSON(response);
                    console.log(info);
                   GenerarPDF(info.id_compra);
                
                    }else{
                        console.log('no data');
                    }
           },
           error: function(error) {
           }
        });
    }
});
// cambiar password
 $('.newpassword').keyup(function(e){
    valiPass();
   
});

// Form cambiar contraseña
    $('#btn_Changepass').click(function(e){
    e.preventDefault();
    var passActual=$('#passwords').val();
    
    var passNuevo=$('#newpasswordu').val();
    var passConfir=$('#passwordcon').val();
    var action="Changepass";
    if (passNuevo!= passConfir) {
        $('.alertChangepass').html('<p style="color:red;"> Las contraseñas no son iguales.</p>');
        $('.alertChangepass').slideDown();
        return false;    
        
    }
    if (passNuevo.length<8) {
        $('.alertChangepass').html('<p style="color:red;">La nueva contraseña debe de ser de 8 caracteres.</p>');
        $('.alertChangepass').slideDown();
         return false; 
    }

     $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,passActual:passActual,passNuevo:passNuevo},
            // va a enviar lo que hay en la data
           success: function(response){
                console.log(response);
                if (response != 'error'){
                    var info = $.parseJSON(response);
                    if (info.code == '00') {
                         $('.alertChangepass').html('<p style="color:green;">'+info.msg+'</p>');
                         $('#btn_Changepass')[0].reset();
                    }else{
                        $('.alertChangepass').html('<p style="color:red;">'+info.msg+'</p>');
                    }
                     $('.alertChangepass').slideDown();
                    }
           },
           error: function(error) {
           }
        });
   
});
});

// validar password
function valiPass(){
    var passNuevo=$('#newpasswordu').val();
    var passConfir=$('#passwordcon').val();
    if (passNuevo!= passConfir) {
        $('.alertChangepass').html('<p style="color:red;"> Las contraseñas no son iguales.</p>');
        $('.alertChangepass').slideDown();
        return false;    
        
    }
    if (passNuevo.length<8) {
        $('.alertChangepass').html('<p style="color:red;">La nueva contraseña debe de ser de 8 caracteres.</p>');
        $('.alertChangepass').slideDown();
    }
    $('.alertChangepass').html('');
    $('.alertChangepass').slideUp();
}

// Generar factura venta
function generarPDF(cliente,factura){
    var ancho=1000;
    var alto=800;
    var x= parseInt((window.screen.width/2) - (ancho/2));
    var y= parseInt((window.screen.height/2) - (alto/2));
    $url='../factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height"+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

// Generar factura compra
function GenerarPDF(factura){
    var ancho=1000;
    var alto=800;
    var x= parseInt((window.screen.width/2) - (ancho/2));
    var y= parseInt((window.screen.height/2) - (alto/2));
    $url='../factura/generarFacturaC.php?f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+",height"+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
}

function del_product_detalle(id_det_venta){
    var action='del_product_detalle';
    var id_det_venta= id_det_venta;
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_det_venta:id_det_venta},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if (response != 'error'){
                    var info = $.parseJSON(response);
                     $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    $('#txt_id_producto').val('');
                   
                    $('#txt_nombre_prod').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00'); 
                    // bloquear cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');
                    
                    // ocultar boton agregar
                    $('#agregar_producto').slideUp();
                }else{
                    $('#detalle_venta').html('');
                    $('#detalle_totales').html('');
                }
                    viewProcesar();
           },
           error: function(error) {
           }
        });
}
function del_product_detalles(id_det_compra){
    var action='del_product_detalles';
    var id_det_compra= id_det_compra;
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_det_compra:id_det_compra},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if (response != 'error'){
                    var info = $.parseJSON(response);
                     $('#detalle_compra').html(info.detalle_compra);
                    $('#detalle_total').html(info.total);
                    
                    $('#txt_id_productos').val('');
                   
                    $('#txt_nombre_produ').html('-');
                    $('#txt_existencias').html('-');
                    $('#txt_cant_productos').val('0');
                    $('#txt_precio_producto').html('0.00');
                    $('#txt_precio_totales').html('0.00'); 
                    // bloquear cantidad
                    $('#txt_cant_productos').attr('disabled','disabled');
                     $('#txt_precio_producto').attr('disabled','disabled');
                    // ocultar boton agregar
                    $('#agregar_productos').slideUp();
                }else{
                    $('#detalle_compra').html('');
                    $('#detalle_total').html('');
                }
                    viewProcesar();
           },
           error: function(error) {
           }
        });
}


// mostrar u ocultar boton procesar
function viewProcesar(){
    // va a las filas tr y si hay elementos en el detalle;
    if ($('#detalle_venta tr').length>0) {
        $('#btn_facturar_venta').show();
    }else{
        $('#btn_facturar_venta').hide();
    }
    if ($('#detalle_compra tr').length>0) {
        $('#btn_facturar_compra').show();
    }else{
        $('#btn_facturar_compra').hide();
    }
}

// Mantener venta y cambiar entre modulos
function serchForDetalle(id_usuario){
    var action='serchForDetalle';
    var user= id_usuario;
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,id_usuario:id_usuario},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if(response != 'error'){
                            var info = $.parseJSON(response);
                            $('#detalle_venta').html(info.detalle);
                            $('#detalle_totales').html(info.totales);
                            
                            
                        }else{
                            console.log('no data');
                        }
                 viewProcesar();
           },
           error: function(error) {
           }
        });
    }
    
    function serchForDetalles(id_usuario){
    var action='serchForDetalles';
    var user= id_usuario;
        $.ajax({
            url:'ajax.php',
            type: "POST",
            async: true,
            data: {action:action,user:user},
            // va a enviar lo que hay en la data
           success: function(response){
                // console.log(response);
                if(response != 'error'){
                            var info = $.parseJSON(response);
                        
                            $('#detalle_compra').html(info.detalle_compra);
                            $('#detalle_total').html(info.total);
                            
                        }else{
                            console.log('no data');
                        }
                 viewProcesar();
           },
           error: function(error) {
           }
        });
    }
