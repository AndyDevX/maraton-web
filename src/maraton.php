<?php
// Iniciar la sesión para mantener el estado
session_start();

include('maraton_funciones.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tema'])) {
    $_SESSION['tema'] = $_POST['tema'] ?? '';
    $_SESSION['articulo'] = $_POST['articulo'] ?? '';
    $_SESSION['tipo'] = $_POST['tipo_pregunta'] ?? '';
    $_SESSION['cantidad'] = $_POST['cantidad'] ?? '';
    $_SESSION['mostrar_botones'] = true;
    $_SESSION['esperando_inicio'] = true;
    header('Location: maraton.php');
    exit;
}

$tema = $_SESSION['tema'] ?? '';
$articulo = $_SESSION['articulo'] ?? '';
$tipo = $_SESSION['tipo'] ?? '';
$cantidad = $_SESSION['cantidad'] ?? '';

$tabla = $tema;
$ids = obtenerIdsDeTabla($tabla);

if (is_array($ids) && !empty($ids)) {
    $datos1 = $ids;

    // Ajustar la cantidad solicitada si es "Todos" o si excede el total disponible
    if ($cantidad === 'Todos' || intval($cantidad) > count($ids)) {
        $totalValores = count($ids);
    } else {
        $totalValores = intval($cantidad);
    }

    if (!isset($_SESSION['valores_seleccionados'])) {
        $_SESSION['valores_seleccionados'] = [];
    }

    // Reiniciar selección manualmente
    if (isset($_POST['reiniciar_seleccion'])) {
        unset($_SESSION['valores_seleccionados']);
        unset($_SESSION['valor_actual']);
        unset($_SESSION['opciones']);
        unset($_SESSION['rango_correcto']);
        $_SESSION['esperando_inicio'] = true;
        echo "<p>Selección reiniciada.</p>";
    }
/* echo '<pre>';
var_dump($ids);
echo '</pre>';*/
//echo $totalValores;
    // Mostrar botón "Iniciar" si se está esperando
    if (isset($_SESSION['esperando_inicio']) && $_SESSION['esperando_inicio']) {
        if (isset($_POST['seleccionar_valor'])) {
            unset($_SESSION['esperando_inicio']);
            unset($_SESSION['valores_seleccionados']);
            unset($_SESSION['valor_actual']);
            unset($_SESSION['opciones']);
            unset($_SESSION['rango_correcto']);
            $_SESSION['valores_seleccionados'] = [];
        } else {
            echo '<form method="POST" action="" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">';
            echo '<button type="submit" name="seleccionar_valor" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Iniciar</button>';
            echo '<button type="submit" name="reiniciar_seleccion" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Reiniciar selección</button>';
            echo '</form>';
            echo '<form method="GET" action="inicio_maraton.php">';
            echo '<button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Volver al menú</button>';
            echo '</form>';
            exit;
        }
    }

    // Seleccionar valor o verificar respuesta
    if (isset($_POST['seleccionar_valor']) || isset($_POST['verificar_respuesta'])) {
        $valores_seleccionados = $_SESSION['valores_seleccionados'];

        if (count($valores_seleccionados) < $totalValores || isset($_SESSION['valor_actual'])) {
            if (isset($_POST['seleccionar_valor']) && !isset($_SESSION['valor_actual'])) {
                $valores_disponibles = array_diff($datos1, $valores_seleccionados);

                if (!empty($valores_disponibles)) {
                    $valor_seleccionado = $valores_disponibles[array_rand($valores_disponibles)];
                    $valores_seleccionados[] = $valor_seleccionado;
                    $_SESSION['valores_seleccionados'] = $valores_seleccionados;
                    $_SESSION['valor_actual'] = $valor_seleccionado;
                } else {
                    echo "<p>No hay más valores disponibles.</p>";
                    exit;
                }
            }

            $valor_seleccionado = $_SESSION['valor_actual'];
            $columna_texto = "pregunta";
            $texto = generarPregunta($valor_seleccionado, $tabla, $columna_texto);

            $columna_importe_i = "limite_inferior";
            $columna_importe_f = "limite_superior";

            $importe_i = obtenerDatoPorID($valor_seleccionado, $tabla, $columna_importe_i);
            $importe_f = obtenerDatoPorID($valor_seleccionado, $tabla, $columna_importe_f);

            if ($importe_i !== null && $importe_f !== null && is_numeric($importe_i) && is_numeric($importe_f)) {
                $rango_correcto = round($importe_i, 2) . ' a ' . round($importe_f, 2);

                if (!isset($_SESSION['opciones'])) {
                    $opciones = [];
                    $variaciones = [1.10, 1.20, 0.90];
                    foreach ($variaciones as $factor) {
                        $nuevo_i = round($importe_i * $factor, 2);
                        $nuevo_f = round($importe_f * $factor, 2);
                        $opciones[] = "$nuevo_i a $nuevo_f";
                    }
                    $opciones[] = $rango_correcto;
                    shuffle($opciones);
                    $_SESSION['opciones'] = $opciones;
                    $_SESSION['rango_correcto'] = $rango_correcto;
                }

                echo '<form method="POST" action="">';
                echo '<div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; background-color: #f9f9f9;">';
                echo '<h3>Pregunta:</h3>';
                echo '<p><strong>¿Cuál es el importe de la multa por <span style="color: #007bff;">' . htmlspecialchars($texto) . '</span>?</strong></p>';

                echo '<div><strong>Opciones:</strong><br>';
                foreach ($_SESSION['opciones'] as $opcion) {
                    $checked = (isset($_POST['respuesta']) && $_POST['respuesta'] === $opcion) ? 'checked' : '';
                    $color = '';
                    if (isset($_POST['verificar_respuesta'])) {
                        if ($opcion === $_SESSION['rango_correcto']) {
                            $color = 'style="color: green; font-weight: bold;"';
                        } elseif (isset($_POST['respuesta']) && $_POST['respuesta'] === $opcion) {
                            $color = 'style="color: red;"';
                        }
                    }
                    echo "<label $color><input type='radio' name='respuesta' value='$opcion' $checked> $opcion</label><br>";
                }
                echo '</div>';

                if (isset($_POST['verificar_respuesta'])) {
                    if ($_POST['respuesta'] === $_SESSION['rango_correcto']) {
                        echo '<p style="color: green; font-weight: bold;">La respuesta fue correcta.</p>';
                    } else {
                        echo '<p style="color: red; font-weight: bold;">Puedes hacer un mejor trabajo.</p>';
                    }

                    unset($_SESSION['valor_actual']);
                    unset($_SESSION['opciones']);
                    unset($_SESSION['rango_correcto']);

                    if (count($_SESSION['valores_seleccionados']) < $totalValores) {
                        echo '<button type="submit" name="seleccionar_valor" style="margin-top:10px;">Siguiente pregunta</button>';
                    } else {
                        echo '<p style="margin-top:10px;">Ya se han seleccionado todos los valores.</p>';
                    }
                } else {
                    echo '<button type="submit" name="verificar_respuesta" style="margin-top:10px;">Verificar</button>';
                }

                echo '</div>';
                echo '</form>';

                echo '<div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">';
                echo '<form method="POST" action="" style="display:inline-block;">';
                echo '<button type="submit" name="reiniciar_seleccion" style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Reiniciar selección</button>';
                echo '</form>';
                echo '<form method="GET" action="inicio_maraton.php" style="display:inline-block;">';
                echo '<button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Volver al menú</button>';
                echo '</form>';
                echo '</div>';
            } else {
                echo "<p>No se pudo obtener el rango de importes.</p>";
            }
        } else {
            echo "<p>Ya se han seleccionado todos los valores.</p>";
        }
    }
} else {
    echo "<p>No se encontraron registros en la tabla '" . htmlspecialchars($tabla) . "'.</p>";
}
?>