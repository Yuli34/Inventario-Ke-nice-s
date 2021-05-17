<?php


	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}
 ?>
	<header >
			<div class="header" >
				
				<h1 >Sistema de Inventario Ke-nices</h1>
				<div class="optionsBar">
					<p>Bogotá, <?php echo fecha(); ?></p>
					<span>|</span>
					<span class="user"><?php echo $_SESSION['nombre']." ".$_SESSION['apellido'] ?></span>
					<img class="photouser" src="../img/avatar.png" alt="Usuario">
					<a href="salir.php" title="Salir"><i class="fas fa-power-off"></i></a>
				</div>
			</div>
			<?php include "nav.php"; ?>
		</header>