<?php   
// Verifica que tipo de rol tenemos}
session_start();
if ($_SESSION['id_rol']!=1) {
    header('location: ../');
}
include "../clases/ConexionBD.php";
 if(!empty($_POST)){
    // print_r($_FILES['foto']);
    // exit();
    $alert = '';
    if( empty($_POST['id_categoria']) || empty($_POST['id_proveedor']) || empty($_POST['nombre']) || empty($_POST['marca']) ||  empty($_POST['precio']) || empty($_POST['tamaño'])){
          $alert='*Todos los campos son obligatorios.';
        }else{
            // Recibir datos
            // $id_producto=$['id_producto'];
             $id_usuario=$_SESSION['id_usuario'];
                $id_proveedor= $_POST['id_proveedor'];
                $id_categoria= $_POST['id_categoria'];
                $nombre= $_POST['nombre'];
                $marca = $_POST['marca'];
                $stock ;
                $precio= $_POST['precio'];
                $tamaño = $_POST['tamaño'];
                $foto = $_FILES['foto'];
                $nombre_foto=$foto['name'];
                $type=$foto['type'];
                $url_temp =$foto['tmp_name'];
                $imgProducto='img_producto.png';
               $descripcion = $_POST['descripcion'];

               if ($nombre_foto!='') {
                   $destino = '../img/uploads/';
                   $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                   $imgProducto=$img_nombre.'.jpg';
                   $src= $destino.$imgProducto;
               }
                    // $query=mysqli_query($conexionbd,"SELECT id_producto FROM productos");
                    // $data= mysqli_fetch_array($query);
                    $query_insert=mysqli_query($conexionbd,"INSERT INTO productos (id_categoria,id_proveedor,id_usuario,nombre,marca,stock,precio,tamaño,foto,descripcion) VALUES ('$id_categoria','$id_proveedor','$id_usuario','$nombre','$marca',0,'$precio','$tamaño','$imgProducto','$descripcion')");
                    // $query_kardex=mysqli_query($conexionbd,"CALL add_kardex('$data['id_usuario']'");
                    
                if ($query_insert) {
                    if ($nombre_foto !='') {
                        move_uploaded_file($url_temp,$src);
                    }
                   $alert='*Se guardo correctamente el producto';
                }else{
                     $alert='*Error, no se guardo el producto';
                
                }
            }

        
  
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Registro producto</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/categoria.png" alt="Responsive imagen" height="170px "style="height: 300px">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Registro de producto</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
       <!-- atributo post y multipart/form-data (permite adjuntar archivos) -->
                        <form action="" method="post" enctype="multipart/form-data">
                                <div >
                                    
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="id_categoria">Categoría: </label>
                                 <?php  
                                    $query_categoria=mysqli_query($conexionbd,"SELECT * FROM categorias WHERE estado=1");
                                    $result_categoria=mysqli_num_rows($query_categoria);
                                   
                                 ?>
                                 <select name="id_categoria" id="id_categoria" style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;">
                                     <?php   
                                    if ($result_categoria>0) {
                                        while ($id_categoria=mysqli_fetch_array($query_categoria)) {
                                            # code...
                                        
                                     ?>
                                     <option value="<?php echo $id_categoria['id_categoria']; ?>"> <?php    echo $id_categoria['nombre']; ?></option>
                                     
                                 <?php  }
                             } ?>
                                 </select>
                                 </div>
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto" for="id_proveedor">Proveedor: </label>
                                 <?php  
                                    $query_proveedor=mysqli_query($conexionbd,"SELECT * FROM proveedor WHERE estado=1");
                                    $result_proveedor=mysqli_num_rows($query_proveedor);
                                    mysqli_close($conexionbd);
                                 ?>
                                 <select name="id_proveedor" id="id_proveedor" style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;">
                                    <?php   
                                    if ($result_proveedor>0) {
                                        while ($id_proveedor=mysqli_fetch_array($query_proveedor)) {
                                            # code...
                                        
                                     ?>
                                      <option value="<?php  echo $id_proveedor['id_proveedor']; ?>"><?php    echo $id_proveedor['proveedor'];  ?></option>
                                 <?php  }
                             } ?>
                                    
                                 </select>
                                 </div>

                            

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="marca">Marca: </label>
                                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca"required>
                            </div>
                             <!-- div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="stock">Stock: </label>
                                <input type="text" class="form-control" name="stock" id="stock" placeholder="Stock"required>
                            </div> -->
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="precio">Precio de venta: </label>
                             <input type="precio" class="form-control" placeholder="Precio de venta" name="precio"  required pattern="^[0-9]+" title="Por favor, ingresar un precio valido.">
                                 </div>
                           
                               
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="tamaño">Tamaño: </label>
                                <input type="text" class="form-control" name="tamaño" id="tamaño" placeholder="Tamaño"required>
                            </div>
                              <div class="photo">
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="foto">Imagen: </label>
                                  <div class="prevPhoto">
                                    <span class="delPhoto notBlock">X</span>
                                    <label for="foto"></label>
                                    </div>
                                    <div class="upimg">
                                    <input type="file" name="foto" id="foto">
                                    </div>
                                    <div id="form_alert"></div>
                            </div>
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="descripcion">Descripcion: </label>
                                <textarea type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion"></textarea>
                            </div>
                            <button  style="margin: 30px auto 5px auto" id="Agregar_kardex" class="btn btn-primary" ><i class="fas fa-save"></i> Guardar producto</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
