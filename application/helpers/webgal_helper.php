<?php

/**
 * Atgriež html izdrukai nokodētu tekstu
 * @param string $str - apstrādājamais teksts
 * @return type 
 */
function _h($str){
    return htmlspecialchars($str, ENT_COMPAT, "UTF-8");
}

/**
 * Izdrukā lietotāja padoto tekstu, pirms tam apstrādājot ar htmlspecialchars
 * @param type $str 
 */
function _eh($str){
    echo _h($str);
}


?>
