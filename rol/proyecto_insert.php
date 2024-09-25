<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario. Cada input se identifica con su "name"
$codigo = $_POST["codigo"];
$fechacreacion = $_POST["fechacreacion"];
$valor = $_POST["valor"];
$cliente = $_POST["cliente"];
$empresa = $_POST["empresa"];

// Query SQL a la BD. Si tienen que hacer comprobaciones, hacerlas acá (Generar una query diferente para casos especiales)
$query = "INSERT INTO `proyecto`(`codigo`,`fechacreacion`, `valor`, `cliente`, `empresa`) VALUES ('$codigo', '$fechacreacion', '$valor', '$cliente', '$empresa')";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma pagina
if($result):
    // Si fue exitosa, redirigirse de nuevo a la página de la entidad
	header("Location: proyecto.php");
else:
	echo "Ha ocurrido un error al crear la persona";
endif;

mysqli_close($conn);