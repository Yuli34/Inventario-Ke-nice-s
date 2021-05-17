<?php
	
	$total = 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="en">
<head >  
	<meta charset="UTF-8">
	<title>Factura</title>
   <style type="text/css">
   	@import url('fonts/BrixSansRegular.css');
@import url('fonts/BrixSansBlack.css');

*{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}
p, label, span, table{
	font-family: 'BrixSansRegular';
	font-size: 9pt;
}
.h2{
	font-family: 'lucida bright';
	font-size: 20pt;
	word-spacing: 10px;
	text-align: center;
	margin right: 1px;
}
.h3{
	font-family: 'BrixSansBlack';
	font-size: 12pt;
	display: block;
	background: #E8417B;
	color: #FFF;
	text-align: center;
	padding: 3px;
	margin-bottom: 5px;
}
#page_pdf{
	width: 95%;
	margin: 15px auto 10px auto;
}

#factura_head, #factura_cliente, #factura_detalle{
	width: 100%;
	margin-bottom: 10px;
}
/*.logo_factura{
	width: 300px; height: 300px

}*/
.info_empresa{
	
	
}
.info_factura{

	/*width: -100%;
	margin: 100px 100px;
	text-align: center;*/
}
.info_cliente{
	width: 25%;

}
.datos_cliente{
	width: 100%;
}
.datos_cliente tr td{
	width: 50%;
}
.datos_cliente{
	padding: 10px 10px 0 10px;
}
.datos_cliente label{
	width: 75px;
	display: inline-block;
}
.datos_cliente p{
	display: inline-block;
}

.textright{
	text-align: right;
}
.textleft{
	text-align: left;
}
.textcenter{
	text-align: center;
}
.round{
	
	border-radius: 10px;
	border: 1px solid #E8417B;
	overflow: hidden;
	
}
.round p{
	/*padding: 0 15px;
*/
	
}

#factura_detalle{
	border-collapse: collapse;
}
#factura_detalle thead th{
	background: #222EAD;
	color: #FFF;
	padding: 5px;
}
#detalle_productos tr:nth-child(even) {
    background: #ededed;
}
#detalle_totales span{
	font-family: 'BrixSansBlack';
}
/*.nota{
	font-size: 8pt;
}
.label_gracias{
	font-family: verdana;
	font-weight: bold;
	font-style: italic;
	text-align: center;
	margin-top: 20px;
}
.anulada{
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translateX(-50%) translateY(-50%);
}*/
   </style>
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<table id="factura_head" style="margin-top:80px ">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="./img/logo.png" style="width: 50px;margin-left: 50px" >
				</div>
			</td>
			<td class="info_empresa" >
				<div>
					<b><span class="h2"style="width: 50px;margin-left: 100px" >FACTURA KE-NICE'S</span></b>
					
				</div>
			</td>
			<td class="info_factura"  >
				<div class="round" style="justify-content: left;
	margin-right: : -1px; margin-left: 50px; padding-left: 1px" >
					<span class="h3">Factura</span>
					<p>No. Factura: <strong><?php echo $factura['id_venta']; ?></strong></p>
					<p>Fecha: <?php echo $factura['fecha']; ?></p>
					<p>Hora: <?php echo $factura['hora']; ?></p>
					<p>Vendedor: <?php echo $factura['nombre']." ".$factura['apellido']; ?></p>
				</div>
			</td>
		</tr>
	</table>
	<table id="factura_cliente" style="margin-top:80px ">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Cliente</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Cedula:</label><p><?php echo $factura['cedula']; ?></p></td>
							<td><label>Teléfono:</label> <p><?php echo $factura['telefono']; ?></p></td>
						</tr>
						<tr>
							<td><label>Nombre:</label> <p><?php echo $factura['nomcliente']." ".$factura['apecliente']; ?></p></td>
							<td><label>Dirección:</label> <p><?php echo $factura['direccion']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>

	<table id="factura_detalle" style="margin-top:80px ">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Producto</th>
					<th class="textright" width="150px">Precio Unitario.</th>
					<th class="textright" width="150px"> Precio Total</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">

			<?php

				if($result_detalle > 0){

					while ($row = mysqli_fetch_assoc($query_productos)){
			 ?>
				<tr>
					<td class="textcenter"><?php echo $row['cantidad']; ?></td>
					<td><?php echo $row['producto']; ?></td>
					<td class="textright"><?php echo $row['totalventa']; ?></td>
					<td class="textright"><?php echo $row['preciototal']; ?></td>
				</tr>
			<?php
						$precio_total = $row['preciototal'];
						
					}
				}

				
			?>
			</tbody>
			<tfoot id="detalle_totales">
				
				<tr>
					<td colspan="3" class="textright"><b><span>TOTAL.</span></b></td>
					<td class="textright" ><b><span><?php echo $precio_total; ?></span></b></td>
				</tr>
		</tfoot>
	</table>
	<div>
		
		<h4 class="label_gracias">¡Gracias por su compra!</h4>
	</div>

</div>

</body>
</html>