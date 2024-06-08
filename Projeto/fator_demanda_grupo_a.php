<?php
function calcularFatorDemandaGrupoA($ramo_selecionado, $potencia)
{
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
            return 69; // Caso padrão se o ramo não for reconhecido
    }
}
?>
