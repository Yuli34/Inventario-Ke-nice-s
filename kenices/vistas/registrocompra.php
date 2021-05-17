<?php 
session_start();
// echo md5($_SESSION['id_usuario']);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php include "dependencias.php"; ?>
	<title>Nueva Compra</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <div class="row  justify-content-center ">

            <div class= "col-sm-8 mt-5 pt-5">
                
                <div class="card-header bg-danger mb-3 text-white text-center" style="border-radius:10px">
                <div class="card-body" >

                       <div class="datos_compra">	

                       		<div class="card-body bg-danger mb-3 text-white text-center" style="border-radius:10px">

                       		<h1>Datos de compra</h1>	

                       		<table class="tbl_compra table table-striped  table-bordered" >
           						            			
							<tr class="table-active" style="background-color: #1D88CF; color: white;">
			
							<th class="col-6 textrigh">Usuario	</th>
							<th class="textrigh">Acciones	</th>
							</tr>	 
							<tr>	
								<td><?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?></td>
								<td ><a  href=""><button style="margin: 0px 0px 0px 0px; display: none;" id="btn_facturar_compra" class="btn btn-success"><i class="fas fa-edit"></i>Procesar</button></a>	
								 <a href=""><button style="margin: 0px 0px 0px 40px;" id="btn_anular_compra"class="btn btn-danger"><i class="fas fa-ban"></i>Anular</button></a>
								</tr>
                       			</table>	 
                       		</div>
                       </div>

                       <div class="card-body bg-danger mb-3 text-white text-center " style="border-radius:10px;margin-top: 40px;">

                       		<table class="tbl_compra table table-striped " >
                       			
			<tr class="table-active" style="background-color: #1D88CF; color: white;text-align: center">
			
				<th>Código	</th>
				
				<th>Nombre	</th>
				<th >Existencia</th>
				<th>Cantidad	</th>
				<th >Precio de compra	</th>
				<th class="textrigh">Precio Total	</th>
				<th>Accion	</th>
			</tr>	 
				<tr>	
				<td><input type="text" name="txt_id_productos" id="txt_id_productos"></td>
				<!-- <td><input type="text" name="txt_desc_producto" id="txt_desc_producto" style="width: 55%" placeholder="%">	</td> -->
				<td id="txt_nombre_produ">-</td>
				<td id="txt_existencias">-</td>
				<td><input type="text" name="txt_cant_productos" id="txt_cant_productos" value="0" min="1" disabled style="width: 55%">	</td>

				<td ><input type="text" name="txt_precio_producto" id="txt_precio_producto" value="0.00" min="1" disabled style="width: 80%"></td>
				<td id="txt_precio_totales" class="textrigh">0.00</td>
				<td><a  href=""><button id="agregar_productos" style="margin: 0px 0px 0px 0px;" class="btn btn-success"><i class="fas fa-plus"></i> Agregar</button></a>	</td>
				</tr>
				<tr class="table-active" style="background-color: #1D88CF; color: white;">
				<th>Código	</th>
				
				<th colspan="2" style="text-align:center;">Nombre	</th>
				<th >Cantidad	</th>
				<th >Precio	</th>
				<th class="textrigh">Precio Total	</th>
				<th>Accion	</th>
				
			</tr>	 
				<tbody id="detalle_compra">	
				<!-- contenido ajax		 -->

				</tbody>
				<tfoot id="detalle_total">	
					

				</tfoot>
		</table>	

         

       

   <?php include "footer.php"; ?>	  
   <script type="text/javascript">
   	$(document).ready(function() {
   		var id_usuario='<?php echo $_SESSION['id_usuario'];  ?>';
   		serchForDetalles(id_usuario);	
   	});
   </script>                  
</body>
</html>