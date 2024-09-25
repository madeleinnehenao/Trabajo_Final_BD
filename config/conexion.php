<?php

// Cambiar los datos de acuerda a su base de datos (Poner en nombre en $db)
// Si siguió todo el proceso correctamente no deberá combiar ni $host, ni $user, ni $pass

$host = "localhost";
$user = "root";
$pass = "";
$db = "ejemplo";

$conn = new mysqli($host, $user, $pass, $db) or die("Error al conectar a la DB " . mysqli_error($link));