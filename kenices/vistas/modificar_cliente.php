<?php   
session_start();
    include "../clases/ConexionBD.php";
     if(!empty($_POST))
     {
        $alert = '';
        if(empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido'])  || empty($_POST['direccion']) || empty($_POST['email']) || empty($_POST['telefono'])){
              $alert='*Todos los campos son obligatorios.';
            }else{
                
                $id_cliente = $_POST['id_cliente'];
                $cedula=$_POST['cedula'];
                    $nombre= $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $email= $_POST['email'];
                    
                    $direccion = $_POST['direccion'];
                    $telefono = $_POST['telefono'];
                   
            
                    $query = mysqli_query($conexionbd, "SELECT * FROM clientes WHERE (cedula='$cedula' AND  id_cliente != $id_cliente) OR (email='$email' AND  id_cliente != $id_cliente) "); 
                    
                    $result=mysqli_fetch_array($query);
                    $result=count((array)$result);
                    if ($result > 0 ) {
                        $alert='*La cédula o el correo ya existe. ';
                    }else{
                                 $sql_update=mysqli_query($conexionbd,"UPDATE clientes SET cedula=$cedula,nombre='$nombre',apellido='$apellido',direccion='$direccion',email='$email',telefono='$telefono' WHERE id_cliente=$id_cliente");
                        }
                        
                    if ($sql_update) {
                       $alert='*Se actualizo correctamente el cliente';
                

                    }else{
                         $alert='*Error, no se actualizo el cliente';
                    
                    }
                    }

            }
            
        



        // Mostrar datos(al cambiar el id usuario en la consulta entonces lo devuelve a la lista)reques recibe el metdo post o el metodo get. Recibe id del metodo post 
            if(empty($_REQUEST['id_cliente']))
            {
                header('location: lista_cliente.php');
                mysqli_close($conexionbd);
            }
            // Es igual a lo que se esta buscando en la url
            $id_cliente=$_REQUEST['id_cliente'];

            // consulta
            $sql=mysqli_query($conexionbd,"SELECT * FROM clientes  where id_cliente=$id_cliente AND estado=1");
            mysqli_close($conexionbd);
           $result_sql=mysqli_num_rows($sql);
           if ($result_sql ==0) {

                header('location: lista_cliente.php');
            }else{
                $option='';
                while ($data=mysqli_fetch_array($sql)) {
                    # code...
                    $id_cliente=$data['id_cliente'];
                    $cedula=$data['cedula'];
                     $nombre= $data['nombre'];
                    $apellido = $data['apellido'];
                    $email= $data['email'];
                    $direccion = $data['direccion'];
                    $telefono = $data['telefono'];
         }
     }
    
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Actualizar cliente</title>
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

        <h1 style="border-bottom: white 2px groove">Actualizar cliente</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <input type="hidden"  name="id_cliente" id="id_cliente" value="<?php echo $id_cliente; ?>">
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="cedula">Cédula: </label>
                                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula" required autofocus value="<?php echo $cedula ?>">
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus value="<?php echo $nombre ?>" >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido">Apellido: </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos"required value="<?php echo $apellido ?>">
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido." value="<?php echo $email ?>">
                                 </div>
                                <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required value="<?php echo $direccion ?>">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required value="<?php echo $telefono ?>">
                            </div>
                             
                             
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Actualizar cliente</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
