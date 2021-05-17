<?php   
session_start();
    include "../clases/ConexionBD.php";
     if(!empty($_POST))
     {
        $alert = '';
        if(empty($_POST['nombre'])){
              $alert='*El nombre es obligatorio.';
            }else{
                
                $id_categoria = $_POST['id_categoria'];
                $nombre=$_POST['nombre'];
                    
                    $descripcion= $_POST['descripcion'];                   
            
                    $query = mysqli_query($conexionbd, "SELECT * FROM categorias WHERE (nombre='$nombre' AND  id_categoria != $id_categoria)  "); 
                    
                    $result=mysqli_fetch_array($query);
                    $result=count((array)$result);
                    if ($result > 0 ) {
                        $alert='*La categoría ya existe. ';
                    }else{
                                 $sql_update=mysqli_query($conexionbd,"UPDATE categorias SET nombre='$nombre',descripcion='$descripcion' WHERE id_categoria=$id_categoria");
                                  
                        }
                        
                    if ($sql_update) {
                       $alert='*Se actualizo correctamente la categoría';
                

                    }else{
                         $alert='*Error, no se actualizo la categoría';
                    
                    }
                    }

            }
            
        



        // Mostrar datos(al cambiar el id usuario en la consulta entonces lo devuelve a la lista)reques recibe el metdo post o el metodo get. Recibe id del metodo post 
            if(empty($_REQUEST['id_categoria']))
            {
                header('location: lista_categoria.php');
                mysqli_close($conexionbd);
            }
            // Es igual a lo que se esta buscando en la url
            $id_categoria=$_REQUEST['id_categoria'];

            // consulta
            $sql=mysqli_query($conexionbd,"SELECT * FROM categorias  where id_categoria=$id_categoria AND estado=1");
            mysqli_close($conexionbd);
           $result_sql=mysqli_num_rows($sql);
           if ($result_sql ==0) {

                header('location: lista_categoria.php');
            }else{
                $option='';
                while ($data=mysqli_fetch_array($sql)) {
                    # code...
                    $id_categoria=$data['id_categoria'];
                    $nombre=$data['nombre'];
                    
                    $descripcion = $data['descripcion'];
                   
         }
     }
    
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Actualizar categoría</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/categoria.png" alt="Responsive imagen" height="5px " style="height: 300px">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Actualizar categoría</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <input type="hidden"  name="id_categoria" id="id_categoria" value="<?php echo $id_categoria; ?>">
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="nombre">Categoria: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="nombre" required autofocus value="<?php echo $nombre ?>">
                                 </div>

                               
                              <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="descripcion">Descripcion: </label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?php echo $descripcion ?>">

                            </div>
                             
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Actualizar Categoria</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
