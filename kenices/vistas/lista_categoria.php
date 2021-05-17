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
	<title>Lista de categoría</title>
	
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
		<h1>Lista de de categoría</h1>  <button onclick="location.href='registrocategoria.php'"   class="btn btn-primary" ><i class="fas fa-user-plus"></i> Nueva categoria</button>
		<div class="table-responsive">
		<table style="text-align: center;" class="table table-striped table-bordered table-white">
			<tr class="table-active">
				<th>Id	</th>
				<th>Nombre	</th>
				<th>Descripción	</th>
				
				
				<th>Estado	</th>
				<th>Acciones	</th>
			</tr>
			<?php 	
					// innerjoin combinar filas de 2 o mas tablas
					$query= mysqli_query($conexionbd,"SELECT * FROM categorias  ORDER BY estado DESC,id_categoria ASC");
					mysqli_close($conexionbd);
					$result= mysqli_num_rows($query);
					if ($result>0){
						while ($data= mysqli_fetch_array($query)){
				?>	
					

					<tr >
					<td><?php 	echo $data["id_categoria"]  ?>	</td>
					
					<td><?php 	echo $data["nombre"] ?></td>
					<td><?php if(empty($data["descripcion"])){
						echo $data["descripcion"]="-----";
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
					<a  href="modificar_categoria.php?id_categoria=<?php 	echo $data["id_categoria"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i>Editar</button></a>	
				<?php }elseif ($data["estado"]=='Activo' && $_SESSION['id_rol']==1){ ?>
						 <!-- echo estoy enviando el id proveedor por la url -->
					<a  href="modificar_categoria.php?id_categoria=<?php 	echo $data["id_categoria"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-warning"><i class="fas fa-edit"></i></button></a>	
					 <a href="eliminar_categoria.php?id_categoria=<?php 	echo $data["id_categoria"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button></a>
					 	<?php  }elseif ($_SESSION['id_rol']==1){ ?> <a  href="restaurar_categoria.php?id_categoria=<?php 	echo $data["id_categoria"] ?>"><button style="margin: 0px 0px 0px 0px;" class="btn btn-success"><i class="fas fa-trash-restore"></i> Restaurar</button></a><?php } ?></td>
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