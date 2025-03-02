<?php
// Verificar si el formulario fue enviado por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos del formulario
    $noCorridas = (int) $_POST['Corridas'];
    $noEstados = (int) $_POST['Estados'];
    $matrizTransicion = $_POST['matrizTransicion'];
    $estadoActual = "S".$_POST['EstadoInicial'];

    // DEBUG
/*
    echo '<pre>';
    echo json_encode($_POST, JSON_PRETTY_PRINT);
    echo '</pre>';
*/

    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resultados de Simulación</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="text-center text-black">Resultados de Simulación</h2>
                <p class="text-muted text-left">Estado Inicial: <strong>' .  $estadoActual . '</strong> 
                    <br>  Número de corridas: <strong>' . $noCorridas . '</strong>
                </p>

                
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Iteración</th>
                                <th>Número Aleatorio</th>
                                <th>Estado Actual</th>
                            </tr>
                        </thead>
                        <tbody>';

    // Bucle de simulación
    for ($a = 1; $a <= $noCorridas; $a++) {
        $randomNum = mt_rand() / mt_getrandmax(); // Genera un número aleatorio entre 0 y 1
        $estadoActual = calculaEstadoActual($matrizTransicion[$estadoActual], $randomNum); // Determina el nuevo estado
        
        // Imprimir los resultados en la tabla
        echo '<tr>
                <td><strong>' . $a . '</strong></td>
                <td>' . number_format($randomNum, 4) . '</td>
                <td class="text-primary"><strong>' . $estadoActual . '</strong></td>
              </tr>';
    }

    echo '          </tbody>
                    </table>
                </div>
                <div class="text-center mt-4">
                    <a href="index.html" class="btn btn-dark btn-block">Volver</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>';
}

// Función para calcular el estado actual
function calculaEstadoActual($estadosRango, $randomNum) {
    $limiteInferior = 0;
    $probabilidadesAcumuladas = [];

    // Calcular la probabilidad acumulada
    foreach ($estadosRango as $estado => $probabilidad) {
        $probabilidad = floatval($probabilidad);
        $limiteSuperior = $limiteInferior + $probabilidad;
        $probabilidadesAcumuladas[$estado] = $limiteSuperior;

        // Si el número aleatorio cae en el rango actual
        if ($randomNum >= $limiteInferior && $randomNum < $limiteSuperior) {
            return $estado;
        }

        // Actualizar límite inferior
        $limiteInferior = $limiteSuperior;
    }

    // Si no se encontró un estado válido, devolver el último estado
    return array_key_last($probabilidadesAcumuladas);
}
?>