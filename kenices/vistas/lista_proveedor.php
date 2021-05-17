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
	<title>Lista proveedores</title>
	
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
		<h1>Lista de proveedores</h1>  <button onclick="location.href='registroproveedor.php'"   class="btn btn-primary" ><i class="fas fa-user-plus"></i> Nuevo proveedor</button>
		<div class="table-responsive">
		<table class="table table-striped table-bordered table-white">
			<tr class="table-active">
				<th>Id	</th>
				<th>Proveedor	</th>
				<th>Contacto	</th>
				<th>Dirección	</th>
				<th>Email	</th>
				<th>Teléfono	</th>
				<th>Fecha</th>
				<th>Detalle	</th>
				<th>Estado	</th>
				<th>Acciones	</th>
			</tr>
			<?php 	
					// innerjoin combinar filas de 2 o mas tablas
					$query= mysqli_query($conexionbd,"SELECT * FROM proveedor  ORDER BY estado DESC,id_proveedor ASC");
					mysqli_close($conexionbd);
					$result= mysqli_num_rows($query);
					if ($result>0){
						while ($data= mysqli_fetch_array($query)){
				?>	
					

					<tr >
					<td><?php 	echo $data["id_proveedor"]  ?>	</td>
					<td><?php 	echo $data["proveedor"] ?>	</td>
					<td><?php 	echo $data["contacto"] ?>	</td>
					<td><?php 	echo $data["direccion"] ?></td>
					<td><?php 	echo $data["email"] ?></td>
					<td><?php 	echo $data["telefono"] ?></td>
					<td><?php 	echo $data["fecha"] ?></td>
					<td><?php if(empty($data["detalle"])){
						echo $data["detalle"]='-----';
					}else{
						echo $data["detalle"];} ?></td>


					<td><?php if ($data["estado"]==0) { ?><span class="badge bg-danger"> <?php echo $data["estado"]='Inactivo' ?></span><?php }elseif ($data["estado"]==1){ ?><span class="badge bg-success"> <?php echo $data["estado"]='Activo' ?></span><?php } ?>
				</td>
					<!-- ?enviar datos a html -->
					<td> 
						<?php if ($data["estado"]=='Activo' && $_SESSION['id_rol']!=1) {
							# code...
						 ?>
						 <!-- echo estoy enviando el id cliente por la url -->
					<a  href="modificar_proveedor.php?id_proveedor=<?php 	echo $data["id_proveedor"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i>Editar</button></a>	
				<?php }elseif ($data["estado"]=='Activo' && $_SESSION['id_rol']==1){ ?>
						 <!-- echo estoy enviando el id proveedor por la url -->
					<a  href="modificar_proveedor.php?id_proveedor=<?php 	echo $data["id_proveedor"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i></button></a>	
					 <a href="eliminar_proveedor.php?id_proveedor=<?php 	echo $data["id_proveedor"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></a>
					 	<?php  }elseif ($_SESSION['id_rol']==1){ ?> <a  href="restaurar_proveedor.php?id_proveedor=<?php 	echo $data["id_proveedor"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-success"><i class="fas fa-trash-restore"></i> Restaurar</button></a><?php } ?></td>
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