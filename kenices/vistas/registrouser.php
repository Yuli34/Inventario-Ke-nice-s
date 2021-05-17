<?php   
// Verifica que tipo de rol tenemos}
session_start();
if ($_SESSION['id_rol']!=1) {
    header('location: ../');
}
include "../clases/ConexionBD.php";
 if(!empty($_POST)){
    $alert = '';
    if(empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['direccion']) || empty($_POST['telefono']) || empty($_POST['fecha'])  || empty($_POST['rol']) ){
          $alert='*Todos los campos son obligatorios.';
        }else{
            // $id_usuario=$_SESSION['id_usuario'];
            $cedula = $_POST['cedula'];
                $nombre= $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $email= $_POST['email'];
                $password= md5($_POST['password']);
                // $passHash = password_hash($password, PASSWORD_BCRYPT);
                // $passveri= password_verify($password, $passHash);
                $direccion = $_POST['direccion'];
                $telefono = $_POST['telefono'];
                $fecha = $_POST['fecha'];
                // $usuario = $_POST['rol'];
                $rol = $_POST['rol'];
                $estado;
                // $usuario = $_POST['usuario'];

                // echo $alert="SELECT * FROM login WHERE id_usuario = '$id_usuario' OR email = '$email' ";
                $query = mysqli_query($conexionbd,"SELECT * FROM login WHERE cedula = '$cedula' OR email = '$email'");

// lo que devulve query se mete en el array
                $result=mysqli_fetch_array($query);
                if ($result > 0 ) {
                    $alert='*La cédula o el correo ya existen. ';
                }else{
                    $query_insert=mysqli_query($conexionbd,"INSERT INTO login(cedula,nombre,apellido,email,password,direccion,telefono,fecha,id_rol,estado) VALUES ('$cedula','$nombre','$apellido','$email','$password','$direccion','$telefono','$fecha','$rol',1)");
                    // ,CASE $usuario WHEN 1 THEN 'Administrador' WHEN 2 THEN 'Vendedor' END,'$rol',1)
                if ($query_insert) {
                   $alert='*Se guardo correctamente el usuario';
                }else{
                     $alert='*Error, no se guardo el usuario';
                
                }
                }

        }
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Registro usuario</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <!-- <div class="container justify-content-center"> -->
        <div class="row  justify-content-center ">
<!--         <div class="row justify-content-center"> -->
            <!-- <div class= "col-sm-4"></div> -->
            <div class= "col-sm-3 mt-5 pt-5">
                


                <div class="card-header bg-danger mb-3 text-white text-center"><img class=" img-fluid mx-auto d-block" src="../img/avatar.png" alt="Responsive imagen" height="170px ">
                <div class="card-body ">

        <h1 style="border-bottom: white 2px groove">Registro de usuario</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="cedula">Cédula: </label>
                                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula" required autofocus>
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido">Apellido: </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos"required>
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido.">
                                 </div>
                           
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="password">Contraseña: </label>
                                <input type="password" class="form-control" placeholder="Contraseña"  name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe  contener 8 o más caracteres que sean de al menos un número, una letra mayúscula y minúscula">
                                </div>
                                <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required>
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required>
                            </div>
                              <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="fecha">Fecha: </label>
                                <input type="date" class="form-control" name="fecha" id="fecha" required>
                            </div>
                            <!--  <div >
                                <label style="display: block; padding-right: 240px; margin: 15px auto 5px auto" for="usuario">Usuario: </label>
                                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario"required>
                            </div> -->
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="rol">Rol: </label>
                                <?php 
                                                // Query(consulta) para devolver los roles de la BD 
                                        // mysqli_num_rows obtine cuantas filas hay del quiery mysqli_fetch_array saca los datos
                                        $query_rol=mysqli_query($conexionbd,"SELECT * FROM rol");
                                        mysqli_close($conexionbd);
                                        $result_rol=mysqli_num_rows($query_rol);

                                        
                                     ?>
                                <select  style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;" name="rol" id="rol" >
                                    
                                     <?php 

                                          if ($result_rol > 0) {
                                             while($rol=mysqli_fetch_array($query_rol)){
                                      ?>     
                                          <option value="<?php echo $rol["id_rol"]; ?>" > <?php echo $rol["rol"] ?> </option>
                                       <?php       
                                             }
                                        
                                        }  
                                        ?>
                                     
                                    
                                </select>
                            </div>
                             <!-- en caso de que alert tenga algo imprime algo  de lo contrario nada(? lo que iria despues es lo q se quiere hacer, : seria de lo contrario-->
                             
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Registrar usuario</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
