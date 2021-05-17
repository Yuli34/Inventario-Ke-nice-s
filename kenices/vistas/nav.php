<nav>
			<ul>
				<li><a href="index.php">Inicio</a></li>
				<?php 
						if ($_SESSION['id_rol']==1) {
							# code...
					 ?>
				<li class="principal">
					<a href="#"> Usuarios <i class="fas fa-angle-down"></i> </a>
					<ul>
						<li><a href="registrouser.php">Nuevo Usuario</a></li>
						<li><a href="lista_user.php">Lista de Usuarios</a></li>
					</ul>
				</li>
			<?php } ?>
				<li class="principal">
					<a href="#">Clientes <i class="fas fa-angle-down"></i></a>
					<ul>
						<li><a href="registrocliente.php">Nuevo Cliente</a></li>
						<li><a href="lista_cliente.php">Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Proveedores <i class="fas fa-angle-down"></i></a>
					<ul>
						<li><a href="registroproveedor.php">Nuevo Proveedor</a></li>
						<li><a href="lista_proveedor.php">Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Categoría <i class="fas fa-angle-down"></i></a>
					<ul>
						<li><a href="registrocategoria.php">Nueva Categoría</a></li>
						<li><a href="lista_categoria.php">Lista Categorías</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Productos <i class="fas fa-angle-down"></i></a>
					<ul>
						<?php 
							if ($_SESSION['id_rol']==1) {
								# code...
							
						 ?>
						<li><a href="registroproducto.php">Nuevo Producto</a></li>
						<?php } ?>
						<li><a href="lista_producto.php">Lista de Productos</a></li>
					</ul>
				</li>	
				<li class="principal">
					<a href="#">Compras <i class="fas fa-angle-down"></i></a>
					<ul>
						<li><a href="registrocompra.php">Nuevo Compra</a></li>
						<!-- <li><a href="#">Lista Compras</a></li> -->
					</ul>
				</li>
				<li class="principal">
					<a href="#">Ventas <i class="fas fa-angle-down"></i></a>
					<ul>
						<li><a href="registroventa.php">Nuevo Venta</a></li>
						<!-- <li><a href="#">Lista Ventas</a></li> -->
					</ul>
				</li>
				<li class="principal">
					<a href="registrokardex.php">Kardex </a>
				</li>
			</ul>
		</nav>