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
		// recibe y se almacena en idliente
		$id_cliente=$_POST['id_cliente'];

		$query_delete=mysqli_query($conexionbd,"UPDATE clientes SET estado=0 WHERE id_cliente=$id_cliente");
		mysqli_close($conexionbd);
	if ($query_delete) {
		header("location: lista_cliente.php");
		mysqli_close($conexionbd);
	}else{
		echo "Error al eliminar";
	}
	
}
// Metodo request PUEDE RECIbIR Y ENVIAR POR EL METDO POST
// verifica si exite la variable id_cliente que se esta enviando por la url
	if(empty($_REQUEST['id_cliente']) ){
		header("location: lista_cliente.php");
		mysqli_close($conexionbd);
	}else{
		
		// almacenar en una variable
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
	<title>Eliminar cliente</title>
	

	
</head>
<body >
	<?php include "header.php"; ?>
	<section id="container">
		 <div class="row  justify-content-center ">
            <div class= "col-sm-4 mt-5 pt-5  ">
            	 <div class="card border-danger mb-3 text-center card-header "><i class="fas fa-user-times "  style="margin-left:150px;font-size: 150px; color:#E84158 " ></i>
            	 	 <div class="card-body  ">
		<div class="data_delete">
			<h2 style="margin-top: 5px;color: black">??Est?? seguro de eliminar el siguiente registro?</h2>
			
			<p style="margin-top: 20px">Nombre del cliente:<span style="color:#2329AA"> <?php echo $nombre; ?></span></p>
			<p >C??dula:<span style="color:#2329AA"> <?php echo $cedula; ?></span></p>

			<form method="post" action="">
				
				<input type="hidden" name="id_cliente" value="<?php echo $id_cliente; ?>">
				<a href="lista_cliente.php"><button  style="margin: 30px auto 5px auto" class="btn btn-danger" ><i class="far fa-trash-alt"></i> Eliminar</button>
				</a>
				<a href="lista_cliente.php"  style="margin-left: 7px !important;margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-ban"></i> Cancelar  
				</a>
			</form>
		
		</div>
	</section>
<?php include "footer.php"; ?>	
</body>
</html>            
            
