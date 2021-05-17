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

                       		<table class="tbl_kardex table table-striped  table-bordered" >
           						            			            			
			<tr class="table-active" style="background-color: #1D88CF; color: white;text-align: center">
			
				<th>#	</th>
				<th>Fecha  </th>
				<th>Factura	</th>
        <th >Usuario</th>
        <th >Movimiento</th>
				<th >Stock Inicial</th>
				<th>Ingreso	</th>
        <th>Egreso </th>
        <th>Stock Actual</th>
			</tr>	 
				 <?php   
          $id_producto=$_REQUEST['id_producto'];
                    // innerjoin combinar filas de 2 o mas tablas
                    $query= mysqli_query($conexionbd,"SELECT k.id_kardex,k.id_producto,k.fecha,k.id_venta,k.id_compra,k.Movimiento,k.stock_inicial,k.ingreso,k.egreso,k.stock_actual,p.id_producto,u.nombre,u.apellido FROM det_kardex k INNER JOIN productos p ON k.id_producto=p.id_producto INNER JOIN login u on k.id_usuario=u.id_usuario WHERE k.id_producto=$id_producto ORDER BY k.fecha DESC");
                    mysqli_close($conexionbd);
                    $result= mysqli_num_rows($query);
                    if ($result>0){
                        while ($data= mysqli_fetch_array($query)){
                            
                ?>  
                    

                    <tr >

                    <td><?php   echo $data["id_kardex"]  ?>  </td>
                    <td><?php   echo $data["fecha"] ?>  </td>
                    <td><?php if ($data["Movimiento"]=='Venta') {
                      echo $data["id_venta"];
                    }elseif ($data["Movimiento"]=='Compra'){ echo $data["id_compra"];}   ?>   </td>
                    <td><?php   echo $data["nombre"]." ".$data["apellido"] ?>   </td>
                    <td><?php   echo $data["Movimiento"] ?></td>
                    <td><?php   echo $data["stock_inicial"] ?></td>
                    <td><?php   echo $data["ingreso"] ?></td>
                    <td><?php   echo $data["egreso"] ?></td>
                    <td><?php   echo $data["stock_actual"] ?></td>
                    
				</tr><?php }} ?>
			 
		</table>	                 
</body>
</html>