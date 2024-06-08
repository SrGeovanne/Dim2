<?php
/**
 * Obtém o transformador recomendado com base na demanda em kVA.
 */
function obterTransformadorRecomendado($demanda_kva)
{
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
function obterFusivelMonofasicoRecomendado($potencia_kva)
{
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
function obterFusivelTrifasicoRecomendado($potencia_kva)
{
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
function obterBarramentoPrimarioRecomendado($potencia_kva)
{
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
function obterPosteRecomendado($potencia_kva)
{
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
function obterCircuitoSecundarioRecomendado($potencia_kva)
{
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
?>
