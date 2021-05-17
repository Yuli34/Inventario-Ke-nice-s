<?php   
// Verifica que tipo de rol tenemos}
session_start();

include "../clases/ConexionBD.php";
 if(!empty($_POST)){
    $alert = '';
    if(empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['direccion']) || empty($_POST['email'])  ||  empty($_POST['telefono'])){
          $alert='*Todos los campos son obligatorios.';
        }else{
            // Recibir datos
             $id_usuario=$_SESSION['id_usuario'];
            $cedula = $_POST['cedula'];
                $nombre= $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $direccion = $_POST['direccion'];
                $email= $_POST['email'];
                $telefono = $_POST['telefono'];
               
                $query = mysqli_query($conexionbd,"SELECT * FROM clientes WHERE cedula = '$cedula' ");

// lo que devulve query se mete en el array
                $result=mysqli_fetch_array($query);
                if ($result > 0 ) {
                    $alert='*El número de documento ya existe. ';
                }else{
                    $query_insert=mysqli_query($conexionbd,"INSERT INTO clientes (id_usuario,cedula,nombre,apellido,direccion,email,telefono) VALUES ('$id_usuario','$cedula','$nombre','$apellido', '$direccion','$email','$telefono')");
                    
                if ($query_insert) {
                   $alert='*Se guardo correctamente el cliente';
                }else{
                     $alert='*Error, no se guardo el cliente';
                
                }
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
    <title>Registro cliente</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/avatar.png" alt="Responsive imagen" height="170px ">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Registro de cliente</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="cedula">Cédula: </label>
                                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula" required autofocus>
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido">Apellido: </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos"required>
                            </div>
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required>
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido.">
                                 </div>
                           
                               
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required>
                            </div>
                              <!-- <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="fecha">Fecha: </label>
                                <input type="date" class="form-control" name="fecha" id="fecha" required>
                            </div> -->
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Guardar cliente</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
