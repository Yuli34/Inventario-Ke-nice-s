
<?php 

date_default_timezone_set('America/Bogota');
 function fecha(){

	$mes = array("", "Enero", 
					  "Febrero", 
					  "Marzo", 
					  "Abril", 
					  "Mayo", 
					  "Junio", 
					  "Julio", 
					  "Agosto", 
					  "Septiembre", 
					  "Octubre", 
					  "Noviembre", 
					  "Diciembre");


$day = array("Domingo", 
					  "Lunes", 
					  "Martes", 
					  "Miércoles",  
					  "Jueves", 
					  "Viernes", 
					  "Sábado", 
				);

return $day[date('w')]." ".date('d')." de ". $mes[date('n')] . " de " . date('Y'). "| ".date ('g'). ":". date ('i').date ('A');
// setTimeout('fecha()',1000);
// Defuelve una cadena
}

 ?>
