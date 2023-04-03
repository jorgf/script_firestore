<?php
namespace App\Lib;

class Lib {
  // debug tools
  public static function d($obj){var_dump($obj);}
  public static function dd($obj){
    print("____ DEBUG MODE ____\n\n");
    var_dump($obj); 
    die;
  }
  public static function s($msg){print "\n$msg\n\n";}
  public static function sd($msg){print "\n$msg\n\n"; die;}

  // string formatting
  public static function trimString($str, $inicio, $fim){
    $str = substr($str,strlen($inicio));
    $str_final = substr($str,0,(strlen($str)-strlen($fim)));
    return $str_final;
  }
}
