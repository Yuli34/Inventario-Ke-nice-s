<?php 
session_start();
if ($_SESSION['id_rol']!=1) {
    header('location: ../');
}
include "../clases/ConexionBD.php";
	// Validacion recibir por el metodo post el id del usuario
	if (!empty($_POST)) 
	{
		if ($_POST['id_usuario']==1) {
			header("location: lista_user.php");
			mysqli_close($conexionbd);
			exit;
		}
		$id_usuario=$_POST['id_usuario'];

		$query_restore=mysqli_query($conexionbd,"UPDATE login SET estado=1 WHERE id_usuario=$id_usuario");
		mysqli_close($conexionbd);
	if ($query_restore) {
		header("location: lista_user.php");
		mysqli_close($conexionbd);
	}else{
		echo "Error al eliminar";
	}
	
}
// Metodo request PUEDE RECIbIR Y ENVIAR POR EL METDO POST

	if(empty($_REQUEST['id_usuario']) || $_REQUEST['id_usuario']==1 ){
		header("location: lista_user.php");
		mysqli_close($conexionbd);
	}else{
		
		// recivir variable y guardar en nueva varables
		$id_usuario=$_REQUEST['id_usuario'];
		
		$query=mysqli_query($conexionbd,"SELECT l.id_usuario, l.cedula, l.nombre,l.id_rol, r.rol FROM login l INNER JOIN rol r ON l.id_rol=r.id_rol WHERE l.id_usuario=$id_usuario");
		mysqli_close($conexionbd);
		$result=mysqli_num_rows($query);
		if ($result>0) {
			while ($data=mysqli_fetch_array($query)) {
				$cedula=$data['cedula'];
				$nombre=$data['nombre'];
				$rol=$data['rol'];
				

			}
		}else{
			header("location: lista_user.php");
		}
	}
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
	<?php include "dependencias.php"; ?>
	<title>Restaurar usuario</title>
	

	
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
			<p >Rol:<span style="color:#2329AA"> <?php echo $rol; ?></span></p>
			<form method="post" action="">
				
				<input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
				<input type="hidden" name="id_rol" value="<?php echo $id_rol; ?>">
				<a href="lista_user.php"><button  style="margin: 30px auto 5px auto" class="btn btn-success" ><i class="fas fa-trash-restore"></i> Restaurar</button>
				</a>
				<a href="lista_user.php"  style="margin-left: 7px !important;margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-ban"></i> Cancelar  
				</a>
			</form>
		
		</div>
	</section>
<?php include "footer.php"; ?>	
</body>
</html>