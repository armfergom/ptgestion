<?php
	include_once ('inc/misc.php'); 
	include_once ('inc/db_connect.php'); 
	
	function formularioBusquedaArticulo()
	{		
		if (isset($_REQUEST['tipo'])){
			//tipo 1 búsqueda por referencia
			if ($_REQUEST['tipo']==1){
				echo '
					<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
					<h4>Búsqueda de artículos</h4>
					<form method="post"> 
						<table class="tabla-centrada">
						<tr>
							<td><label for="nombre" ><b>Referencia:</b></label></td>
							<td><input name="referencia" type="text" maxlength="8"/></td>
						</tr>
						<tr>
							<td></td>
							<td><input name="buscarArticulo" type="submit" value="Buscar" class="boton" /></td>
						</tr>
						</table>
					</form>';
			}
			if ($_REQUEST['tipo']==2){
					echo '
					<a href="menuArticulos.php"><h4>Menú artículos</h4></a>
					<h4>Búsqueda de artículos</h4>
					<form method="post"> 
						<table class="tabla-centrada">
						<tr>
							<td><label for="nombre" ><b>Nombre:</b></label></td>
							<td><input name="nombre" type="text"/></td>
						</tr>
						<tr>
							<td></td>
							<td><input name="buscarArticulo" type="submit" value="Buscar" class="boton" /></td>
						</tr>
						</table>
					</form>';
			}
			
		}
	}
	
	if (isset($_REQUEST['buscarArticulo'])){
		if($_REQUEST['tipo']==1){
			$referencia=$_REQUEST['referencia'];
			$query="SELECT * FROM articulo WHERE Referencia='$referencia'";
			
			try {
				$stmt = $dbh->query($query);
				$num = $stmt->rowCount();
				
				if ($num==1){
					Header("Location:datosArticulo.php?articulo=$referencia");
				}
				else{
					include_once ('inc/header.php');
					echo '<a href="menuArticulos.php"><h4>Menú artículos</h4></a>';
					echo '<p class="parrafoCentrado">No se ha encontrado ningún artículo con esa referencia. Buscar <a href="busquedaArticulo.php?tipo=1">otro</a> artículo.</p>';
				}
			}
			catch(PDOException $e ) {
				// tratamiento del error
				die("error: ".$e->GetMessage());
			}
	
		}
		if($_REQUEST['tipo']==2){
			$nombre=$_REQUEST['nombre'];
			$query2="SELECT Referencia, Nombre, Precio, Coste, IdProveedor, Unidades FROM articulo WHERE Nombre LIKE '%$nombre%'";
			
			try {
				include_once ('inc/header.php');
				$stmt = $dbh->query($query2);
				$num = $stmt->rowCount();
				if($num >0){
					listaArt($stmt);
				}
				else
					echo '<p class="parrafoCentrado">No se ha encontrado ningún artículo con ese nombre. Buscar <a href="busquedaArticulo.php?tipo=2">otro</a> artículo.</p>';
			}

			catch(PDOException $e ) {
				// tratamiento del error
				die("error: ".$e->GetMessage());
			}
	
		}
	
	}
	else{
		include_once ('inc/header.php');	
		formularioBusquedaArticulo();
	}

	
	include_once('inc/footer.php');

?>