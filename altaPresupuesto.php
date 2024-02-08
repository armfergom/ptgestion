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
				document.getElementById("articulo" + numero).innerHTML += texto1 + numero + texto2 + numero + texto3 + numero + texto4 + numero + texto5 + numero + texto6 + numero + texto7 + numero + texto8 + numero + texto9 + numero + texto10 + numero + texto11 + cadenaDiv;								
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
			$descuento = $_REQUEST["descuento$i"];
			$precio = $_REQUEST["precio$i"];
					
			//////////////////
			if ($precio == null && esIntangible($referencia))
				$errores .= '<li>El precio es un campo obligatorio para art�culos intangibles.</li>';
			
			if ($descuento == null)
				$errores .= '<li>El descuento es un campo obligatorio.</li>';
				
			if ($referencia == 'null' xor $unidades == null)
				$errores .= '<li>La referencia y las unidades son campos obligatorios.</li>';
			else
			{
				if ($unidades != null)
				{
					//ver que las unidades sea un n�mero positivo
					if (!preg_match('/^[1-9]/',$unidades))
						$errores .= "<li>Unidades debe ser un n�mero positivo.</li>";
					else {	
						if (!esIntangible($referencia))
							{
								//ver que tenemos al menos $unidades unidades del art�culo $referencia
								$query = "SELECT Unidades FROM articulo WHERE Referencia = '$referencia'";
								$stmt = $dbh -> query($query);
								$row = $stmt -> fetch();
								
								if(preg_match('/^[1-9][0-9]*\.[0-9]{0,2}$/',$unidades))
									$errores .= "<li>Unidades no puede ser un n�mero decimal excepto en el caso de art�culos intangibles.</li>";

							}
							else
							{
								//si es intangible hay que controlar que se haya introducido el precio
								$precio = $_REQUEST["precio$i"];
								if ($precio == null)
									$errores .= '<li>El precio es un campo obligatorio para art�culos intangibles.</li>';
								else if (!preg_match('/^-?[1-9][0-9]*(\.[0-9]{1,2})?$/',$precio))
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
			$errores .= "<li>Un presupuesto debe tener al menos un art�culo.</li>";

		return $errores;
	}

	//muestra el formulario de altas de presupuestos
	function formularioAltaPresupuesto()
	{
		global $dbh, $tipo;
		
		echo '<a href="menuPresupuestos.php"><h4>Men� presupuestos</h4></a>';
		
		echo '<h4>Alta de presupuesto</h4>';
			
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
							
							//para cada cliente a�adimos una opci�n
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
					<td class="centrado"><label for="descuento"><b>&nbsp;&nbsp;Descuento&nbsp;&nbsp;</b></label></td>
					<td class="centrado"><label for="capitulo"><b>&nbsp;&nbsp;Capitulo&nbsp;&nbsp;</b></label></td>
					<td class="centrado"><label for="subcapitulo"><b>&nbsp;&nbsp;Subcapitulo&nbsp;&nbsp;</b></label></td>
				</tr>';
			
		echo '<script type="text/javascript">
				var texto1 = "<select name=referencia";
				var texto2 = " id=referencia";
				var texto3= " onchange='.'activa(";
				var texto4 =")'.'>';
				
				//obtemos los art�culos de la tabla art�culo
				try {
						$query = "SELECT Referencia FROM articulo ORDER BY Referencia ASC";
						$stmt = $dbh->query($query);							
						
						//para cada art�culo a�adimos una opci�n
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
					
				echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=unidades";
				var texto5 = " type=text size=3 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=comentario";
				var texto6 = " type=text size=10 maxlength=50 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=precio";
				var texto7 = " id=precio";
				var texto8 = " type=text size=6 maxlength=6 disabled=true />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=descuento";
				var texto9 = " type=text size=3 maxlength=3 value=0 />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select style=\"width: 60px\" name=capitulo";
				var texto10 = ">';
				
				
				//obtemos los cap�tulos de la tabla cap�tulo
				try {
						$query = "SELECT IdCapitulo, Nombre FROM capitulo";
						$stmt = $dbh->query($query);							
						
						//para cada cap�tulo a�adimos una opci�n
						foreach ($stmt as $row)
						{
							echo '<option value='.$row['IdCapitulo'].'';							
							
							echo '>'.$row['Nombre'].'</option>';
						}
				}
				catch(PDOException $e ) {
					// tratamiento del error
					die("Error PDO: ".$e->GetMessage());
				}
				
				echo '</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select style=\"width: 60px\" name=subcapitulo";
				var texto11 = ">';
				
				//obtemos los subcap�tulos de la tabla subcap�tulo
				try {
						$query = "SELECT IdSubcapitulo, Nombre FROM subcapitulo";
						$stmt = $dbh->query($query);							
						
						//para cada subcap�tulo a�adimos una opci�n
						foreach ($stmt as $row)
						{
							echo '<option value='.$row['IdSubcapitulo'].'';							
							
							echo '>'.$row['Nombre'].'</option>';
						}
				}
				catch(PDOException $e ) {
					// tratamiento del error
					die("Error PDO: ".$e->GetMessage());
				}
				
				echo '</select>"</script>';
			
		echo '				
			</table>
			<div id="articulo1" class="centrado"></div>				
			<table class="tabla-centrada">					
				<tr>
					<td class="centrado"><input name="otro" type="button" value="Otro art�culo" class="boton" OnClick="javascript:otroArticulo();" /></td>
		 ';
		
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

	//si llega informaci�n del formulario, la tratamos
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
			//inserta presupuesto (con la fecha actual CURrent DATE) si no estamos continuando un presupuesto
			$sql = "INSERT INTO presupuesto (Fecha, Observaciones, IdCliente) VALUES (curdate(), '$observaciones', $cliente)";

			try
			{
				//si dio el presupuesto de alta, damos de alta las l�neas del presupuesto
				if (isset($_REQUEST['continuar']) || $dbh->exec($sql))
				{
					if (isset($_REQUEST['presupuesto']))
						$IdPresupuesto = $_REQUEST['presupuesto'];
					else
						$IdPresupuesto = $dbh->lastInsertId();

					$sql = "INSERT INTO numeropresupuesto (NumeroPresupuesto, IdPresupuesto, AnoPresupuesto) VALUES ($IdPresupuesto, $IdPresupuesto, YEAR(curdate()))";
					$dbh->exec($sql);

					$i = 1;
					$errores = '';
					
					while(isset($_REQUEST["referencia$i"]) && isset($_REQUEST["unidades$i"]))
					{				
						$referencia = $_REQUEST["referencia$i"];
						$unidades = $_REQUEST["unidades$i"];
						$comentario = $_REQUEST["comentario$i"];
						$precio = $_REQUEST["precio$i"];
						$descuento = $_REQUEST["descuento$i"];
						$capitulo = $_REQUEST["capitulo$i"];
						$subcapitulo = $_REQUEST["subcapitulo$i"];
						
						if ($referencia != 'null' && $unidades != null)
						{
							//si no es intangible cogemos el precio de la tabla, si lo es ya lo habr� introducido el usuario
							if (!esIntangible($referencia))
							{
								$query="SELECT Precio FROM articulo WHERE Referencia='$referencia'";
								$stmt = $dbh->query($query);	
								$row = $stmt -> fetch();
								$precio=$row['Precio'];
							}								
							
							//a�adimos la l�nea de presupuesto
							$sql = "INSERT INTO lineapresupuesto (Referencia, IdPresupuesto, Unidades, Precio, Descuento, Comentario, IdCapitulo, IdSubcapitulo) VALUES ('$referencia', $IdPresupuesto, $unidades, $precio, $descuento, '$comentario', $capitulo, $subcapitulo)";
							
							if (!($dbh->exec($sql)))
								$errores .= "<p>El art�culo $referencia ya pertenece al presupuesto. Si desea modificar su n�mero de unidades, elim�nelo del presupuesto y a��dalo de nuevo.</p>";							
						}
						$i += 1;
					}
					
					if (isset($_REQUEST['continuar']))
						echo '<a href="menuPresupuestos.php"><h4>Men� presupuestos</h4></a><p class="parrafoCentrado">El presupuestos se modific� con �xito.<br /><a href="datosPresupuesto.php?presupuesto='.$IdPresupuesto.'">Ver presupuesto.</a></p>';
					else
						echo '<a href="menuPresupuestos.php"><h4>Men� presupuestos</h4></a><p class="parrafoCentrado">Presupuesto dada de alta.<br /><a href="datosPresupuesto.php?presupuesto='.$IdPresupuesto.'">Ver presupuesto.</a></p>';
					
					echo $errores;
				}
				else
					echo '<a href="menuPresupuestos.php"><h4>Men� presupuestos</h4></a><p class="parrafoCentrado">Los art�culos no se pudieron a�adir a la presupuesto. Por favor contacte con el administrador.</p>';
			}
			catch(PDOException $e ) 
			{
				die("Error PDO: ".$e->GetMessage());
			}
		}
		//si hay errores de validaci�n
		else 
		{
			echo '<div class="erroresFormularios"><ul>'.$errores.'</ul></div>';
			formularioAltaPresupuesto();	
			echo '<script type="text/javascript">otroArticulo();</script>';
		}
	} 
	//si no, lo mostramos
	else 
	{
		formularioAltaPresupuesto();
		echo '<script type="text/javascript">otroArticulo();</script>';
	}
	
	include_once('inc/footer.php');

?>
	