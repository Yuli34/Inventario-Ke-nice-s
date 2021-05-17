<?php

	
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../clases/ConexionBD.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;
	use Dompdf\Options;
	if(empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		
		$id_compra = $_REQUEST['f'];
		// $id_compra = $_REQUEST['fc'];
		$anulada = '';

		$query = mysqli_query($conexionbd,"SELECT v.id_compra, DATE_FORMAT(v.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(v.fecha,'%H:%i:%s') as  hora,  v.estado,
												 u.nombre,u.apellido
												
											FROM  compra v
											INNER JOIN login u
											ON u.id_usuario = v.id_usuario
											
											WHERE v.id_compra = $id_compra  AND v.estado =1 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$id_compra = $factura['id_compra'];

			if($factura['estado'] == 0){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conexionbd,"SELECT p.nombre as producto,dt.cantidad,pr.proveedor, pr.contacto,pr.telefono,dt.totalcompra,(dt.cantidad * dt.totalcompra) as preciototal
														FROM compra v
														INNER JOIN det_compra dt
														ON v.id_compra = dt.id_compra
														INNER JOIN productos p
														ON dt.id_producto = p.id_producto
														INNER JOIN proveedor pr
														ON pr.id_proveedor = p.id_proveedor
														WHERE v.id_compra = $id_compra ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/FacturaC.php');
		    
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
			$dompdf->stream('factura_'.$id_compra.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>