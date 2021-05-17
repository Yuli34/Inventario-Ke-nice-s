<?php 
session_start();
// echo md5($_SESSION['id_usuario']);
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<?php include "dependencias.php"; ?>
	<title>Nueva Venta</title>
</head>
<body >
    <?php include "header.php"; ?>


    <div id="container ">

        <div class="row  justify-content-center ">

            <div class= "col-sm-3 mt-5 pt-5">
                
                <div class="card-header bg-danger mb-3 text-white text-center" style="border-radius:10px">
                <div class="card-body" >

       <h1 style="text-align:center;border-bottom: white 2px groove"> Datos de cliente  <a  class="btn_new_cliente" href=""><button id="new_cliente" class="btn btn-primary  " style="margin: 5px 0px 10px 10px;" ><i class="fas fa-user-plus"></i> Nuevo cliente</button></a></h1>

      <form name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos" >
         						<input type="hidden" name="action" value="addCliente">
         						<input type="hidden" name="id_cliente" id="id_cliente" value="" required>

                            <div>  
                                <label  style="display: block; text-align:left  ; "for="cedula">Cédula: </label>
                               <input  type="text" class="form-control" name="cedula_cli" id="cedula_cli" placeholder="Cédula" required autofocus>
                                 </div>

                                <div>  
                                <label style="display: block; text-align:left;margin: 15px auto 5px auto" for="nombre_cli">Nombres: </label>
                                <!-- style="width: 30%; display: inline-block;justify-content: left;margin-top:70px" -->
                                <input  type="text" class="form-control" name="nombre_cli" id="nombre_cli" placeholder="Nombres del cliente" disabled required >
                            </div>
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="apellido_cli">Apellidos: </label>
                                <input type="text" class="form-control" name="apellido_cli" id="apellido_cli" placeholder="Apellidos" disabled required>
                            </div>
                             <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto"for="direccion_cli">Dirección: </label>
                                <input type="text" class="form-control" name="direccion_cli" id="direccion_cli" placeholder="Dirección del cliente" disabled required>
                            </div>
                            <div >
                            <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="email_cli">Correo electrónico: </label>
                             <input type="email" class="form-control" placeholder="Correo electrónico" name="email_cli"  id="email_cli"disabled required>
                                 </div>
                            
                            <div >
                                <label style="display: block; text-align:left; margin: 15px auto 5px auto" for="telefono_cli">Celular: </label>
                                <input type="text" class="form-control" name="telefono_cli" id="telefono_cli" placeholder="Celular" disabled required>
                            </div>
                        	<div >
                            <button  id="registro_cliente"  style="display: none;margin: 30px auto 5px auto" class="btn btn-primary" ><i class="fas fa-save"></i> Guardar cliente</button>
                           </div>
                        </form>
                        </div>
                       </div> 
                       </div>  
                       <div class= "col-sm-8 mt-5 pt-5">
                       <div class="datos_venta">	

                       		<div class="card-body bg-danger mb-3 text-white text-center" style="border-radius:10px">

                       		<h1>Datos de venta</h1>	

                       		<table class="tbl_venta table table-striped  table-bordered" >
           						            			
							<tr class="table-active" style="background-color: #1D88CF; color: white;">
			
							<th class="col-6 textrigh">Vendedor	</th>
							<th class="textrigh">Acciones	</th>
							</tr>	 
							<tr>	
								<td><?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?></td>
								<td ><a  href=""><button style="margin: 0px 0px 0px 0px; display: none;" id="btn_facturar_venta" class="btn btn-success"><i class="fas fa-edit"></i>Procesar</button></a>	
								 <a href=""><button style="margin: 0px 0px 0px 40px;" id="btn_anular_venta"class="btn btn-danger"><i class="fas fa-ban"></i>Anular</button></a>
								</tr>
                       			</table>	 
                       		</div>
                       </div>

                       <div class="card-body bg-danger mb-3 text-white text-center " style="border-radius:10px;margin-top: 40px;">

                       		<table class="tbl_venta table table-striped " >
                       			
			<tr class="table-active" style="background-color: #1D88CF; color: white;text-align: center">
			
				<th>Código	</th>
				
				<th>Nombre	</th>
				<th >Existencia</th>
				<th>Cantidad	</th>
				<th class="textrigh">Precio	</th>
				<th class="textrigh">Precio Total	</th>
				<th>Accion	</th>
			</tr>	 
				<tr>	
				<td><input type="text" name="txt_id_producto" id="txt_id_producto"></td>
				<!-- <td><input type="text" name="txt_desc_producto" id="txt_desc_producto" style="width: 55%" placeholder="%">	</td> -->
				<td id="txt_nombre_prod">-</td>
				<td id="txt_existencia">-</td>
				<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled style="width: 55%">	</td>

				<td id="txt_precio" class="textrigh">0.00</td>
				<td id="txt_precio_total" class="textrigh">0.00</td>
				<td><a  href=""><button id="agregar_producto" style="margin: 0px 0px 0px 0px;" class="btn btn-success"><i class="fas fa-plus"></i> Agregar</button></a>	</td>
				</tr>
				<tr class="table-active" style="background-color: #1D88CF; color: white;">
				<th>Código	</th>
				
				<th colspan="2" style="text-align:center;">Nombre	</th>
				<th >Cantidad	</th>
				<th class="textrigh">Precio	</th>
				<th class="textrigh">Precio Total	</th>
				<th>Accion	</th>
				
			</tr>	 
				<tbody id="detalle_venta">	
				<!-- contenido ajax		 -->

				</tbody>
				<tfoot id="detalle_totales">	
					

				</tfoot>
		</table>	

         

       

   <?php include "footer.php"; ?>	  
   <script type="text/javascript">
   	$(document).ready(function() {
   		var id_usuario='<?php echo $_SESSION['id_usuario'];  ?>';
   		serchForDetalle(id_usuario);	
   	});
   </script>                  
</body>
</html>