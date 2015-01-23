<?php
	
	include_once('inc/header.php');
	$cadenaDiv1 = '<div id=';
	$cadenaDiv2 = '></div>';
	
	$tipo = $_REQUEST['tipo'];
	
	echo '<script type="text/javascript">
			var numero = 1;
			function otroArticulo()
			{
				var artConNum = "articulo"+numero;
				var artConNumMasUno = "articulo"+(numero+1);
				var cadenaDiv = "'.$cadenaDiv1.'"+artConNumMasUno+"'.$cadenaDiv2.'"; 					
				document.getElementById("articulo" + numero).innerHTML += texto1 + numero + texto2 + numero + texto3 + numero + texto4 + numero + texto5 + numero + texto6 + numero + texto7 + numero + texto8 + cadenaDiv;								
				numero += 1;
			}
			
			function activa(x)
			{
				if (esIntangible(document.getElementById(\'referencia\'+x).value))
					document.getElementById(\'precio\'+x).disabled=false;
				else
					document.getElementById(\'precio\'+x).disabled=true;
			}
		  </script>';
	
	//valida el formulario
	function validaFormulario()
	{
		global $dbh;
		
		$errores = '';
		
		$articulos = 0;
		$i = 1;
		while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
		{				
			$referencia = $_REQUEST["referencia$i"];
			$unidades = $_REQUEST["unidades$i"];
					
			//////////////////
			if ($referencia == 'null' xor $unidades == null)
				$errores .= '<li>La referencia y las unidades son campos obligatorios.</li>';
			else
			{
				if ($unidades != null)
				{
					//ver que las unidades sea un número positivo
					if (!preg_match('/^[1-9][0-9]*$/',$unidades))
						$errores .= "<li>Unidades debe ser un número positivo.</li>";
					else 						
						$articulos += 1;					
				}
			}
			
			//////////////////
						
			$i += 1;
		}
		
		if ($articulos == 0)
			$errores .= "<li>Una venta debe tener al menos un artículo.</li>";

		return $errores;
	}

	//muestra el formulario de altas de listas de boda
	function formularioAltaListaBoda()
	{
		global $dbh, $tipo;
		
		echo '<a href="menuListaBoda.php"><h4>Menú listas de boda</h4></a>';
		
		echo '<h4>Altas</h4>';
			
		echo '<form method="post"> 	';
		
		if (!isset($_REQUEST['continuar']))
		{
			echo '
				<div class="centrado">
					<label for="cliente"><b>Cliente</b></label><br /><select name="cliente">';
					//obtemos los clientes
					try {
							$query = "SELECT IdCliente, Apellidos, Nombre FROM cliente ORDER BY Apellidos ASC";
							$stmt = $dbh->query($query);							
							
							//para cada cliente añadimos una opción
							foreach ($stmt as $row)
							{
								echo '<option value='.$row['IdCliente'].'';							
								
								echo '>'.$row['Apellidos'].' '.$row['Nombre'].'</option>';
							}
					}
					catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
			echo '</select></div><br />	';
		}
			echo '
					<table class="tabla-centrada">
					<tr>
						<td class="centrado"><label for="referencia"><b>&nbsp;&nbsp;Referencia&nbsp;&nbsp;</b></label></td>
						<td class="centrado"><label for="unidades"><b>&nbsp;&nbsp;Unidades&nbsp;&nbsp;</b></label></td>
						<td class="centrado"><label for="comentario"><b>&nbsp;&nbsp;Comentario&nbsp;&nbsp;</b></label></td>
						<td class="centrado"><label for="precio"><b>&nbsp;&nbsp;Precio (sin IVA)&nbsp;&nbsp;</b></label></td>
					</tr>';
				
			echo '<script type="text/javascript">
					var texto1 = "<select name=referencia";
					var texto2 = " id=referencia";
					var texto3= " onchange='.'activa(";
					var texto4 =")'.'>';
					
					//obtemos los artículos de la tabla artículo
					try {
							$query = "SELECT Referencia FROM articulo ORDER BY Referencia ASC";
							$stmt = $dbh->query($query);							
							
							//para cada artículo añadimos una opción
							echo '<option value=null></option>';
							foreach ($stmt as $row)
							{
								echo '<option value='.$row['Referencia'].'';							
								
								echo '>'.$row['Referencia'].'</option>';
							}
					}
					catch(PDOException $e ) {
						// tratamiento del error
						die("Error PDO: ".$e->GetMessage());
					}
						
					echo '</select>&nbsp&nbsp&nbsp&nbsp<td class=centrado><input name=unidades";
					var texto5 = " type=text size=3 maxlength=3 />&nbsp;&nbsp;&nbsp;&nbsp;<input name=comentario";
					var texto6 = " type=text size=10 maxlength=50 />&nbsp;&nbsp;&nbsp;&nbsp;<input name=precio";
					var texto7 = " id=precio";
					var texto8 = " type=text size=6 maxlength=6 disabled=true />";</script>';
				
			echo '				
				</table>
				<div id="articulo1" class="centrado"></div>				
				<table class="tabla-centrada">					
					<tr>
						<td class="centrado"><input name="otro" type="button" value="Otro artículo" class="boton" OnClick="javascript:otroArticulo();" /></td>';
						
			
			echo '	</tr>	
				</table>';
			
			if (!isset($_REQUEST['continuar']))
				echo '
					<div class="centrado">
					<br />
					<label for="observaciones"><b>Observaciones</b></label><br />
					<textarea name="observaciones" rows="5" cols="40"></textarea><br />
					</div>
					<br/>';
			
			echo '
				<div class="centrado">
				<td class="centrado"><input name="darDeAlta" type="submit" value="Dar de alta" class="boton" /></td> 
				</div>
			</form>';
	}

	//si llega información del formulario, la tratamos
	if (isset($_REQUEST['darDeAlta']))
	{		
		//obtenemos los datos del formulario
		$observaciones = $_REQUEST['observaciones'];		
		$cliente = $_REQUEST['cliente'];
		
		//los validamos
		$errores = validaFormulario();
		
		//si no hay errores
		if (!$errores)
		{		
			//inserta venta (con la fecha actual CURrent DATE) si no estamos continuando una venta
			$sql = "INSERT INTO listaboda (Fecha,Observaciones,IdCliente) VALUES (curdate(),'$observaciones',$cliente)";

			try
			{
				//si dio la venta de alta, damos de alta las líneas de venta
				if (isset($_REQUEST['continuar']) || $dbh->exec($sql))
				{
					if (isset($_REQUEST['listaboda']))
						$IdListaBoda = $_REQUEST['listaboda'];
					else
						$IdListaBoda = $dbh->lastInsertId();
					
					$i = 1;
					$errores = '';
					
					while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
					{				
						$referencia = $_REQUEST["referencia$i"];
						$unidades = $_REQUEST["unidades$i"];
						$comentario = $_REQUEST["comentario$i"];
						$precio = $_REQUEST["precio$i"];
						
						if ($referencia != 'null' && $unidades != null)
						{
							if (!esIntangible($referencia))
							{
								$query="SELECT Precio FROM articulo WHERE Referencia='$referencia'";
								$stmt = $dbh->query($query);	
								$row = $stmt -> fetch();
								$precio=$row['Precio'];
							}								
							
							//añadimos la línea de lista boda
							$sql = "INSERT INTO linealistaboda (Referencia, IdListaBoda, Unidades, Precio, Comentario) VALUES ('$referencia', $IdListaBoda, $unidades, $precio, '$comentario')";
							$dbh->exec($sql);
						}
						$i += 1;
					}
					
					if (isset($_REQUEST['continuar']))
						echo '<a href="menuListaBoda.php"><h4>Menú listas de boda</h4></a><p class="parrafoCentrado">La lista de boda se modificó con éxito.<br /><a href="datosListaBoda.php?listaboda='.$IdListaBoda.'">Ver lista de boda.</a></p>';
					else
						echo '<a href="menuListaBoda.php"><h4>Menú listas de boda</h4></a><p class="parrafoCentrado">Lista de boda dada de alta.<br /><a href="datosListaBoda.php?listaboda='.$IdListaBoda.'">Ver lista de boda.</a></p>';
					
					echo $errores;
				}
				else
					echo '<a href="menuListaBoda.php"><h4>Menú listas de boda</h4></a><p class="parrafoCentrado">Los artículos no se pudieron añadir a la lista de boda. Por favor contacte con el administrador.</p>';
			}
			catch(PDOException $e ) 
			{
				die("Error PDO: ".$e->GetMessage());
			}
		}
		//si hay errores de validación
		else 
		{
			echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
			formularioAltaListaBoda();	
			echo '<script type="text/javascript">otroArticulo();</script>';
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioAltaListaBoda();
		echo '<script type="text/javascript">otroArticulo();</script>';
	}
	
	include_once('inc/footer.php');

?>