<?php 
session_start();
// buscar cliente
include "../clases/ConexionBD.php";
// print_r($_POST);
if(!empty($_POST)){
	if($_POST['action'] == 'searchCliente'){
		if(!empty($_POST['clientes'])){
			$cedula_cli=$_POST['clientes'];
		// LIKE hace una busqueda
		$query=mysqli_query($conexionbd,"SELECT * FROM clientes WHERE cedula LIKE '$cedula_cli' AND estado=1");
		mysqli_close($conexionbd);
		$result = mysqli_num_rows($query);
		$data='';
		if($result > 0){
			$data = mysqli_fetch_assoc($query);
		}else{
			$data = 0;
		}
		echo json_encode($data,JSON_UNESCAPED_UNICODE);

		}
		exit;
	}
// Registrar cliente ventas
	if($_POST['action'] == 'addCliente'){
		$id_usuario=$_SESSION['id_usuario'];	
		 $cedula=$_POST['cedula_cli'];
		 $nombre=$_POST['nombre_cli'];
		 $apellido=$_POST['apellido_cli'];
		 $direccion=$_POST['direccion_cli'];
		 $email=$_POST['email_cli'];
		 $telefono=$_POST['telefono_cli'];
	   		$query_insert=mysqli_query($conexionbd,"INSERT INTO clientes (id_usuario,cedula,nombre,apellido,direccion,email,telefono) VALUES ('$id_usuario','$cedula','$nombre','$apellido', '$direccion','$email','$telefono')");
	   		 if ($query_insert) {
	   		 	// se extrae el id del cliente con la funcion mysli
	                   $id_cliente=mysqli_insert_id($conexionbd);
	                   $msg=$id_cliente;
	                }else{
	                     $msg='*Error, no se guardo el cliente';
	                
	                }
	                mysqli_close($conexionbd);
	                echo $msg;
		exit;
	}

// extraer datos del producto
	if($_POST['action'] == 'infoProducto'){
			$id_producto=$_POST['id_producto'];
		$query=mysqli_query($conexionbd,"SELECT * FROM productos WHERE id_producto=$id_producto AND estado=1");
		mysqli_close($conexionbd);
		$result = mysqli_num_rows($query);
		if($result > 0){
			$data = mysqli_fetch_assoc($query);
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
			exit;
		}
			echo 'error';
			exit;
		}
	// Agregar producto al detalle	
	if($_POST['action'] == 'agregar_producto'){
		if (empty($_POST['id_producto']) || empty($_POST['cantidad'])) {
			echo 'error';
		}else{
			$id_producto=$_POST['id_producto'];
			$cantidad=$_POST['cantidad'];
			$token=md5($_SESSION['id_usuario']);
			$query_det_tem_venta=mysqli_query($conexionbd,"CALL add_det_tem_venta($id_producto,$cantidad,'$token')");
			$result= mysqli_num_rows($query_det_tem_venta);
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query_det_tem_venta)) {
					
					$precioTotal= round($data['cantidad']*($data['precioventa']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['precioventa'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalle('.$data['id_det_venta'].');"><i class="fas fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotales='

					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle']=$detalleTabla;
					$arrayData['totales']=$detalleTotales;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}	


	// Agregar producto al detalle	compra
	if($_POST['action'] == 'agregar_productos'){
		if (empty($_POST['id_producto']) || empty($_POST['cantidad'])|| empty($_POST['preciocompra'])) {
			echo 'error';
		}else{
			$id_producto=$_POST['id_producto'];
			$cantidad=$_POST['cantidad'];
			$preciocompra=$_POST['preciocompra'];
			$tokena=md5($_SESSION['id_usuario']);
			$query_det_tem_compra=mysqli_query($conexionbd,"CALL add_det_tem_compra($id_producto,$cantidad,$preciocompra,'$tokena')");
			$result= mysqli_num_rows($query_det_tem_compra);
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query_det_tem_compra)) {
					
					$precioTotal= round($data['cantidad']*($data['preciocompra']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['preciocompra'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalles('.$data['id_det_compra'].');"><i class="fas fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotal='

					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle_compra']=$detalleTabla;
					$arrayData['total']=$detalleTotal;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}	


	// Extrae detalle temp	
	if($_POST['action'] == 'serchForDetalle'){
		if (empty($_POST['id_usuario'])) {
			echo 'error';
		}else{
			$token=md5($_SESSION['id_usuario']);
			$query=mysqli_query($conexionbd, "SELECT tmp.id_det_venta, tmp.token_user,tmp.cantidad,tmp.precioventa,p.id_producto,p.nombre FROM det_tem_venta tmp INNER JOIN productos p ON tmp.id_producto=p.id_producto WHERE token_user='$token'");
			
			$result= mysqli_num_rows($query);
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query)) {
					
					$precioTotal= round($data['cantidad']*($data['precioventa']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['precioventa'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalle('.$data['id_det_venta'].');"><i class="far fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotales='
					
					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle']=$detalleTabla;
					$arrayData['totales']=$detalleTotales;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}
	// extrae detalle tem compra	
	if($_POST['action'] == 'serchForDetalles'){
		if (empty($_POST['user'])) {
			echo 'error';
		}else{
			$tokena=md5($_SESSION['id_usuario']);
			$query=mysqli_query($conexionbd, "SELECT tmp.id_det_compra, tmp.token_user,tmp.cantidad,tmp.preciocompra,p.id_producto,p.nombre FROM det_tem_compra tmp INNER JOIN productos p ON tmp.id_producto=p.id_producto WHERE token_user='$tokena'");
			
			$result= mysqli_num_rows($query);
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query)) {
					
					$precioTotal= round($data['cantidad']*($data['preciocompra']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['preciocompra'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalles('.$data['id_det_compra'].');"><i class="far fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotales='
					
					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle_compra']=$detalleTabla;
					$arrayData['total']=$detalleTotales;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}	





// borrar detalle temp	
	if($_POST['action'] == 'del_product_detalle'){
		// print_r($_POST);
		if (empty($_POST['id_det_venta'])) {
			echo 'error';
		}else{
			$id_det_venta=$_POST['id_det_venta'];
			$token=md5($_SESSION['id_usuario']);
			$query_det_tem_venta=mysqli_query($conexionbd,"CALL del_det_tem_venta($id_det_venta,'$token')");
			$result= mysqli_num_rows($query_det_tem_venta);		
			
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query_det_tem_venta)) {
					
					$precioTotal= round($data['cantidad']*($data['precioventa']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['precioventa'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalle('.$data['id_det_venta'].');"><i class="fas fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotales='
					
					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle']=$detalleTabla;
					$arrayData['totales']=$detalleTotales;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}

// borrar detalle temp	compra
	if($_POST['action'] == 'del_product_detalles'){
		// print_r($_POST);
		if (empty($_POST['id_det_compra'])) {
			echo 'error';
		}else{
			$id_det_compra=$_POST['id_det_compra'];
			$tokena=md5($_SESSION['id_usuario']);
			$query_det_tem_compra=mysqli_query($conexionbd,"CALL del_det_tem_compra($id_det_compra,'$tokena')");
			$result= mysqli_num_rows($query_det_tem_compra);		
			
			$detalleTabla='';
			// $sub_total=0;
			
			$total=0;
			$arrayData=array();

			if ($result>0) {
				while ($data = mysqli_fetch_assoc($query_det_tem_compra)) {
					
					$precioTotal= round($data['cantidad']*($data['preciocompra']),2);
					// $sub_total = round($sub_total+$precioTotal,2);
					$total= round($total+$precioTotal, 2);
					$detalleTabla.='<tr>	
									<td>'.$data['id_producto'].'</td>
									
									<td colspan="2">'.$data['nombre'].'</td>
									<td class="text-center">'.$data['cantidad'].'</td>	
									<td class="textrigh">'.$data['preciocompra'].'</td>
									<td class="textrigh">'.$precioTotal.'</td>
									<td class=""> <a  href=""><button style="margin: 0px 0px 0px 0px;" class="btn btn-danger" onclick="event.preventDefault(); del_product_detalles('.$data['id_det_compra'].');"><i class="fas fa-trash-alt"></i></button></a>	

									</td>
								</tr>';
				}
					$detalleTotales='
					
					
					<tr>	
						<td colspan="6" class="textrigh" style="font-weight: bold;text-align: right;">TOTAL</td>
						<td class="textrigh">'.$total.'</td>

					</tr>';
					$arrayData['detalle_compra']=$detalleTabla;
					$arrayData['total']=$detalleTotales;
					echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
				mysqli_close($conexionbd);
		}
		exit;
	}


	// anularVenta
 	if ($_POST['action']== 'anularVenta') {
 		$token = md5($_SESSION['id_usuario']);
 		$query_del =mysqli_query($conexionbd,"DELETE FROM det_tem_venta WHERE token_user='$token'");
 		mysqli_close($conexionbd);
 		if ($query_del) {
 			echo 'ok';
 		}else{
 			echo 'error';
 		}
 		exit;
 	}
// anularCompra
 	if ($_POST['action']== 'anularCompra') {
 		$tokena = md5($_SESSION['id_usuario']);
 		$query_del =mysqli_query($conexionbd,"DELETE FROM det_tem_compra WHERE token_user='$tokena'");
 		mysqli_close($conexionbd);
 		if ($query_del) {
 			echo 'ok';
 		}else{
 			echo 'error';
 		}
 		exit;
 	}
// procesar ventas
 	if ($_POST['action']== 'procesarVenta') {
 		if (empty($_POST['id_cliente'])) {
 			$id_cliente=1;
 		}else{
 			$id_cliente=$_POST['id_cliente'];
 		}
 		$token = md5($_SESSION['id_usuario']);
 		$id_usuario=$_SESSION['id_usuario'];

 		$query=mysqli_query($conexionbd,"SELECT *FROM det_tem_venta WHERE token_user='$token'");
 		$result=mysqli_num_rows($query);
 		
 		if ($result >0) {
 			$query_procesar=mysqli_query($conexionbd,"CALL procesar_venta($id_usuario,$id_cliente,'$token')");
 			$result_detalle=mysqli_num_rows($query_procesar);
 			if ($result_detalle>0) {
 				$data=mysqli_fetch_assoc($query_procesar);
 				echo json_encode($data,JSON_UNESCAPED_UNICODE);
 			
 		}else{
 			echo 'error';
 		}
 	}else{
 		echo 'error';
 	}
 		mysqli_close($conexionbd);
 		exit;
 	}


// procesar compra
 	if ($_POST['action']== 'procesarCompra') {
 		
 		$tokena = md5($_SESSION['id_usuario']);
 		$id_usuario=$_SESSION['id_usuario'];

 		$query=mysqli_query($conexionbd,"SELECT *FROM det_tem_compra WHERE token_user='$tokena'");
 		$result=mysqli_num_rows($query);
 		
 		if ($result >0) {
 			$query_procesar=mysqli_query($conexionbd,"CALL procesar_compra($id_usuario,'$tokena')");
 			$result_detalle=mysqli_num_rows($query_procesar);
 			if ($result_detalle>0) {
 				$data=mysqli_fetch_assoc($query_procesar);
 				echo json_encode($data,JSON_UNESCAPED_UNICODE);
 			
 		}else{
 			echo 'error';
 		}
 	}else{
 		echo 'error';
 	}
 		mysqli_close($conexionbd);
 		exit;
 	}

// cambiar contraseña
 	if ($_POST['action']== 'Changepass') {
 		print_r($_POST);
 		if (!empty($_POST['passActual']) && !empty($_POST['passNuevo']))  {
 			$passwords=md5($_POST['passActual']);
 			$newPass=md5($_POST['passNuevo']);
 			$id_usuario=$_SESSION['id_usuario'];
 			$code='';
 			$msg='';
 			$arrData=array();
 			$query_user=mysqli_query($conexionbd,"SELECT * FROM login WHERE password='$passwords' AND id_usuario=$id_usuario ");
 			$result =mysqli_num_rows($query_user);	
 			if ($result >0) {
 				$query_update=mysqli_query($conexionbd,"UPDATE login SET password='$newPass' WHERE id_usuario=$id_usuario ");
 				mysqli_close($conexionbd);
 				if($query_update){
 					$code='00';
 					$msg="Su contraseña se ha actualizado con exito.";
 				}else{
 					$code='2';
 					$msg="No es posible actualizar su contraseña.";
 				}
 			}else{
 				$code='1';
 					$msg="La contraseña actual es incorrecta.";
 			}
 			
 			$arrData=array('cod' => $code,'msg'=> $msg);
 			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
 		}else{
 			echo "error";
 			}
 		
 		exit;
 	}

	exit;
}

 ?>