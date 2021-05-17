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
    if( empty($_POST['id_categoria']) || empty($_POST['id_proveedor']) || empty($_POST['nombre']) || empty($_POST['marca']) ||  empty($_POST['precio']) || empty($_POST['tamaño']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove'])){
          $alert='*Todos los campos son obligatorios.';
        }else{
            // Recibir datos
             $id_usuario=$_SESSION['id_usuario'];
             $id_producto= $_POST['id_producto'];
                $id_proveedor= $_POST['id_proveedor'];
                $id_categoria= $_POST['id_categoria'];
                $nombre= $_POST['nombre'];
                $marca = $_POST['marca'];
                $stock ;
                $precio= $_POST['precio'];
                $tamaño = $_POST['tamaño'];
                $descripcion = $_POST['descripcion'];

                $imgProducto= $_POST['foto_actual'];
                $imgRemove= $_POST['foto_remove'];
                $foto = $_FILES['foto'];
                $nombre_foto=$foto['name'];
                $type=$foto['type'];
                $url_temp =$foto['tmp_name'];
                $upd= '';
               
               if ($nombre_foto != '') {
                   $destino = '../img/uploads/';
                   $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                   $imgProducto=$img_nombre.'.jpg';
                   $src= $destino.$imgProducto;
               }else{
                        if($_POST['foto_actual'] != $_POST['foto_remove']){
                            $imgProducto='img_producto.png';
                        }
               }
               
                    $query_update=mysqli_query($conexionbd,"UPDATE productos SET  id_categoria=$id_categoria,nombre='$nombre',marca='$marca',precio='$precio',tamaño='$tamaño',foto='$imgProducto',descripcion='$descripcion', id_proveedor=$id_proveedor where id_producto=$id_producto");
                  
                    
                if ($query_update) {
                    // si se actualiza una foto se elimina la foto que esta actualmente ingresando a la ruta
                    if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.png')) || ($_POST['foto_actual'] != $_POST['foto_remove'])){
                        unlink('../img/uploads/'.$_POST['foto_actual']);
                       }
                    if ($nombre_foto !='' ) {
                            move_uploaded_file($url_temp,$src);
                        }
                       
                    
                   $alert='*Se actualizo correctamente el producto';
                }else{
                     $alert='*Error, no se actualizo el producto';
                
                }
            }

       
  
    }
    if (empty($_REQUEST['id_producto'])) {
         header('location:lista_producto.php');
          // mysqli_close($conexionbd);
    }else{
         $id_producto=$_REQUEST['id_producto'];
          $sql=mysqli_query($conexionbd,"SELECT p.id_producto,  c.id_categoria,pro.id_proveedor,p.nombre,c.nombre as categoria, pro.proveedor, p.marca, p.tamaño, p.stock, p.precio, p.foto, p.descripcion  FROM productos p INNER JOIN categorias c on p.id_categoria = c.id_categoria INNER JOIN proveedor pro on p.id_proveedor=pro.id_proveedor where id_producto=$id_producto and p.estado=1");
            
           $result_sql=mysqli_num_rows($sql);
           // validaciones para mostrar la imagen
           $foto='';
           $classRemove='notBlock';
           if ($result_sql >0) {
                $data_producto=mysqli_fetch_assoc($sql);
                    if ($data_producto['foto']!='img_producto.png') {
                            $classRemove='';
                            $foto= '<img id="img" src="../img/uploads/'.$data_producto['foto'].'" alt="Producto">';
                 
                     }
                 }else{
                    
                    header('location: lista_producto.php');
                    }
                        
               }
                   
         
     
    
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Actualizar producto</title>
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

        <h1 style="border-bottom: white 2px groove">Actualizar producto</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
       <!-- atributo post y multipart/form-data (permite adjuntar archivos) -->
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden"  name="id_producto" id="id_producto" value="<?php echo $data_producto['id_producto']; ?>">
                            <input type="hidden"  name="foto_actual" id="foto_actual" value="<?php echo $data_producto['foto']; ?>">
                            <input type="hidden"  name="foto_remove" id="foto_remove" value="<?php echo $data_producto['foto']; ?>">
                                <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto" for="id_categoria">Categoría: </label>
                                 <?php  
                                    $query_categoria=mysqli_query($conexionbd,"SELECT * FROM categorias WHERE estado=1");
                                    // print_r($query_categoria);
                                    $result_categoria=mysqli_num_rows($query_categoria);
                                   
                                 ?>
                                 <select name="id_categoria" id="id_categoria" class="notItemOne" style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;">
                                     <option value= "<?php   echo $data_producto['id_categoria']; ?>"><?php  echo $data_producto['categoria'] ?>   </option>
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
                                    $query_proveedor=mysqli_query($conexionbd,"SELECT id_proveedor, proveedor FROM proveedor WHERE estado=1");
                                    $result_proveedor=mysqli_num_rows($query_proveedor);
                                    mysqli_close($conexionbd);
                                 ?>
                                 <select name="id_proveedor" id="id_proveedor" class="notItemOne" style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;">
                                    <option value= "<?php   echo $data_producto['id_proveedor']; ?>"><?php  echo $data_producto['proveedor'] ?>   </option>
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
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus value="<?php echo $data_producto['nombre'] ?>">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="marca">Marca: </label>
                                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca"required value="<?php     echo $data_producto['marca'] ?>">
                            </div>
                             <!-- div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="stock">Stock: </label>
                                <input type="text" class="form-control" name="stock" id="stock" placeholder="Stock"required>
                            </div> -->
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="precio">Precio de venta: </label>
                             <input type="precio" class="form-control" placeholder="Precio de venta" name="precio"  required  title="Por favor, ingresar un precio valido."value="<?php     echo $data_producto['precio'] ?>">
                                 </div>
                           
                               
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="tamaño">Tamaño: </label>
                                <input type="text" class="form-control" name="tamaño" id="tamaño" placeholder="Tamaño"required value="<?php     echo $data_producto['tamaño'] ?>">
                            </div>
                              <div class="photo">
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="foto">Imagen: </label>
                                  <div class="prevPhoto">
                                    <span class="delPhoto <?php     echo $classRemove; ?>">X</span>
                                    <label for="foto"></label>
                                    <?php   echo $foto; ?>
                                    </div>
                                    <div class="upimg">
                                    <input type="file" name="foto" id="foto">
                                    </div>
                                    <div id="form_alert"></div>
                            </div>
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="descripcion">Descripcion: </label>
                                <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?php     echo $data_producto['descripcion'] ?>">
                            </div>
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Actualizar producto</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
