<?php 
	// empty si EXISTE POST

	$alert = '';
	session_start();
	if(!empty($_SESSION['active'])){
		header('location: vistas/');
	}else{

	if (!empty($_POST))
	{
		// si existe la session redirecciona al siatema
		
		if(empty($_POST['email']) || empty($_POST['password']))
		{
			$alert= 'Los campos obligatorios para el ingreso a la plataforma no están llenos, por favor llene los dos campos correspondientes';
			
		}else{

			require_once "clases/ConexionBD.php";
// mysqli_real_escape_string sirve para garantizar que los datos sean seguros antes de enviar una consulta a MySQL. MD5 ALGORITMO DE ENCRIPTACIÓN verifica si existe el usuario o el email
			$email= mysqli_real_escape_string($conexionbd,$_POST['email']);
			
			$pass = mysqli_real_escape_string($conexionbd,$_POST['password']);
			// $passHash = password_hash($pass, PASSWORD_BCRYPT);
			// $passveri= password_verify($pass, $passHash);
			$passveri= md5($pass);
			$query = mysqli_query($conexionbd,"SELECT l.id_usuario,l.cedula,l.nombre,l.apellido,l.email,l.password,l.direccion,l.telefono,l.fecha,l.id_rol,l.estado,r.rol FROM login l INNER JOIN rol r ON l.id_rol = r.id_rol WHERE email='$email' AND password = '$passveri' AND estado=1");
			// variable result guardara lo que genere el query que devuelve un numero
			mysqli_close($conexionbd);
			$result = mysqli_num_rows($query);

			// si hay un registro es mayor a 0 guarda en la variable data un array (colección de variables y permite almacenar conjunto de datos) que devuelve el query. Entra cuando el usuario y la contraseña sea correcta
			if ($result > 0) {
				$data = mysqli_fetch_array($query);
				$_SESSION['active'] = true;
				
				$_SESSION['id_usuario'] = $data['id_usuario'];
				$_SESSION['cedula'] = $data['cedula'];
				$_SESSION['nombre'] = $data['nombre'];
				$_SESSION['apellido'] = $data['apellido'];
				$_SESSION['email'] = $data['email'];
				$_SESSION['password'] = $data['password'];
				$_SESSION['direccion'] = $data['direccion'];
				$_SESSION['telefono'] = $data['telefono'];
				$_SESSION['fecha'] = $data['fecha'];
				$_SESSION['nom_rol'] = $data['rol'];
				// $_SESSION['usuario'] = $data['usuario'];
				$_SESSION['id_rol'] = $data['id_rol'];
				$_SESSION['estado'] = $data['estado'];
				header('Location: vistas/');
				
				
			}else{
				// include("validar.php");
				// if ($_POST['email'] != $_POST['email'])
				$alert= '*El correo o la contraseña con el que desea ingresar a la plataforma es incorrecto';
				session_destroy();
			}

		}


	}
}

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
	<title>Iniciar sesion</title>
	<!-- boootstrap libreria para hacer interfaz -->
	<link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/panel-primary.css"> -->
	<!-- Jquery permite manipular elementos del DOM (textos, imágenes, enlaces, etc.) , cambiar el diseño CSS -->
	<script src="librerias/jquery-3.5.1.min.js"></script>
	<!-- Los iconos tipo Solid de Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.0/css/solid.css">
    <script src="https://use.fontawesome.com/releases/v5.10.0/js/all.js"></script>
     <!-- <link rel="stylesheet" type="text/css" href="/css/index.css"> -->
</head>
<body style= "background: url(img/fondo.jpg) no-repeat center center fixed; background-size: cover">

	<br><br><br>
	<div class="container justify-content-center">
		<div class="row justify-content-center">
			<!-- <div class= "col-sm-4"></div> -->
			<div class= "col-sm-4">
			<!-- panel con color -->
				<!-- <div class="card border-primary mb-3">  -->
					<div class="card-header bg-danger mb-3 text-white text-center"><img class="mx-auto d-block" src="img/avatar.png" height="170px">
					<!-- Inventario Floristeria Ke-nice´s</div> -->
					<!-- <img class="card-img-top" src="img/avatar.png" width="100" height="100"> -->
						<div class="card-body">
							<form action="" method="post">
							 <div class="form-group " id="user-group">
                                <!-- <label>Usuario</label> -->
                                <h3>Iniciar Sesión</h3>

                                <!-- <input style="font-family:Font Awesome\ 5 Free" type="text" placeholder="&#xf007;  Email" class="form-control" name="usuario" id="usuario"> -->
                                <input type="email" placeholder="Email"class="form-control" name="email"  required autofocus pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido.">
								<!-- <script src="js/validar.js"></script> -->
                            </div>
                            <br>
                            <div class="form-group" id="contrasena-group">
                                <!-- <label>Contraseña</label> -->
                                <input type="password" placeholder="Contraseña" class="form-control" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe  contener 8 o más caracteres que sean de al menos un número, una letra mayúscula y minúscula">
                                <!-- en caso de que alert tenga algo imprime algo  de lo contrario nada(? lo que iria despues es lo q se quiere hacer, : seria de lo contrario-->
                               
                             </div>
   
                             <div class="alert"> <?php echo isset($alert)? $alert:''; ?> </div>
                            <button   class="btn btn-primary" ><i class="fas fa-sign-in-alt"></i>Ingresar</button>
                            <!-- <a href="registro.php" class="btn btn-secondary">Registrarse</a> -->
                            <!-- <br></br>	
                            <a href="olvidarclave.php" style="color:#100;">¿Has olvidado tu contraseña?</a> -->

                        	</form>
						</div>
				</div>
			</div>
			<!-- <div class= "col-sm-4"></div> -->
		</div>
	</div>
</body>
</html>