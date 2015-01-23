<?php
	
	include_once('inc/header.php');
	if(isset($_REQUEST['cliente']))
	{
		$Id = $_REQUEST['cliente'];
	
		//obtemos los atributos de ese cliente
		try 
		{
			$query = "SELECT NIF,Nombre,Apellidos,Titulo,Observaciones,Direccion,Localidad,Provincia,Pais,CP,Tlf1,Tlf2,FechaAlta,Email FROM cliente WHERE IdCliente=$Id";
			$stmt = $dbh->query($query);							
			
			//para ese cliente añadimos sus propiedades
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				
				echo '
				<a href="menuClientes.php"><h4>Menú clientes</h4></a>
					<table class="tabla-centrada">
					<tr>
					<td><a href="modificarCliente.php?cliente='.$Id.'"><button type="button" class="boton" id="botonModificarCli">Modificar</button></td>
					<td><a href="borrarCliente.php?cliente='.$Id.'"><button type="button" class="boton" id="botonBorrarCli" >Borrar</button></a></td>
					</tr>
					</table>
					<br />
				';
				
				echo '<table class="tabla-listado">';
			
				//cabecera de la tabla
				echo '<tr class="filaImpar"><td>Titulo</td><td>Nombre</td><td>Apellidos</td><td>NIF</td><td>Direccion</td><td>CP</td><td>Localidad</td><td>Provincia</td><td>Pais</td></tr>';
				
				echo '<tr class="filaPar">';
				
				if ($row['Titulo']!=null)
					echo '<td>'.$row['Titulo'].'</td>';
				else
					echo '<td>---</td>';	
				
				if ($row['Nombre']!=null)
					echo '<td>'.$row['Nombre'].'</td>';
				else
					echo '<td>---</td>';
				
				if ($row['Apellidos']!=null)
					echo '<td>'.$row['Apellidos'].'</td>';
				else
					echo '<td>---</td>';				
				
				if ($row['NIF']!=null)
					echo '<td>'.$row['NIF'].'</td>';
				else
					echo '<td>---</td>';
				
				if ($row['Direccion'] != null)
					echo '<td>'.$row['Direccion'].'</td>';
				else
					echo '<td>---</td>';
				
				if ($row['CP']!=0)
					echo '<td>'.$row['CP'].'</td>';
				else
					echo '<td>---</td>';
				
				if ($row['Localidad'] != null)
					echo '<td>'.$row['Localidad'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Provincia'] != null)
					echo '<td>'.$row['Provincia'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Pais'] != null)
					echo '<td>'.$row['Pais'].'</td>';
				else
					echo '<td>---</td>';
								
				echo '</tr>';
				
				echo '</table>';
				
				echo '<br/>';
				
				echo '<table class="tabla-listado">';
			
				echo '<tr class="filaImpar"><td>Teléfono1</td><td>Teléfono2</td><td>Email</td><td>Fecha de alta</td></tr>';
				
				echo '<tr class="filaPar">';
				
				if($row['Tlf1']!=0)
					echo '<td>'.$row['Tlf1'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Tlf1'] !=0)
					echo '<td>'.$row['Tlf2'].'</td>';
				else
					echo '<td>---</td>';
					
				if ($row['Email'] !=null)
					echo '<td>'.$row['Email'].'</td>';
				else
					echo '<td>---</td>';
				
				echo '<td>'.$row['FechaAlta'].'</td>';
				
				echo '</tr>';
				
				echo '</table>';
				
				if ($row['Observaciones'] != null)
					echo '<p class=parrafoCentrado>Observaciones: '.$row['Observaciones'].'';
			
			}
		}
		catch(PDOException $e ) {
			// tratamiento del error
			die("Error PDO: ".$e->GetMessage());
		}
	}

	include_once('inc/footer.php');

?>