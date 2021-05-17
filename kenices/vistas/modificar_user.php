<?php   
session_start();
if ($_SESSION['id_rol']!=1) {
    header('location: ../');
}
    include "../clases/ConexionBD.php";
     if(!empty($_POST))
     {
        $alert = '';
        if(empty($_POST['cedula']) || empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['email']) || empty($_POST['direccion']) || empty($_POST['telefono'])   || empty($_POST['rol']) ){
              $alert='*Todos los campos son obligatorios.';
            }else{
                
                $id_usuario = $_POST['id_usuario'];
                $cedula=$_POST['cedula'];
                    $nombre= $_POST['nombre'];
                    $apellido = $_POST['apellido'];
                    $email= $_POST['email'];
                    $password= $_POST['password'];
                    $passHash = password_hash($password, PASSWORD_BCRYPT);
                    $passveri= password_verify($password, $passHash);
                    $direccion = $_POST['direccion'];
                    $telefono = $_POST['telefono'];
                    $fecha = $_POST['fecha'];
                    // $usuario = $_POST['rol'];
                    $rol = $_POST['rol'];
                    $estado;
                    // $usuario = $_POST['usuario'];

                    // echo $alert="SELECT * FROM login WHERE id_usuario = '$id_usuario' OR email = '$email' ";
                    $query = mysqli_query($conexionbd, "SELECT * FROM login WHERE (cedula='$cedula' AND  id_usuario != $id_usuario) OR (email='$email' AND  id_usuario != $id_usuario) "); 
                    
                    $result=mysqli_fetch_array($query);
                    $result=count((array)$result);
                    if ($result > 0 ) {
                        $alert='*La cédula o el correo ya existe. ';
                    }else{

                        if(empty($_POST['password']) ){

                            $sql_update=mysqli_query($conexionbd,"UPDATE login SET cedula=$cedula,nombre='$nombre',apellido='$apellido',email='$email',direccion='$direccion',telefono='$telefono',id_rol='$rol' WHERE id_usuario=$id_usuario");
                            
                        }else{
                                 $sql_update=mysqli_query($conexionbd,"UPDATE login SET cedula=$cedula,nombre='$nombre',apellido='$apellido',email='$email',password='$password',direccion='$direccion',telefono='$telefono',fecha='$fecha',id_rol='$rol' WHERE id_usuario=$id_usuario");
                        }
                        
                    if ($sql_update) {
                       $alert='*Se actualizo correctamente el usuario';
                

                    }else{
                         $alert='*Error, no se actualizo el usuario';
                    
                    }
                    }

            }
            
        }



        // Mostrar datos(al cambiar el id usuario en la consulta entonces lo devuelve a la lista)reques recibe el metdo post o el metodo get. Recibe id del metodo post 
            if(empty($_REQUEST['id_usuario']))
            {
                header('location: lista_user.php');
                mysqli_close($conexionbd);
            }
            // Es igual a lo que se esta buscando en la url
            $id_usuario=$_REQUEST['id_usuario'];

            // consulta
            $sql=mysqli_query($conexionbd,"SELECT l.id_usuario, l.cedula,l.nombre, l.apellido, l.email, l.direccion, l.telefono, (l.id_rol) as id_rol, (r.rol) as rol FROM login l INNER JOIN rol r on l.id_rol = r.id_rol where id_usuario=$id_usuario and estado=1");
            mysqli_close($conexionbd);
           $result_sql=mysqli_num_rows($sql);
           if ($result_sql ==0) {

                header('location: lista_user.php');
            }else{
                $option='';
                while ($data=mysqli_fetch_array($sql)) {
                    # code...
                    $id_usuario=$data['id_usuario'];
                    $cedula=$data['cedula'];
                     $nombre= $data['nombre'];
                    $apellido = $data['apellido'];
                    $email= $data['email'];
                    $direccion = $data['direccion'];
                    $telefono = $data['telefono'];
                   
                    // $usuario = $data['rol'];
                    $id_rol=$data['id_rol'];
                    $rol = $data['rol'];
                    $estado;

                    // validacion de rol y  para hacer que las opciones sean seleccionadas por defecto necesita un select
                    if ($id_rol==1) {
                        $option='<option value="'.$id_rol.'" select> '.$rol.'</option>';
                    }else if($id_rol==2){ 
                        $option='<option value="'.$id_rol.'" select> '.$rol.'</option>';
            }
        }
    }
 ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
   
   <?php include "dependencias.php"; ?>
    <title>Actualizar usuario</title>
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

        <h1 style="border-bottom: white 2px groove">Actualizar usuario</h1>
       <div id="alert"  > <?php echo isset($alert)? $alert:''; ?> </div>
                        <form action="" method="post">

                                <input type="hidden"  name="id_usuario" id="id_usuario" value="<?php echo $id_usuario; ?>">
                            <div >
                                 <label  style="display: block; text-align:left  ;margin: 15px auto 5px auto"for="cedula">Cédula: </label>
                                <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cédula" required autofocus value="<?php echo $cedula ?>">
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre">Nombre: </label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombres" required autofocus value="<?php echo $nombre ?>" >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido">Apellido: </label>
                                <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos"required value="<?php echo $apellido ?>">
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email"  required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Por favor, ingresar un email valido." value="<?php echo $email ?>">
                                 </div>
                           
                            <!-- <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="password">Contraseña: </label>
                                <input type="password" class="form-control" placeholder="Contraseña"  name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="La contraseña debe  contener 8 o más caracteres que sean de al menos un número, una letra mayúscula y minúscula">

                                </div> -->
                                <input type="hidden"  name="password" id="password" value="<?php echo $password; ?>">
                                <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion">Dirección: </label>
                                <input type="text" class="form-control" name="direccion" id="direccion" placeholder="Dirección"required value="<?php echo $direccion ?>">
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono">Celular: </label>
                                <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Celular"required value="<?php echo $telefono ?>">
                            </div>
                              <!-- <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="fecha">Fecha: </label>
                                <input type="date" class="form-control" name="fecha" id="fecha" required>
                            </div> -->
                            <!--  <div >
                                <label style="display: block; padding-right: 240px; margin: 15px auto 5px auto" for="usuario">Usuario: </label>
                                <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario"required>

                            </div> -->
                            <input type="hidden"  name="fecha" id="fecha" value="<?php echo $fecha ?>">
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="rol"  >Rol: </label>
                                <?php 
                                // Hay que volver a conectar por que arriba se cerro sesion
                                 include "../clases/ConexionBD.php";
                                                // Query(consulta) para devolver los roles de la BD 
                                        // mysqli_num_rows obtine cuantas filas hay del quiery mysqli_fetch_array saca los datos
                                        $query_rol=mysqli_query($conexionbd,"SELECT * FROM rol");
                                        mysqli_close($conexionbd);
                                        $result_rol=mysqli_num_rows($query_rol);

                                        
                                     ?>
                                <select   style="display: block; padding: 3px; border: 1px ;border-radius:5px;  width:100%;" name="rol" id="rol" class="notItemOne" >
                                    
                                     <?php 
                                            echo  $option;
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
                             
                            <button  style="margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Actualizar usuario</button>
                            <!-- <a href="index.php" class="btn btn-primary">Registrar</a> -->
                        </form>
                        </div>
                       
</body>
</html>
