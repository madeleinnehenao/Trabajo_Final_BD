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
WITH EjemplaresContados AS (
    SELECT 
        b.documento_de_identificacion, 
        b.nombre_completo,
        COUNT(e.identificador) AS total_ejemplares
    FROM 
        bibliotecario b
    JOIN 
        ejemplar e ON b.documento_de_identificacion = e.receptor
    GROUP BY 
        b.documento_de_identificacion, b.nombre_completo
),
RankedBibliotecarios AS (
    SELECT 
        documento_de_identificacion,
        nombre_completo,
        total_ejemplares,
        RANK() OVER (ORDER BY total_ejemplares DESC) AS ranking
    FROM 
        EjemplaresContados
)

SELECT 
    documento_de_identificacion, 
    nombre_completo,
    total_ejemplares  -- Se incluye el total de ejemplares recibidos
FROM 
    RankedBibliotecarios
WHERE 
    ranking <= 3
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
                <th scope="col" class="text-center">Documento de Identificación</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Total Ejemplares Recibidos</th> <!-- Se agrega la columna para total de ejemplares -->
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
                <td class="text-center"><?= $fila["documento_de_identificacion"]; ?></td>
                <td class="text-center"><?= $fila["nombre_completo"]; ?></td>
                <td class="text-center"><?= $fila["total_ejemplares"]; ?></td> <!-- Mostrar total de ejemplares -->
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
