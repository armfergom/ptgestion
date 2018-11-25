# Instalación

- Instalar la version 1.6.8 de XAMPP para Windows
- Marcar las opciones `Install Apache as a Service` y `Install MySQL as a service` durante la instalación
- Descarar el codigo del programa de https://github.com/armfergom/ptgestion
- Copiar la carpeta `ptgestion` con el código del programa en C://xampp/htdocs
- En este punto y si aun no tenemos la base de datos creada, si accedemos a http://localhost/ptgestion podremos acceder al programa pero veremos un error que nos dice que no podemos acceder a la base de datos. 

# Instalar la base de datos

A la hora de instalar una base de datos, tenemos dos opciones. O instalarla limpia, sin ningún dato o usar una copia de seguridad.

## Instalar una base de datos limpia, sin datos

- Acceder a http://localhost/phpmyadmin
- Hacer click en el icono de `SQL` en la parte izquierda de la pantalla
- Seleccionar `Importar archivos`
- Seleccionar el archivo `C://xampp/htdocs/ptgestion/PTGestion.sql`
- Hacer click en `Continuar` para crear la base de datos

## Instalar una base de datos desde una copia de seguridad

- Acceder a http://localhost/phpmyadmin
- Hacer click en la base de datos `ptgestion` en el menu izquierdo de la pantalla
- En las pestañas de la parte supuerior de la pantalla, hacer click en `Importar`
- Hacer click en `Seleccionar archivo`
- Seleccionar el archivo `.sql` que contiene la copia de seguridad
- Hacer click en `Continuar`

# Crear una copia de seguridad de la base de datos:

- Acceder a http://localhost/phpmyadmin
- Hacer click en la base de datos `ptgestion` en el menu izquierdo de la pantalla
- En las pestañas de la parte supuerior de la pantalla, hacer click en `Exportar`
- Marcar el checkbox `Añada DROP TABLE/VIEW/PROCEDURE/FUNCTION`
- Marcar el checkbox `Enviar (genera un archivo descargable)`
- Dar un nombre a la copia de seguridad en el campo `Plantilla del nombre del archivo`
- Hacer click en `Continuar` y esperar, guardar el archivo `.sql` y esperar a que se descargue por completo