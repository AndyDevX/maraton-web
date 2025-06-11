<?php

// Incluir el archivo de conexión
include('conexion.php');

// Función para obtener todos los ids de una tabla
function obtenerIdsDeTabla($tabla) {
    global $conectar;

    // Obtener artículo desde sesión
    $articulo = $_SESSION['articulo'] ?? '';

    // Verificar la conexión
    if ($conectar->connect_error) {
        die("Error de conexión: " . $conectar->connect_error);
    }

    // Convertir el string de artículos a array
    $articulos_array = array_map('trim', explode(',', $articulo));

    // Escapar cada artículo
    $articulos_escapados = array_map(function($item) use ($conectar) {
        return "'" . $conectar->real_escape_string($item) . "'";
    }, $articulos_array);

    // Construir cláusula IN
    $articulos_sql = implode(',', $articulos_escapados);

    // Consulta segura
    $sql = "SELECT id FROM `$tabla` WHERE articulo IN ($articulos_sql)";
    $result = $conectar->query($sql);

    $ids = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
        }
    }

    return $ids;
}

function seleccionarValores($datos, $valorRepetido, $vecesRepetido, $totalValores) {
    // Asegurarnos de que el valor a repetir esté en el array
    if (!in_array($valorRepetido, $datos)) {
        return "El valor a repetir no está en el grupo de datos.";
    }

    // Filtrar el array para excluir el valor que se repetirá
    $datosFiltrados = array_filter($datos, function($valor) use ($valorRepetido) {
        return $valor !== $valorRepetido;
    });

    // Verificar que hay suficientes valores únicos para seleccionar
    if (count($datosFiltrados) < ($totalValores - $vecesRepetido)) {
        return "No hay suficientes valores únicos para seleccionar.";
    }

    // Seleccionar valores únicos al azar
    $valoresSeleccionados = array_rand(array_flip($datosFiltrados), $totalValores - $vecesRepetido);

    // Convertir a array si solo se selecciona un valor
    if (!is_array($valoresSeleccionados)) {
        $valoresSeleccionados = [$valoresSeleccionados];
    }

    // Añadir el valor repetido las veces especificadas
    for ($i = 0; $i < $vecesRepetido; $i++) {
        $valoresSeleccionados[] = $valorRepetido;
    }

    // Mezclar el array para que el valor repetido no esté siempre al final
    shuffle($valoresSeleccionados);

    return $valoresSeleccionados;
}
function generarPregunta($id, $tabla, $columna_texto) {
    global $conectar;

    // Verificar que la conexión a la base de datos esté activa
    if (!$conectar || $conectar->connect_error) {
        die("Error de conexión a la base de datos.");
    }

    // Verificar que el ID sea válido
    if (!is_numeric($id) || $id <= 0) {
        return null; // Retornar null si el ID no es válido
    }

    // Consulta a la base de datos para obtener el texto
    $sql = "SELECT $columna_texto FROM $tabla WHERE id = $id";
    $result = $conectar->query($sql);

    // Verificar si la consulta fue exitosa
    if (!$result) {
        die("Error en la consulta SQL: " . $conectar->error);
    }

    // Verificar si se obtuvieron resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row[$columna_texto]; // Retornar el texto
    } else {
        return null; // Retornar null si no se encuentra el registro
    }
}

function obtenerDatoPorID($id, $tabla, $columna) {
    include('conexion.php'); // Asegúrate de tener aquí tu conexión

    $id = intval($id); // Sanear el ID
    //$columna = mysqli_real_escape_string($conexion, $columna);
    //$tabla = mysqli_real_escape_string($conexion, $tabla);
    $sql = "SELECT `$columna` FROM `$tabla` WHERE id = $id LIMIT 1";
    $resultado = mysqli_query($conectar, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        return $fila[$columna];
    } else {
        return null;
    }
}




?>