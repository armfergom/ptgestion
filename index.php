<?php 
    include_once('inc/header.php');
?>
<table class="tabla-centrada">
<tr>
	<td><a href="menuArticulos.php"><button type="button" class="botonMP" id="botonArticulo" onmouseover="aclaracionArticulos()" onmouseout="aclaracionArticulos2()">Artículos</button></a></td>
	<td><a href="menuProveedores.php"><button type="button" class="botonMP" id="botonProveedor" onmouseover="aclaracionProveedores()" onmouseout="aclaracionProveedores2()">Proveedores</button></a></td>
	<td><a href="menuCompras.php"><button type="button" class="botonMP" id="botonCompra" onmouseover="aclaracionCompras()" onmouseout="aclaracionCompras2()">Compras</button></a></td>
	<td><a href="menuClientes.php"><button type="button" class="botonMP" id="botonCliente" onmouseover="aclaracionClientes()" onmouseout="aclaracionClientes2()">Clientes</button></a></td>
</tr>
</table>
<table class="tabla-centrada">
<tr>
	<td><a href="menuVentas.php"><button type="button" class="botonMP" id="botonVenta" onmouseover="aclaracionVentas()" onmouseout="aclaracionVentas2()">Ventas</button></a></td>
	<td><a href="menuPresupuestos.php"><button type="button" class="botonMP" id="botonPresupuesto" onmouseover="aclaracionPresupuestos()" onmouseout="aclaracionPresupuestos2()">Presupuestos</button></a></td>
	<td><a href="menuListaBoda.php"><button type="button" class="botonMP" id="botonLista" onmouseover="aclaracionListas()" onmouseout="aclaracionListas2()">Listas de boda</button></a></td>
</tr>
</table>

<div id="aclaracion"><p><br/></p></div>

<?php 
    include_once('inc/footer.php'); 
?>