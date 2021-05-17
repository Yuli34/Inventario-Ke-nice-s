<?php   
session_start();
    include "../clases/ConexionBD.php";
     if(!empty($_POST))
     {
        $alert = '';
        if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['direccion'])  || empty($_POST['email']) || empty($_POST['telefono'])){
              $alert='*Todos los campos son obligatorios.';
            }else{
                
                $id_proveedor = $_POST['id_proveedor'];
                $proveedor=$_POST['proveedor'];
                    $contacto= $_POST['contacto'];
                    $direccion = $_POST['direccion'];
                    $email= $_POST['email'];
                    $telefono = $_POST['telefono'];
                    $detalle= $_POST['detalle'];                   
            
                    $query = mysqli_query($conexionbd, "SELECT * FROM proveedor WHERE (proveedor='$proveedor' AND  id_proveedor != $id_proveedor) OR (email='$email' AND  id_proveedor != $id_proveedor) "); 
                    
                    $result=mysqli_fetch_array($query);
                    $result=count((array)$result);
                    if ($result > 0 ) {
                        $alert='*El proveedor o el correo ya existe. ';
                    }else{
                                 $sql_update=mysqli_query($conexionbd,"UPDATE proveedor SET proveedor='$proveedor',contacto='$contacto',direccion='$direccion',email='$email',telefono='$telefono',detalle='$detalle' WHERE id_proveedor=$id_proveedor");
                                 
                        }
                        
                    if ($sql_update) {
                       $alert='*Se actualizo correctamente el proveedor';
                

                    }else{
                         $alert='*Error, no se actualizo el proveedor';
                    
                    }
                    }

            }
            
        



        // Mostrar datos(al cambiar el id usuario en la consulta entonces lo devuelve a la lista)reques recibe el metdo post o el metodo get. Recibe id del metodo post 
            if(empty($_REQUEST['id_proveedor']))
            {
                header('location: lista_proveedor.php');
                mysqli_close($conexionbd);
            }
            // Es igual a lo que se esta buscando en la url
            $id_proveedor=$_REQUEST['id_proveedor'];

            // consulta
            $sql=mysqli_query($conexionbd,"SELECT * FROM proveedor  where id_proveedor=$id_proveedor AND estado=1");
            mysqli_close($conexionbd);
           $result_sql=mysqli_num_rows($sql);
           if ($result_sql ==0) {

                header('location: lista_proveedor.php');
            }else{
                $option='';
                while ($data=mysqli_fetch_array($sql)) {
                    # code...
                    $id_proveedor=$data['id_proveedor'];
                    $proveedor=$data['proveedor'];
                     $contacto= $data['contacto'];
                    $direccion = $data['direccion'];

                    $email= $data['email'];
                     $telefono = $data['telefono'];
                    $detalle = $data['detalle'];
                   
         }
     }
    
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Actualizar proveedor</title>
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

        <h1 style="border-bottom: white 2px groove">Actualizar proveedor</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <input type="hidden"  name="id_proveedor" id="id_proveedor" value="<?php echo $id_proveedor; ?>">
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="proveedor">Proveedor: </label>
                                <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Proveedor" required autofocus value="<?php echo $proveedor ?>">
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="contacto">Contacto: </label>
                                <input type="text" class="form-control" name="contacto" id="contacto" placeholder="Contacto" required autofocus value="<?php echo $contacto ?>" >
                            </div>
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required value="<?php echo $direccion ?>">
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido." value="<?php echo $email ?>">
                                 </div>
                               
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required value="<?php echo $telefono ?>">
                            </div>
                              <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="detalle">Detalle: </label>
                                <input type="text" class="form-control" name="detalle" id="detalle" placeholder="Detalle" value="<?php echo $detalle ?>">
                            </div>
                             
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Actualizar Proveedor</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
