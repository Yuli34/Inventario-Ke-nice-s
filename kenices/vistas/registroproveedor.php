<?php   
// Verifica que tipo de rol tenemos}
session_start();

include "../clases/ConexionBD.php";
 if(!empty($_POST)){
    $alert = '';
    if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['direccion']) || empty($_POST['email']) || empty($_POST['telefono'])){
          $alert='*Los datos son obligatorios.';
        }else{
            // Recibir datos
                $id_usuario=$_SESSION['id_usuario'];
                $proveedor= $_POST['proveedor'];
                $contacto= $_POST['contacto'];
                $direccion= $_POST['direccion'];
                $email= $_POST['email'];
                $telefono= $_POST['telefono'];
                $detalle = $_POST['detalle'];
                
                
                    $query_insert=mysqli_query($conexionbd,"INSERT INTO proveedor (id_usuario,proveedor,contacto,direccion,email,telefono,detalle) VALUES ('$id_usuario','$proveedor','$contacto','$direccion','$email','$telefono','$detalle')");
                    
                if ($query_insert) {
                   $alert='*Se guardo correctamente el proveedor';
                }else{
                     $alert='*Error, no se guardo el proveedor';
                
                }
                }

        
        mysqli_close($conexionbd);
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Registro de proveedor</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/categoria.png" alt="Responsive imagen" height="170px " style="height: 300px">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Registro de proveedor</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="proveedor">Proveedor: </label>
                                <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder=" Proveedor" required autofocus >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="contacto">Contacto: </label>
                                <input type="text" class="form-control" name="contacto" id="contacto" placeholder="Contacto">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Direccion">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo: </label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Correo electrónico">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Telefono: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Telefono">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="detalle">Detalle: </label>
                                <textarea type="text" class="form-control" name="detalle" id="detalle" placeholder="Detalle"></textarea>
                            </div>
                        
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Guardar proveedor</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
