<?php 
session_start();
// echo md5($_SESSION['id_usuario']);
include "../clases/ConexionBD.php";
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php include "dependencias.php"; ?>
	<title>Kardex</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <div class="row  justify-content-center ">

               <div class= "col-sm-8 mt-5 pt-5">
                       <div class="datos_kardex">	

                       		<div class="card-body bg-danger mb-3 text-white text-center" style="border-radius:10px">

                       		<h1 style="margin-bottom: 30px">Kardex</h1>	

                       		<table class="tbl_kardex table table-striped  table-bordered" style="border-color: black">
           						            			            			
			<tr class="table-active" style="background-color: #1D88CF; color: white;text-align: center">
			
				<th>Código	</th>
				
				<th>Nombre	</th>
        <th >Marca</th>
				<th >Tamaño</th>
				<th>Categoria	</th>
        <th>Proveedor </th>
        <th>Stock </th>
				<th>Accion	</th>
			</tr>	 
				 <?php   
                    // innerjoin combinar filas de 2 o mas tablas
                    $query= mysqli_query($conexionbd,"SELECT p.id_producto,p.nombre as producto,p.marca,p.tamaño,p.stock,c.nombre as categoria, pr.proveedor FROM productos p INNER JOIN categorias c on p.id_categoria=c.id_categoria INNER JOIN proveedor pr on p.id_proveedor=pr.id_proveedor ORDER BY id_producto ASC");
                    mysqli_close($conexionbd);
                    $result= mysqli_num_rows($query);
                    if ($result>0){
                        while ($data= mysqli_fetch_array($query)){
                            
                ?>  
                    

                    <tr <?php if ($data["stock"]<=5){  ?>style="background-color: #EE8EAC"<?php }else if ($data["stock"]<=15){ ?>style="background-color: #EEEE8E"<?php }else if ($data["stock"]>=100){ ?>style="background-color: #93EB90"<?php } ?>>
                    <td><?php   echo $data["id_producto"]  ?>  </td>
                    <td><?php   echo $data["producto"] ?>  </td>
                    <td><?php   echo $data["marca"] ?>   </td>
                    <td><?php   echo $data["tamaño"] ?></td>
                    <td><?php   echo $data["categoria"] ?></td>
                    <td><?php   echo $data["proveedor"] ?></td>
                    <td><?php   echo $data["stock"] ?></td>
                    
				<td><a  href="lista_kardex.php?id_producto=<?php echo $data["id_producto"] ?>"><button id="ver_kardex" style="margin: 0px 0px 0px 0px;" class="btn btn-primary"><i class="fas fa-eye"></i></button></a>
        	</td></tr><?php }} ?>
			 
		</table>	                 
</body>
</html>