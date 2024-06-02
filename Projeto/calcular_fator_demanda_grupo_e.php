<?php
function calcularFatorDemandaGrupoE($pot_va_totais) {
    $max_pot_va = max($pot_va_totais);
    $fatores = [];
    foreach ($pot_va_totais as $pot_va) {
        $fatores[] = ($pot_va == $max_pot_va) ? 1 : 0.6;
    }
    return $fatores;
}
?>
