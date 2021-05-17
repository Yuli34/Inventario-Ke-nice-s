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

#factura_head,  #factura_detalle{
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
					<p>No. Factura Compra: <strong><?php echo $factura['id_compra']; ?></strong></p>
					<p>Fecha: <?php echo $factura['fecha']; ?></p>
					<p>Hora: <?php echo $factura['hora']; ?></p>
					<p>Usuario: <?php echo $factura['nombre']." ".$factura['apellido']; ?></p>
				</div>
			</td>
		</tr>
	</table>
	

	<table id="factura_detalle" style="margin-top:80px ">
			<thead>
				<tr>
					<th width="50px">Cant.</th>
					<th class="textleft">Producto</th>
					<th class="textleft">Proveedor</th>
					<th class="textleft">Contacto</th>
					<th class="textleft">Telefono</th>
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
					<td><?php echo $row['proveedor']; ?></td>
					<td><?php echo $row['contacto']; ?></td>
					<td><?php echo $row['telefono']; ?></td>
					<td class="textright"><?php echo $row['totalcompra']; ?></td>
					<td class="textright"><?php echo $row['preciototal']; ?></td>
				</tr>
			<?php
						$precio_total = $row['preciototal'];
						
					}
				}

				
			?>
			</tbody>
			<tfoot id="detalle_totales" >
				
				<tr >
					<td colspan="6" class="textright" ><b><span>TOTAL.</span></b></td>
					<td class="textright"><b><span><?php echo $precio_total; ?></b></span></td></b>
				</tr>
		</tfoot>
	</table>
	<div>
		
		<!-- <h4 class="label_gracias">¡Gracias por su compra!</h4> -->
	</div>

</div>

</body>
</html>