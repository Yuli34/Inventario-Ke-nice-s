<?php   
// Verifica que tipo de rol tenemos}
session_start();

include "../clases/ConexionBD.php";
 if(!empty($_POST)){
    $alert = '';
    if(empty($_POST['nombre']) ){
          $alert='*El nombre es obligatorio.';
        }else{
            // Recibir datos
               
                $nombre= $_POST['nombre'];
                $descripcion = $_POST['descripcion'];
                $query = mysqli_query($conexionbd,"SELECT * FROM categorias WHERE nombre = '$nombre' ");

// lo que devulve query se mete en el array
                $result=mysqli_fetch_array($query);
                if ($result > 0 ) {
                    $alert='*El id ya existe. ';
                }else{
                    $query_insert=mysqli_query($conexionbd,"INSERT INTO categorias (nombre,descripcion) VALUES ('$nombre','$descripcion')");
                    
                if ($query_insert) {
                   $alert='*Se guardo correctamente la categoria';
                }else{
                     $alert='*Error, no se guardo la categoria';
                
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
    <title>Registro categoria</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/categoria.png" alt="Responsive imagen" height="5px "style="height: 300px">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Registro de categoría</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required autofocus >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="descripcion">Descripción: </label>
                               <textarea type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                            </div>
                        
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Guardar categoria</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
