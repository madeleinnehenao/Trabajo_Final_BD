<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3">Consulta 1</h1>

<p class="mt-3">
    Debe mostrar el documento de identificación y el nombre de 
    los bibliotecarios que más ejemplares recibieron y en caso de empate 
    deberá mostrar a todos los bibliotecarios que empataron.
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL para obtener los bibliotecarios que más ejemplares han recibido. 
// Contamos la cantidad de ejemplares recibidos por cada bibliotecario y 
// los ordenamos de mayor a menor. En caso de empate, mostramos todos los bibliotecarios.

$query = "
SELECT bibliotecario.documento_de_identificacion AS cedula,
bibliotecario.nombre_completo AS nombre, COUNT(ejemplar.identificador) AS total_ejemplares
FROM bibliotecario
JOIN ejemplar ON bibliotecario.documento_de_identificacion = ejemplar.receptor
GROUP BY bibliotecario.documento_de_identificacion, bibliotecario.nombre_completo
HAVING total_ejemplares = (
    SELECT MAX(total_ejemplares)
    FROM (
        SELECT COUNT(ejemplar.identificador) AS total_ejemplares
        FROM bibliotecario
        JOIN ejemplar ON bibliotecario.documento_de_identificacion = ejemplar.receptor
        GROUP BY bibliotecario.documento_de_identificacion
    ) AS subquery
)
ORDER BY total_ejemplares DESC;
";

// Ejecutar la consulta
$resultadoC1 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if ($resultadoC1 && $resultadoC1->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Cédula</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Ejemplares Recibidos</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoC1 as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["cedula"]; ?></td>
                <td class="text-center"><?= $fila["nombre"]; ?></td>
                <td class="text-center"><?= $fila["total_ejemplares"]; ?></td>
            </tr>

            <?php
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<!-- Mensaje de error si no hay resultados -->
<?php
else:
?>

<div class="alert alert-danger text-center mt-5">
    No se encontraron resultados para esta consulta
</div>

<?php
endif;

include "../includes/footer.php";
?>