<?php 
session_start();
 ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
	<?php include "dependencias.php"; ?>
	<title>Sisteme Ventas</title>
	
</head>
<body >
	<?php 
	include "header.php"; 
	include "../clases/ConexionBD.php";

	?>
	<div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header  border-primary mb-3 text-white text-center" style="background-color: #41D5CB;"><img class=" img-fluid mx-auto d-block" src="../img/avatar.png" alt="Responsive imagen" height="170px ">
                <div class="card-body " style="margin-top: 50px;background-color: #D5414F">

        <h1 style="border-bottom: white 2px groove;">Perfil</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <input type="hidden"  name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>">
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="cedula">Cédula: </label>
                                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula" required autofocus value="<?php echo $_SESSION['cedula'] ?>" disabled>
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus value="<?php echo $_SESSION['nombre'] ?>" disabled>
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido">Apellido: </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos"required value="<?php echo $_SESSION['apellido'] ?>"disabled>
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido." value="<?php echo $_SESSION['email'] ?>"disabled>
                                 </div>
                           
                           
                               
                                <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required value="<?php echo $_SESSION['direccion'] ?>"disabled>
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required value="<?php echo $_SESSION['telefono'] ?>"disabled>
                            </div>
                           
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="rol">Rol: </label>
                               
                                   <input type="text" class="form-control" name="rol" id="rol" placeholder="rol" required value="<?php echo $_SESSION['nom_rol'] ?>" disabled>  
                                    
                               
                            </div>
                            </div>
                             <div style="margin-top: 30px;background-color: #699FE7" class="card-header  mb-3 text-white text-center">  
                <div class="card-body ">
                	<h1 style="border-bottom: white 2px groove">Cambiar contraseña</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post" name="Changepass" id="txtChangepass">

                                
                            <div>
                            	<input style="margin: 15px auto 5px auto" type="password" class="form-control" name="passwords" id="passwords" placeholder="Contraseña actual" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                            </div>     
                              <div>
                            	<input style="margin: 15px auto 5px auto" type="password" class="form-control newpassword" name="newpasswordu" id="newpasswordu" placeholder="Nueva contraseña" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                            </div> 
                            <div>
                            	<input style="margin: 15px auto 5px auto" type="password" class="form-control newpassword" name="passwordcon" id="passwordcon" placeholder="Confirmarcontraseña"pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                            </div>  
                            <div class="alertChangepass" style="display: none;">
                            	
                            </div>
                            <div>
                            	<button  type="submit" style="margin: 30px auto 5px auto" class="btn btn-primary" id="btn_Changepass"><i class="fas fa-save"></i> Cambiar contraseña</button>
                            </div>                      
                           
                        </form>
                        </div>
<?php include "footer.php"; ?>	
</body>
</html>