<?php 
session_start();
if ($_SESSION['id_rol']!=1) {
    header('location: ../');
}	
include "../clases/ConexionBD.php";
	// Validacion recibir por el metodo post el id del usuario
	if (!empty($_POST)) 
	{
		if (empty($_POST['id_cliente'])) {
			header("location: lista_cliente.php");
			mysqli_close($conexionbd);

		}
		$id_cliente=$_POST['id_cliente'];

		$query_restore=mysqli_query($conexionbd,"UPDATE clientes SET estado=1 WHERE id_cliente=$id_cliente");
		mysqli_close($conexionbd);
	if ($query_restore) {
		header("location: lista_cliente.php");
		mysqli_close($conexionbd);
	}else{
		echo "Error al eliminar";
	}
	
}
// Metodo request PUEDE RECIbIR Y ENVIAR POR EL METDO POST

	if(empty($_REQUEST['id_cliente']) ){
		header("location: lista_cliente.php");
		mysqli_close($conexionbd);
	}else{
		
		// recivir variable y guardar en nueva varables
		$id_cliente=$_REQUEST['id_cliente'];
		
		$query=mysqli_query($conexionbd,"SELECT * FROM clientes WHERE id_cliente=$id_cliente");
		mysqli_close($conexionbd);
		$result=mysqli_num_rows($query);
		if ($result>0) {
			while ($data=mysqli_fetch_array($query)) {
				$cedula=$data['cedula'];
				$nombre=$data['nombre'];
				

			}
		}else{
			header("location: lista_cliente.php");
		}
	}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
	<?php include "dependencias.php"; ?>
	<title>Restaurar cliente</title>
	

	
</head>
<body >
	<?php include "header.php"; ?>
	<section id="container">
		 <div class="row  justify-content-center ">
            <div class= "col-sm-4 mt-5 pt-5  ">
            	 <div class="card border-success mb-3 text-center card-header "><i class="fas fa-user-plus "  style="margin-left:150px;font-size: 150px; color:#41E87F " ></i>
            	 	 <div class="card-body  ">
		<div class="data_delete">
			<h2 style="margin-top: 5px;color: black">¿Está seguro de restaurar el siguiente registro?</h2>
			
			<p style="margin-top: 20px">Nombre:<span style="color:#2329AA"> <?php echo $nombre; ?></span></p>
			<p >Cédula:<span style="color:#2329AA"> <?php echo $cedula; ?></span></p>
			
			<form method="post" action="">
				
				<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
				<a href="lista_cliente.php"><button  style="margin: 30px auto 5px auto" class="btn btn-success" ><i class="fas fa-trash-restore"></i> Restaurar</button>
				</a>
				<a href="lista_cliente.php"  style="margin-left: 7px !important;margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-ban"></i> Cancelar  
				</a>
			</form>
		
		</div>
	</section>
<?php include "footer.php"; ?>	
</body>
</html>