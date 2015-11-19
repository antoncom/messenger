<?php
/**
 * Формируем строку вида "(val1, val2, val3)," для дальнейшего склеивания в единый SQL-запрос
 * На входе ассоциативный массив типа field=>value
 */

$out = "";
if(!$scriptProperties['row']) return;
$row = $scriptProperties['row'];

$out = "(" . implode(",", array_values($row)) . "),";
return $out;