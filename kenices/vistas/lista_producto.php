<?php 
session_start();

include "../clases/ConexionBD.php";                     


 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
    <?php include "dependencias.php"; ?>
    <title>Lista producto</title>
    
</head>
<body >
    <?php include "header.php"; ?>
    <section id="container">
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-12 mt-4 ">
                


                <div class="card-header bg-danger mb-3 text-white">
                <div class="card-body ">
        <h1>Lista de producto</h1>  <button onclick="location.href='registroproducto.php'"   class="btn btn-primary" ><i class="fas fa-user-plus"></i> Nuevo producto</button>
        <div class="table-responsive">
        <table style="text-align: center; border-color: black"class="table table-striped table-bordered table-white">
            <tr class="table-active">
                <th>Id  </th>
                <th>Categoría   </th>
                <th>Proveedor    </th>
                <th>Producto   </th>
                <th>Marca   </th>
                 <th>Tamaño </th>
                <th>Stock    </th>
                <th>Precio</th>
                <th>Foto</th>
                <th>Descripción</th>
                <th>Estado  </th>
                <th>Acciones    </th>
            </tr>
            <?php   
                    // innerjoin combinar filas de 2 o mas tablas
                    $query= mysqli_query($conexionbd,"SELECT p.id_producto,p.nombre as producto,p.marca,p.tamaño,p.estado,p.stock,p.precio,p.foto,p.descripcion,p.estado,c.nombre, pr.proveedor FROM productos p INNER JOIN categorias c on p.id_categoria=c.id_categoria INNER JOIN proveedor pr on p.id_proveedor=pr.id_proveedor ORDER BY estado DESC,id_producto ASC");
                    mysqli_close($conexionbd);
                    $result= mysqli_num_rows($query);
                    if ($result>0){
                        while ($data= mysqli_fetch_array($query)){
                            if ($data['foto']!='img_producto.png') {
                                // abro ruta de imagen y concateno con el nombre de la foto
                                $foto='../img/uploads/'.$data['foto'];
                            }else{
                                $foto='../img/uploads/'.$data['foto'];
                            }
                ?>  
                    

                     <tr <?php if ($data["stock"]<=5){  ?>style="background-color: #EE8EAC"<?php }else if ($data["stock"]<=15){ ?>style="background-color: #EEEE8E"<?php }else if ($data["stock"]>=100){ ?>style="background-color: #93EB90"<?php } ?>>
                    <td><?php   echo $data["id_producto"]  ?>  </td>
                    <td><?php   echo $data["nombre"] ?>  </td>
                    <td><?php   echo $data["proveedor"] ?>   </td>
                    <td><?php   echo $data["producto"] ?></td>
                    <td><?php   echo $data["marca"] ?></td>
                    <td><?php   echo $data["tamaño"] ?></td>
                    <td ><?php   echo $data["stock"]; ?></td>
                    <td ><?php   echo $data["precio"] ?></td>
                    <td  class="img_producto"><img src="<?php echo $foto ?>" alt="<?php echo $data["producto"] ?>"></td>
                    <td><?php if(empty($data["descripcion"])){
                        echo $data["descripcion"]='-----';
                    }else{
                        echo $data["descripcion"];} ?></td>


                    <td><?php if ($data["estado"]==0) { ?><span class="badge bg-danger"> <?php echo $data["estado"]='Inactivo' ?></span><?php }elseif ($data["estado"]==1){ ?><span class="badge bg-success"> <?php echo $data["estado"]='Activo' ?></span><?php } ?>
                </td>
                    <!-- ?enviar datos a html -->
                    <td> 
                        <?php if ($data["estado"]=='Activo' && $_SESSION['id_rol']!=1) {
                            # code...
                         ?>
                         <!-- echo estoy enviando el id cliente por la url -->
                    <a  href="modificar_producto.php?id_producto=<?php    echo $data["id_producto"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i>Editar</button></a>  
                <?php }elseif ($data["estado"]=='Activo' && $_SESSION['id_rol']==1){ ?>
                         <!-- echo estoy enviando el id proveedor por la url -->
                    <a  href="modificar_producto.php?id_producto=<?php    echo $data["id_producto"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i></button></a>    
                     <a href="eliminar_producto.php?id_producto=<?php     echo $data["id_producto"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></a>
                        <?php  }elseif ($_SESSION['id_rol']==1){ ?> <a  href="restaurar_producto.php?id_producto=<?php    echo $data["id_producto"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-success"><i class="fas fa-trash-restore"></i> Restaurar</button></a><?php } ?></td>
                </tr>
                <?php       

                    }
                    }
                
                
             ?>

        
        </table>
    </div>
    </section>
<?php include "footer.php"; ?>  
</body>
</html>