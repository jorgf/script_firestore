<?php

// DEBUG TOOLS
function d($obj){var_dump($obj);}
function dd($obj){
  print("____ DEBUG MODE ____\n\n");
  var_dump($obj); 
  die;
}
function s($msg){print "\n$msg\n\n";}
function sd($msg){print "\n$msg\n\n"; die;}

// STRINGS
function trimString($str, $inicio, $fim){
  $str = substr($str,strlen($inicio));
  $str_final = substr($str,0,(strlen($str)-strlen($fim)));
  return $str_final;
}
