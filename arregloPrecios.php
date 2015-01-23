<?php
	
	include_once('inc/header.php');
		
	set_time_limit(0);

	$queryArticulos = "SELECT * FROM articulo";
	$stmtArticulo = $dbh->query($queryArticulos);
	$n = $stmtArticulo->rowCount();
	$i = 0;
	
	echo $n.' articulos <br/>';
	
	$unCuarto = round($n / 4);
	$mitad = round($n / 2);
	$tresCuartos = round($n / 3);
	
	foreach ($stmtArticulo as $rowArticulo){
		
		$referencia = $rowArticulo['Referencia'];
		$precioSinIVA = $rowArticulo['Precio'];
		$precio18 = $precioSinIVA * 1.18;
		$nuevoPrecioSinIVA = $precio18 / 1.21;
				
		$sql = "UPDATE articulo SET Precio=$nuevoPrecioSinIVA WHERE Referencia='$referencia'";

		if($dbh->exec($sql)){
			$i++;
		}else{
			if ($precioSinIVA == $nuevoPrecioSinIVA && $precioSinIVA == 0)
				$i++;
			else
				echo $referencia.'<br/>';
		}
		
		if ($i == $unCuarto)
			echo '25% <br/>';
		if ($i == $mitad)
			echo '50% <br/>';
		if ($i == $tresCuartos)
			echo '75% <br/>';
		if ($i == $n)
			echo '100% <br/>';
			
	}		
		
	include_once('inc/footer.php');

?>