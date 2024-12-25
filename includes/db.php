<?php

// Datos de conexion a la base de datos

$host = "localhost"; // servidor de base de datos 
$dbname = "CdeABeltran24"; // nombre base de datos en prod: CdeABeltran24 || enloc: codaccesobeltran
$username = "root"; // usuario de la base de datos
$password = "NnKm9u.YgGGc"; // contraseña de la base de datos enprod: NnKm9u.YgGGc || enloc: void

// Creo la conexion
$conn = new mysqli($host, $username, $password, $dbname);

// Verifico la conexion
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
