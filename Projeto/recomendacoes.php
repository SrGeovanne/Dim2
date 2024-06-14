<?php
/**
 * Obtém o transformador recomendado com base na demanda em kVA.
 */
function obterTransformadorRecomendado($demanda_kva)
{
    if ($demanda_kva >= 60*1e3 && $demanda_kva <= 82*1e3) {
        return "75 kVA";
    } elseif ($demanda_kva >= 83*1e3 && $demanda_kva <= 124*1e3) {
        return "112,5 kVA";
    } elseif ($demanda_kva >= 125*1e3 && $demanda_kva <= 165*1e3) {
        return "150 kVA";
    } elseif ($demanda_kva >= 166*1e3 && $demanda_kva <= 248*1e3) {
        return "225 kVA";
    } elseif ($demanda_kva >= 249*1e3 && $demanda_kva <= 330*1e3) {
        return "300 kVA";
    } elseif ($demanda_kva >= 331*1e3 && $demanda_kva <= 550*1e3) {
        return "500 kVA";
    } elseif ($demanda_kva >= 551*1e3 && $demanda_kva <= 825*1e3) {
        return "750 kVA";
    } elseif ($demanda_kva >= 826*1e3 && $demanda_kva <= 1100*1e3) {
        return "1000 kVA";
    } elseif ($demanda_kva >= 1101*1e3 && $demanda_kva <= 1375*1e3) {
        return "1250 kVA";
    } elseif ($demanda_kva >= 1376*1e3 && $demanda_kva <= 1650*1e3) {
        return "1500 kVA";
    } elseif ($demanda_kva >= 1651*1e3 && $demanda_kva <= 2200*1e3) {
        return "2000 kVA";
    } elseif ($demanda_kva >= 2201*1e3 && $demanda_kva <= 2717*1e3) {
        return "2500 kVA";
    } else {
        return "não há transformador recomendável para essa potência";
    }
}

/**
 * Obtém o fusível monofásico recomendado com base na potência em kVA.
 */
function obterFusiveisMonofasicos($potencia_va) {
    $fusivel = '';

    if ($potencia_va <= 5*1e3) {
        $fusivel = "0,5 H (13,8 kV ou 34,5 kV)";
    } elseif ($potencia_va <= 10*1e3) {
        $fusivel = "1 H (13,8 kV ou 34,5 kV)";
    } elseif ($potencia_va <= 15*1e3) {
        $fusivel = "1,5 H (13,8 kV)";
    } elseif ($potencia_va <= 25*1e3) {
        $fusivel = "2 H (13,8 kV)";
    } elseif ($potencia_va <= 37.5*1e3) {
        $fusivel = "3 H (13,8 kV)";
    }
    elseif ($potencia_va > 37.5*1e3) {
        $fusivel = "3 H (13,8 kV)";
    }

    return $fusivel;
}

/**
 * Obtém o fusível trifásico recomendado com base na potência em kVA.
 */
function obterFusiveisTrifasicos($potencia_va) {
    $fusivel = '';

    if ($potencia_va <= 45*1e3) {
        $fusivel = "2 H (13,8 kV)";
    } elseif ($potencia_va <= 75*1e3) {
        $fusivel = "3 H (13,8 kV)";
    } elseif ($potencia_va <= 112.5*1e3) {
        $fusivel = "5 H (13,8 kV)";
    } elseif ($potencia_va <= 150*1e3) {
        $fusivel = "7 H (13,8 kV)";
    } elseif ($potencia_va <= 225*1e3) {
        $fusivel = "10 H (13,8 kV)";
    } elseif ($potencia_va <= 300*1e3) {
        $fusivel = "15 H (13,8 kV)";
    } elseif ($potencia_va <= 500*1e3) {
        $fusivel = "20 H (13,8 kV)";
    } elseif ($potencia_va <= 750*1e3) {
        $fusivel = "30 H (13,8 kV)";
    } elseif ($potencia_va <= 1000*1e3) {
        $fusivel = "40 H (13,8 kV)";
    } elseif ($potencia_va <= 1500*1e3) {
        $fusivel = "65 H (13,8 kV)";
    }

    return $fusivel;
}

/**
 * Obtém o barramento primário recomendado com base na potência em kVA.
 */
function obterBarramentoPrimario($potencia_va) {
    $barramento = '';

    if ($potencia_va <= 800*1e3) {
        $barramento = "3/4\" x 1/8\" (30 mm²) - 1/4\" Φ";
    } elseif ($potencia_va <= 1500*1e3) {
        $barramento = "3/4\" x 3/16\" (40 mm²) - 3/8\" Φ";
    } elseif ($potencia_va <= 2500*1e3) {
        $barramento = "1\" x 3/8\" (60 mm²) - 1/2\" Φ";
    }

    return $barramento;
}

/**
 * Obtém o poste recomendado com base na potência em kVA.
 */
function obterPoste($potencia_va) {
    $poste = '';

    if ($potencia_va <= 75*1e3) {
        $poste = "300 daN";
    } elseif ($potencia_va <= 150*1e3) {
        $poste = "600 daN";
    } elseif ($potencia_va <= 225*1e3) {
        $poste = "800 daN";
    } elseif ($potencia_va <= 300*1e3) {
        $poste = "1000 daN";
    } elseif ($potencia_va >= 300*1e3) {
        $poste = "1000 daN";
    }
    

    return $poste;
}

/**
 * Obtém o circuito secundário recomendado com base na potência em kVA.
 */
function obterCircuitoSecundario($potencia_va) {
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

    $pot_kva = $potencia_va / 1000;
    if (isset($tabela[$pot_kva])) {
        $linha = $tabela[$pot_kva];
        return "Tensão Secundária: {$linha[0]} V, Corrente Nominal Secundária: {$linha[1]} A, Cabo de Cobre: {$linha[2]}, Diâmetro do Eletroduto: {$linha[3]}, Corrente do Disjuntor: {$linha[4]} A, Bitola do Condutor de Aterramento (cobre): {$linha[5]} AWG";
    }

    return 'N/A';
}
?>
