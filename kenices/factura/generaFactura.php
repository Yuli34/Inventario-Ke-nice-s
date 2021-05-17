<?php

	//print_r($_REQUEST);
	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../clases/ConexionBD.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;
	use Dompdf\Options;
	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$id_cliente = $_REQUEST['cl'];
		$id_venta = $_REQUEST['f'];
		// $id_compra = $_REQUEST['fc'];
		$anulada = '';

		$query = mysqli_query($conexionbd,"SELECT v.id_venta, DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora, v.id_cliente, v.estado,
												 u.nombre,u.apellido,
												 cl.cedula, cl.nombre as nomcliente, cl.apellido as apecliente,cl.telefono,cl.direccion
											FROM  venta v
											INNER JOIN login u
											ON u.id_usuario = v.id_usuario
											INNER JOIN clientes cl
											ON v.id_cliente = cl.id_cliente
											WHERE v.id_venta = $id_venta AND v.id_cliente = $id_cliente  AND v.estado =1 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$id_venta = $factura['id_venta'];

			if($factura['estado'] == 0){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conexionbd,"SELECT p.nombre as producto,dt.cantidad,dt.totalventa,(dt.cantidad * dt.totalventa) as preciototal
														FROM venta v
														INNER JOIN det_venta dt
														ON v.id_venta = dt.id_venta
														INNER JOIN productos p
														ON dt.id_producto = p.id_producto
														WHERE v.id_venta = $id_venta ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/factura.php');
		    
		    $html = ob_get_clean();
		    
			// instantiate and use the dompdf class
			// $options= new Options;
		 //    $options->set('isHtml5ParserEnabled', true);
		 //     $options->set('isRemoteEnabled',TRUE);
		     $dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			ob_get_clean();
			$dompdf->stream('factura_'.$id_venta.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>