<?php
// Inicia a sessão
session_start();

// Funções para cálculo de fator de demanda

/**
 * Calcula o fator de demanda para o Grupo A com base no ramo selecionado e na potência.
 */
function calcularFatorDemandaGrupoA($ramo_selecionado, $potencia) {
    switch ($ramo_selecionado) {
        case 'auditorio':
        case 'bancos':
        case 'barbearias':
        case 'clubes':
        case 'garagem':
        case 'igreja':
        case 'restaurantes':
            return 1;
        case 'escola':
            return $potencia <= 12000 ? 1 : (12000 + 0.5 * ($potencia - 12000)) / $potencia;
        case 'escritorios':
            return $potencia <= 20000 ? 1 : (20000 + 0.7 * ($potencia - 20000)) / $potencia;
        case 'hospitais':
            return $potencia <= 50000 ? 0.4 : (0.4 * 50000 + 0.2 * ($potencia - 50000)) / $potencia;
        case 'hoteis':
            if ($potencia <= 20000) {
                return 0.5;
            } elseif ($potencia <= 100000) {
                return (0.5 * 20000 + 0.4 * ($potencia - 20000)) / $potencia;
            } else {
                return (0.5 * 20000 + 0.4 * 80000 + 0.3 * ($potencia - 100000)) / $potencia;
            }
        case 'residencias':
            if ($potencia <= 10000) {
                return 1;
            } elseif ($potencia <= 120000) {
                return (10000 + 0.35 * ($potencia - 10000)) / $potencia;
            } else {
                return (10000 + 0.35 * 110000 + 0.25 * ($potencia - 120000)) / $potencia;
            }
        default:
            return 1; // Caso padrão se o ramo não for reconhecido
    }
}

/**
 * Calcula o fator de demanda para o Grupo B com base na carga e quantidade.
 */
function calcularFatorDemandaGrupoB($carga, $quantidade) {
    $fatores = [
        'chuveiro eletrico' => [1, 0.8, 0.55, 0.5, 0.45, 0.42, 0.4, 0.39, 0.38, 0.36, 0.34, 0.31, 0.29, 0.28, 0.27, 0.26, 0.25, 0.25, 0.25, 0.25],
        'torneira eletrica' => [0.96, 0.72, 0.57, 0.54, 0.51, 0.48, 0.46, 0.45, 0.44, 0.42, 0.41, 0.38, 0.36, 0.34, 0.32, 0.31, 0.30, 0.30, 0.30, 0.30],
        'aquecedor passagem' => [0.96, 0.72, 0.57, 0.54, 0.51, 0.48, 0.46, 0.45, 0.44, 0.42, 0.41, 0.38, 0.36, 0.34, 0.32, 0.31, 0.30, 0.30, 0.30, 0.30],
        'ferro eletrico' => [0.96, 0.72, 0.57, 0.54, 0.51, 0.48, 0.46, 0.45, 0.44, 0.42, 0.41, 0.38, 0.36, 0.34, 0.32, 0.31, 0.30, 0.30, 0.30, 0.30],
        'fogao eletrico' => [1, 0.6, 0.4, 0.35, 0.32, 0.3, 0.28, 0.27, 0.26, 0.25, 0.24, 0.23, 0.22, 0.21, 0.21, 0.20, 0.20, 0.20, 0.20, 0.20],
        'maquina secar roupa' => [1, 1, 1, 1, 0.85, 0.75, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4, 0.35, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3],
        'maquina lavar louca' => [1, 1, 1, 1, 0.85, 0.75, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4, 0.35, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3],
        'forno eletrico' => [1, 1, 1, 1, 0.85, 0.75, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4, 0.35, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3],
        'microondas' => [1, 1, 1, 1, 0.85, 0.75, 0.65, 0.6, 0.55, 0.5, 0.45, 0.4, 0.35, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3, 0.3]
    ];

    return $fatores[$carga][min($quantidade - 1, 19)] ?? 1;
}

/**
 * Calcula o fator de demanda para o Grupo C com base no número de aparelhos.
 */
function calcularFatorDemandaGrupoC($numAparelhos) {
    $fatores = [1.00, 0.88, 0.82, 0.78, 0.76, 0.74, 0.72, 0.71, 0.70];
    return $numAparelhos <= 10 ? $fatores[$numAparelhos - 1] : 0.70;
}

/**
 * Calcula o fator de demanda para o Grupo D com base na potência VA total.
 */
function calcularFatorDemandaGrupoD($pot_va_totais) {
    $max_pot_va = max($pot_va_totais);
    return array_map(fn($pot_va) => $pot_va == $max_pot_va ? 1 : 0.7, $pot_va_totais);
}

/**
 * Calcula o fator de demanda para o Grupo E com base na potência VA total.
 */
function calcularFatorDemandaGrupoE($pot_va_totais) {
    $max_pot_va = max($pot_va_totais);
    return array_map(fn($pot_va) => $pot_va == $max_pot_va ? 1 : 0.6, $pot_va_totais);
}

// Funções de recomendação

/**
 * Obtém o transformador recomendado com base na demanda em kVA.
 */
function obterTransformadorRecomendado($demanda_kva) {
    $tabela = [
        [60, 82, 75],
        [83, 124, 112.5],
        [125, 165, 150],
        [166, 248, 225],
        [249, 330, 300],
        [331, 550, 500],
        [551, 825, 750],
        [826, 1100, 1000],
        [1101, 1375, 1250],
        [1376, 1650, 1500],
        [1651, 2200, 2000],
        [2201, 2717, 2500],
    ];

    foreach ($tabela as $linha) {
        if ($demanda_kva >= $linha[0] && $demanda_kva <= $linha[1]) {
            return $linha[2];
        }
    }

    return 'N/A';
}

/**
 * Obtém o fusível monofásico recomendado com base na potência em kVA.
 */
function obterFusivelMonofasicoRecomendado($potencia_kva) {
    $tabela = [
        5 => '0.5H',
        10 => '1H',
        15 => '2H',
        25 => '3H',
        37.5 => '5H',
    ];

    return $tabela[$potencia_kva] ?? 'N/A';
}

/**
 * Obtém o fusível trifásico recomendado com base na potência em kVA.
 */
function obterFusivelTrifasicoRecomendado($potencia_kva) {
    $tabela = [
        45 => '2H',
        75 => '3H',
        112.5 => '5H',
        150 => '5K',
        225 => '10K',
        300 => '15K',
        500 => '25K',
        750 => '40K',
        1000 => '40K',
        1500 => '65K',
    ];

    return $tabela[$potencia_kva] ?? 'N/A';
}

/**
 * Obtém o barramento primário recomendado com base na potência em kVA.
 */
function obterBarramentoPrimarioRecomendado($potencia_kva) {
    $tabela = [
        [0, 800, '3/4" x 1/8"', 30, '1/4"'],
        [801, 1500, '3/4" x 1/8"', 30, '3/8"'],
        [1501, 2000, '3/4" x 3/16"', 40, '3/8"'],
        [2001, 2500, 'N/A', 60, 'N/A'],
    ];

    foreach ($tabela as $linha) {
        if ($potencia_kva >= $linha[0] && $potencia_kva <= $linha[1]) {
            return "{$linha[2]} (seção do tubo: {$linha[3]} mm², vergalhão: {$linha[4]})";
        }
    }

    return 'N/A';
}

/**
 * Obtém o poste recomendado com base na potência em kVA.
 */
function obterPosteRecomendado($potencia_kva) {
    $tabela = [
        75 => '300 daN',
        150 => '600 daN',
        225 => '600 daN',
        300 => '1000 daN',
    ];

    return $tabela[$potencia_kva] ?? 'N/A';
}

/**
 * Obtém o circuito secundário recomendado com base na potência em kVA.
 */
function obterCircuitoSecundarioRecomendado($potencia_kva) {
    $tabela = [
        5 => ['220', 23, '1#6 (35)', '20 (3/4")', 25, '2'],
        10 => ['220', 45, '1#6 (35)', '20 (3/4")', 40, '2'],
        15 => ['220', 68, '1#10 (25)', '20 (3/4")', 70, '2'],
        25 => ['220', 114, '1#25 (25)', '25 (1")', 100, '1/0'],
        37.5 => ['220', 170, '1#50 (25)', '25 (1")', 175, '2/0'],
        75 => ['380', 114, '3#35 (95)', '50 (2")', 125, '1/0'],
        112.5 => ['380', 171, '3#70 (35)', '65 (2 1/2")', 175, '2/0'],
        150 => ['380', 228, '3#95 (50)', '65 (2 1/2")', 225, '3/0'],
        225 => ['380', 342, '3#150 (70)', '80 (3")', 350, '4/0'],
        300 => ['380', 456, '2x3#95 (2#150)', '100 (4")', 500, '250'],
    ];

    if (isset($tabela[$potencia_kva])) {
        $linha = $tabela[$potencia_kva];
        return "Tensão Secundária: {$linha[0]} V, Corrente Nominal Secundária: {$linha[1]} A, Cabo de Cobre: {$linha[2]}, Diâmetro do Eletroduto: {$linha[3]}, Corrente do Disjuntor: {$linha[4]} A, Bitola do Condutor de Aterramento (cobre): {$linha[5]} AWG";
    }

    return 'N/A';
}

// Função para calcular os fatores de demanda para todos os grupos
function calcularFatoresDemanda($potencias, $cargas, $qtds, $ramo_selecionado) {
    $fatores_demanda = [];

    foreach (['A', 'B', 'C', 'D', 'E'] as $grupo) {
        if (isset($potencias[$grupo])) {
            switch ($grupo) {
                case 'A':
                    $fatores_demanda[$grupo] = array_map(fn($pot, $qtd) => calcularFatorDemandaGrupoA($ramo_selecionado, $pot * $qtd), $potencias[$grupo], $qtds[$grupo]);
                    break;
                case 'B':
                    $fatores_demanda[$grupo] = array_map(fn($carga, $qtd) => calcularFatorDemandaGrupoB($carga, $qtd), $cargas[$grupo], $qtds[$grupo]);
                    break;
                case 'C':
                    $fatores_demanda[$grupo] = array_fill(0, count($potencias[$grupo]), calcularFatorDemandaGrupoC(count($potencias[$grupo])));
                    break;
                case 'D':
                    $pot_va_totais = array_map(fn($pot, $qtd) => $pot * $qtd, $potencias[$grupo], $qtds[$grupo]);
                    $fatores_demanda[$grupo] = calcularFatorDemandaGrupoD($pot_va_totais);
                    break;
                case 'E':
                    $pot_va_totais = array_map(fn($pot, $qtd) => $pot * $qtd, $potencias[$grupo], $qtds[$grupo]);
                    $fatores_demanda[$grupo] = calcularFatorDemandaGrupoE($pot_va_totais);
                    break;
            }
        }
    }

    return $fatores_demanda;
}

// Verifica se os dados foram enviados por GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    // Inicializa os arrays de potências, cargas, quantidades e fatores de potência
    $potencias = [];
    $cargas = [];
    $qtds = [];
    $fps = [];

    // Agrupa as potências, cargas, quantidades e fatores de potência por grupo
    foreach ($_GET['grupo'] as $index => $grupo) {
        $grupo = htmlspecialchars($grupo, ENT_QUOTES, 'UTF-8');
        $carga = htmlspecialchars($_GET['carga'][$index], ENT_QUOTES, 'UTF-8');
        $descricao = htmlspecialchars($_GET['Descricoes'][$index], ENT_QUOTES, 'UTF-8');
        $qtd = intval($_GET['qtd'][$index]);
        $pot_w = floatval($_GET['pot_w'][$index]);
        $fp = floatval($_GET['fp'][$index]);

        $potencias[$grupo][] = $pot_w;
        $cargas[$grupo][] = $carga;
        $qtds[$grupo][] = $qtd;
        $fps[$grupo][] = $fp;
    }

    // Verifica se o ramo selecionado está definido na sessão
    $ramo_selecionado = isset($_SESSION['ramo_selecionado']) ? htmlspecialchars($_SESSION['ramo_selecionado'], ENT_QUOTES, 'UTF-8') : '';

    // Calcula os fatores de demanda com base nas potências, cargas, quantidades e no ramo selecionado
    $fatores_demanda = calcularFatoresDemanda($potencias, $cargas, $qtds, $ramo_selecionado);
} else {
    // Se não houver dados enviados por GET, redireciona de volta para a página anterior
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Saída dos Dados</title>
    <link rel="stylesheet" href="gg.css">
</head>

<body>
    <?php
    // Verifica se o ramo selecionado está definido na sessão
    if (isset($_SESSION['ramo_selecionado'])) {
        echo "<h2>Ramo selecionado: " . htmlspecialchars($_SESSION['ramo_selecionado']) . "</h2>";
    } else {
        echo "<h2>Nenhum ramo selecionado</h2>";
    }

    // Verifique se existem dados na URL
    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
        // Array associativo para armazenar as somas de acordo com o grupo
        $group_sums = [];
        $total_w_fd = 0;

        // Agrupa os índices por grupo
        $indices_por_grupo = [];
        foreach ($_GET['grupo'] as $index => $grupo) {
            $indices_por_grupo[$grupo][] = $index;
        }

        // Itera sobre cada grupo para exibir uma tabela separada
        foreach (['A', 'B', 'C', 'D', 'E'] as $grupo) {
            if (isset($indices_por_grupo[$grupo])) {
                echo "<h3>Grupo: " . htmlspecialchars($grupo) . "</h3>";
                echo "<table border='1'>";
                echo "<tr>
                    <th>Grupo</th>
                    <th>Tipo de Carga</th>
                    <th>Descrição</th>
                    <th>Quantidade</th>
                    <th>Potência (W)</th>
                    <th>Fator de Potência (FP)</th>
                    <th>Potência VA</th>
                    <th>Potência W Total</th>
                    <th>Potência VA Total</th>
                    <th>Fator de Demanda (FD)</th>
                    <th>Potência Total com FD</th>
                </tr>";

                $total_pot_fd_grupo = 0; // Inicializa o total do grupo

                foreach ($indices_por_grupo[$grupo] as $index) {
                    // Verifica se o índice atual possui os dados esperados
                    if (isset($_GET['grupo'][$index]) && isset($_GET['qtd'][$index]) && isset($_GET['pot_w'][$index]) && isset($_GET['fp'][$index])) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($_GET['grupo'][$index]) . "</td>";
                        echo "<td>" . (isset($_GET['carga'][$index]) ? htmlspecialchars($_GET['carga'][$index]) : '') . "</td>";
                        echo "<td>" . (isset($_GET['Descricoes'][$index]) ? htmlspecialchars($_GET['Descricoes'][$index]) : '') . "</td>";
                        echo "<td>" . htmlspecialchars($_GET['qtd'][$index]) . "</td>";
                        echo "<td>" . htmlspecialchars($_GET['pot_w'][$index]) . "</td>";

                        // Define FP conforme o grupo
                        if ($_GET['grupo'][$index] === 'C' || $_GET['grupo'][$index] === 'D' || $_GET['grupo'][$index] === 'E') {
                            $fp = 1;
                            echo "<td>" . $fp . "</td>";
                        } else {
                            $fp = floatval($_GET['fp'][$index]);
                            echo "<td>" . number_format($fp, 2) . "</td>";
                        }

                        // Calcula a potência VA
                        $pot_va = $_GET['pot_w'][$index] / $fp;
                        echo "<td>" . (floor($pot_va) == $pot_va ? number_format($pot_va, 0) : number_format($pot_va, 2)) . "</td>";

                        // Calcula a potência W total
                        $pot_w_total = $_GET['pot_w'][$index] * $_GET['qtd'][$index];
                        echo "<td>" . (floor($pot_w_total) == $pot_w_total ? number_format($pot_w_total, 0) : number_format($pot_w_total, 2)) . "</td>";

                        // Calcula a potência VA total
                        $pot_va_total = $pot_va * $_GET['qtd'][$index];
                        echo "<td>" . (floor($pot_va_total) == $pot_va_total ? number_format($pot_va_total, 0) : number_format($pot_va_total, 2)) . "</td>";

                        // Adiciona a potência VA da linha ao total do grupo
                        $group_sums[$grupo]['pot_va_total'] = ($group_sums[$grupo]['pot_va_total'] ?? 0) + $pot_va_total;

                        // Adiciona a potência W da linha ao total do grupo
                        $group_sums[$grupo]['pot_w_total'] = ($group_sums[$grupo]['pot_w_total'] ?? 0) + $pot_w_total;

                        // Obtém o fator de demanda do grupo atual
                        $fator_demanda_grupo = $fatores_demanda[$grupo][$index % count($fatores_demanda[$grupo])] ?? 0;
                        echo "<td>" . (floor($fator_demanda_grupo) == $fator_demanda_grupo ? number_format($fator_demanda_grupo, 0) : number_format($fator_demanda_grupo, 2)) . "</td>";

                        // Calcula a potência total considerando o fator de demanda
                        $potencia_total_com_fd = $pot_va_total * $fator_demanda_grupo;
                        echo "<td>" . (floor($potencia_total_com_fd) == $potencia_total_com_fd ? number_format($potencia_total_com_fd, 0) : number_format($potencia_total_com_fd, 2)) . "</td>";

                        // Soma a potência total com FD ao total geral
                        $total_w_fd += $potencia_total_com_fd;

                        // Atualiza o total do grupo
                        $total_pot_fd_grupo += $potencia_total_com_fd;

                        echo "</tr>";
                    }
                }

                // Exibe a linha de total para o grupo atual
                echo "<tr>";
                echo "<td colspan='7'>Total do Grupo $grupo</td>";
                echo "<td>" . (floor(($group_sums[$grupo]['pot_w_total'] ?? 0)) == ($group_sums[$grupo]['pot_w_total'] ?? 0) ? number_format(($group_sums[$grupo]['pot_w_total'] ?? 0), 0) : number_format(($group_sums[$grupo]['pot_w_total'] ?? 0), 2)) . "</td>";
                echo "<td>" . (floor(($group_sums[$grupo]['pot_va_total'] ?? 0)) == ($group_sums[$grupo]['pot_va_total'] ?? 0) ? number_format(($group_sums[$grupo]['pot_va_total'] ?? 0), 0) : number_format(($group_sums[$grupo]['pot_va_total'] ?? 0), 2)) . "</td>";
                echo "<td></td>";
                echo "<td>" . (floor($total_pot_fd_grupo) == $total_pot_fd_grupo ? number_format($total_pot_fd_grupo, 0) : number_format($total_pot_fd_grupo, 2)) . "</td>"; // Exibe o total do grupo
                echo "</tr>";

                echo "</table><br>";
            }
        }

        // Calcular recomendações
        $transformador_recomendado = obterTransformadorRecomendado($total_w_fd);
        $fusivel_monofasico_recomendado = obterFusivelMonofasicoRecomendado($transformador_recomendado);
        $fusivel_trifasico_recomendado = obterFusivelTrifasicoRecomendado($transformador_recomendado);
        $barramento_primario_recomendado = obterBarramentoPrimarioRecomendado($transformador_recomendado);
        $poste_recomendado = obterPosteRecomendado($transformador_recomendado);
        $circuito_secundario_recomendado = obterCircuitoSecundarioRecomendado($transformador_recomendado);

        // Exibe a linha de total geral e as recomendações
        echo "<h3>Total Geral</h3>";
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td colspan='10'>Total Geral</td>";
        echo "<td>" . (floor($total_w_fd) == $total_w_fd ? number_format($total_w_fd, 0) : number_format($total_w_fd, 2)) . "</td>";
        echo "</tr>";

        // Recomendações
        echo "<tr><td colspan='10'>Transformador Recomendado</td><td>" . htmlspecialchars($transformador_recomendado) . " kVA</td></tr>";
        echo "<tr><td colspan='10'>Fusível Monofásico Recomendado</td><td>" . htmlspecialchars($fusivel_monofasico_recomendado) . "</td></tr>";
        echo "<tr><td colspan='10'>Fusível Trifásico Recomendado</td><td>" . htmlspecialchars($fusivel_trifasico_recomendado) . "</td></tr>";
        echo "<tr><td colspan='10'>Barramento Primário Recomendado</td><td>" . htmlspecialchars($barramento_primario_recomendado) . "</td></tr>";
        echo "<tr><td colspan='10'>Poste Recomendado</td><td>" . htmlspecialchars($poste_recomendado) . "</td></tr>";
        echo "<tr><td colspan='10'>Circuito Secundário Recomendado</td><td>" . htmlspecialchars($circuito_secundario_recomendado) . "</td></tr>";
        echo "</table>";
    } else {
        echo "<tr><td colspan='11'>Nenhum dado recebido.</td></tr>";
    }
    ?>
</body>

</html>
