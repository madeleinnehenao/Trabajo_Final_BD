<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3">Consulta 2</h1>

<p class="mt-3">
    Esta consulta muestra el identificador y nombre de los tres roles que tienen 
    mayor suma de páginas revisadas por bibliotecarios. En caso de empate, se 
    mostrarán los roles que empataron.
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL para obtener los 3 roles con mayor suma de páginas revisadas
$query = "
SELECT rol.identificador, rol.nombre, SUM(ejemplar.numero_de_paginas) AS total_paginas
FROM rol
JOIN bibliotecario ON rol.identificador = bibliotecario.rol
JOIN ejemplar ON bibliotecario.documento_de_identificacion = ejemplar.revisor
GROUP BY rol.identificador, rol.nombre
HAVING total_paginas = (
    SELECT MAX(total_paginas)
    FROM (
        SELECT SUM(ejemplar.numero_de_paginas) AS total_paginas
        FROM rol
        JOIN bibliotecario ON rol.identificador = bibliotecario.rol
        JOIN ejemplar ON bibliotecario.documento_de_identificacion = ejemplar.revisor
        GROUP BY rol.identificador
    ) AS subquery
)
ORDER BY total_paginas DESC
LIMIT 3;
";

// Ejecutar la consulta
$resultadoC2 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if($resultadoC2 and $resultadoC2->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Identificador</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Total Páginas Revisadas</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoC2 as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["cedula"]; ?></td>
                <td class="text-center"><?= $fila["nombre"]; ?></td>
                <td class="text-center"><?= $fila["total_paginas"]; ?></td>
            </tr>

            <?php
            // Cerrar los estructuras de control
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