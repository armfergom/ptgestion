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
				var cadenaDiv = "'.$cadenaDiv1.'"+artConNumMasUno+"'.$cadenaDiv2.'"; 	';
	if ($tipo == 'factura')
		echo 'document.getElementById("articulo" + numero).innerHTML += texto1 + numero + texto2 + numero + texto3 + numero + texto4 + numero + texto5 + numero + texto6 + numero + texto7 + numero + texto8 + numero + texto9 + cadenaDiv;';
	else
		echo 'document.getElementById("articulo" + numero).innerHTML += texto1 + numero + texto2 + numero + texto3 + numero + texto4 + numero + texto5 + numero + texto6 + numero + texto7 + numero + texto8 + cadenaDiv;';

	echo '		numero += 1;
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
					if (!preg_match('/^(-)?[1-9][0-9]*/',$unidades))
						$errores .= "<li>Unidades debe ser un número entero.</li>";
					else 
					{
						if (!esIntangible($referencia))
						{
							//ver que tenemos al menos $unidades unidades del artículo $referencia
							$query = "SELECT Unidades FROM articulo WHERE Referencia = '$referencia'";
							$stmt = $dbh -> query($query);
							$row = $stmt -> fetch();
							
							if(preg_match('/^[1-9][0-9]*\.[0-9]{0,2}$/',$unidades))
								$errores .= "<li>Unidades no puede ser un número decimal excepto en el caso de artículos intangibles.</li>";

							if ($row['Unidades'] < $unidades)
								$errores .= "<li>No se pueden vender $unidades unidades del artículo <a href=\"datosArticulo.php?articulo=$referencia\">$referencia</a> porque no se dispone de tantas en stock.</li>";
						}
						else
						{
							//si es intangible hay que controlar que se haya introducido el precio
							$precio = $_REQUEST["precio$i"];
							if ($precio == null)
								$errores .= '<li>El precio es un campo obligatorio para artículos intangibles.</li>';
							else if (!preg_match('/^-?[0-9][0-9]*(\.[0-9]{1,2})?$/',$precio))
								$errores .= "<li>El precio se introdujo mal.</li>";
						}
						
						$articulos += 1;
					}
				}
			}
			
			//////////////////
						
			$i += 1;
		}
		
		if ($articulos == 0)
			$errores .= "<li>Una venta debe tener al menos un artículo.</li>";
			
			
		if ($_REQUEST['antiguedad']=="null")
			$errores .= "<li>Es obligatorio señalar si se trata de una venta de antigüedades o no.</li>";

		return $errores;
	}

	//muestra el formulario de altas de ventas
	function formularioAltaVenta()
	{
		global $dbh, $tipo;
		
		echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a>';
		
		if ($tipo == 'ticket')
			echo '<h4>Venta por ticket</h4>';
		else if ($tipo == 'factura')
			echo '<h4>Venta por factura</h4>';
			
		echo '<form method="post"> 	';
		
		if ($tipo == 'factura' && !isset($_REQUEST['continuar']))
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
						<td class="centrado"><label for="precio"><b>&nbsp;&nbsp;Precio (sin IVA)&nbsp;&nbsp;</b></label></td>';
			if ($tipo == 'factura')
				echo '<td class="centrado"><label for="descuento"><b>&nbsp;&nbsp;Descuento&nbsp;&nbsp;</b></label></td>';
			echo '
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
					var texto5 = " type=text size=3 />&nbsp;&nbsp;&nbsp;&nbsp;<input name=comentario";
					var texto6 = " type=text size=10 maxlength=50 />&nbsp;&nbsp;&nbsp;&nbsp;<input name=precio";
					var texto7 = " id=precio";';
					
					if ($tipo == 'ticket')
					{
						echo '
						var texto8 = " type=text size=6 maxlength=6 disabled=true />"</script>;';
					}
					else
					{
						echo '
						var texto8 = " type=text size=6 maxlength=6 disabled=true />&nbsp;&nbsp;&nbsp;&nbsp;<input name=descuento";
						var texto9 = " type=text size=3 maxlength=3 />";</script>';
					}

				
			echo '				
				</table>
				<div id="articulo1" class="centrado"></div>				
				<table class="tabla-centrada">					
					<tr>
						<td class="centrado"><input name="otro" type="button" value="Otro artículo" class="boton" OnClick="javascript:otroArticulo();" /></td>';
						
			
			echo '	</tr>	
				</table>';
			
			if (!isset($_REQUEST['continuar']))
			{
				echo '
					<div class="centrado">
					<br />
					<label for="observaciones"><b>Observaciones</b></label><br />
					<textarea name="observaciones" rows="5" cols="40"></textarea><br />
					<label for="formaPago"><b>Forma de pago</b></label><br />
					<select name="formaPago">
					<option value="null"></option>
					<option value="Visa">Visa</option>
					<option value="Efectivo">Efectivo</option>
					<option value="Transferencia">Transferencia</option>
					<option value="Talón">Talón</option>
					<option value="De su lista de boda">De su lista de boda</option>
					</select>
					<br />
					<br />
					<label for="antiguedad"><b>Antigüedad</b></label>
					<select name="antiguedad">
					<option value="null"></option>
					<option value="No">No</option>
					<option value="Si">Sí</option>
					</select>';
				
				if ($tipo == 'ticket')
				{
					echo '
						<br />
						<br />
						<label for="descuento"><b>Descuento (%):</b></label>
						<input name="descuento" type="text" value="0" maxlength="3" size="3"/>
						';
				}
			}
			
			echo '
				</div>
				<br/>
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
		$formaPago = $_REQUEST['formaPago'];
		if (isset($_REQUEST['cliente']))
			$cliente = $_REQUEST['cliente'];
		$antiguedad = $_REQUEST['antiguedad'];
		$descuento = (int)$_REQUEST['descuento']; 
		
		//los validamos
		$errores = validaFormulario();
		
		//si no hay errores
		if (!$errores)
		{		
			//inserta venta (con la fecha actual CURrent DATE) si no estamos continuando una venta
			if (isset($_REQUEST['cliente'])){
				if ($_REQUEST['formaPago']== "null")
					$sql = "INSERT INTO venta (FechaVenta, FormaPago, Observaciones, IdCliente, Antiguedad,Descuento) VALUES (curdate(), '$formaPago','$observaciones', $cliente, '$antiguedad',$descuento)";
				else
					$sql = "INSERT INTO venta (FechaVenta,FechaCobro, FormaPago, Observaciones, IdCliente, Antiguedad,Descuento) VALUES (curdate(),curdate(), '$formaPago','$observaciones', $cliente, '$antiguedad',$descuento)";
			}
			else{
				if ($_REQUEST['formaPago']== "null")
					$sql = "INSERT INTO venta (FechaVenta, FormaPago, Observaciones, Antiguedad,Descuento) VALUES (curdate(), '$formaPago','$observaciones', '$antiguedad',$descuento)";
				else
					$sql = "INSERT INTO venta (FechaVenta,FechaCobro, FormaPago, Observaciones, Antiguedad,Descuento) VALUES (curdate(),curdate(), '$formaPago','$observaciones', '$antiguedad',$descuento)";			
			}
			try
			{
				//si dio la venta de alta, damos de alta las líneas de venta
				if (isset($_REQUEST['continuar']) || $dbh->exec($sql))
				{
					if (isset($_REQUEST['venta']))
						$IdVenta = $_REQUEST['venta'];
					else
						$IdVenta = $dbh->lastInsertId();
					$i = 1;
					$errores = '';
					
					while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
					{				
						$referencia = $_REQUEST["referencia$i"];
						$unidades = $_REQUEST["unidades$i"];
						$comentario = $_REQUEST["comentario$i"];
						$precio = $_REQUEST["precio$i"];
						$descuento = (int)$_REQUEST["descuento$i"];
						
						if ($referencia != 'null' && $unidades != null)
						{
							if (!esIntangible($referencia))
							{
								$query="SELECT Precio FROM articulo WHERE Referencia='$referencia'";
								$stmt = $dbh->query($query);	
								$row = $stmt -> fetch();
								$precio=$row['Precio'];
							}								
							
							//añadimos la línea de venta
							$sql = "INSERT INTO lineaventa (Referencia, IdVenta, Unidades, Precio, Comentario, Descuento) VALUES ('$referencia', $IdVenta, $unidades, $precio, '$comentario', $descuento)";
							
							if (!($dbh->exec($sql)))
								$errores .= "<p>El artículo $referencia ya pertenece a la venta. Si desea modificar su número de unidades, elimínelo de la venta y añádalo de nuevo.</p>";							
							
							//decrementamos las unidades de ese artículo si no es intangible
							if (!esIntangible($referencia))
							{
								$query = "SELECT Unidades FROM articulo WHERE Referencia = '$referencia'";
								$stmt = $dbh -> query($query);
								$row = $stmt -> fetch();

								$unidades = $row['Unidades'] - $unidades;
								
								$sql = "UPDATE articulo SET Unidades = $unidades WHERE Referencia = '$referencia'";
								$dbh -> exec($sql);
							}
						}
						$i += 1;
					}
					
					if (isset($_REQUEST['continuar']))
						echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a><p class="parrafoCentrado">La venta se modificó con éxito.<br /><a href="datosVenta.php?venta='.$IdVenta.'">Ver venta.</a></p>';
					else
						echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a><p class="parrafoCentrado">Venta dada de alta.<br /><a href="datosVenta.php?venta='.$IdVenta.'">Ver venta.</a></p>';
					
					echo $errores;
				}
				else
					echo '<a href="menuVentas.php"><h4>Menú ventas</h4></a><p class="parrafoCentrado">Los artículos no se pudieron añadir a la venta. Por favor contacte con el administrador.</p>';
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
			formularioAltaVenta();	
			echo '<script type="text/javascript">otroArticulo();</script>';
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioAltaVenta();
		echo '<script type="text/javascript">otroArticulo();</script>';
	}
	
	include_once('inc/footer.php');

?>