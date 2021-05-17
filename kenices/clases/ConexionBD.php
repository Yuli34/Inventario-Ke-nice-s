<?php 

	$servidor='localhost';
	$usuario='root';
	$password='';
	$bd='inventariokenices';
	

		$conexionbd= @mysqli_connect($servidor,$usuario,$password,$bd);
		
		if (!$conexionbd){
			echo "Error en la conexion";
		// }else{
		// 	echo "Conexion exitosa";
		}
		

// $obj=new conexion();
// if($obj->conexionbd()){
// 	echo "conectado";
// }

 ?>
