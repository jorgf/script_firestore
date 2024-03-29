<?php
namespace App\Lib;

class File {

  public static function readFile($path) {
    if(file_exists($path)){
      $file = fopen($path, 'r');
      $contents = fread($file, filesize($path));
      fclose($file);
      return $contents;
    }else{
      Lib::sd('ERRO! FILE DOES NOT EXIST');
    }
  }

  public static function writeFile($strPathDoc, $arrValues, $nameDir) {
    $pathDir = $nameDir . '/' . $strPathDoc;
    self::createDirIfNotExist($pathDir);
    $filename = $pathDir . '/data.json';
    self::createFileIfNotExist($filename);
    if(is_writable($filename)){
      $fp = self::openFile($filename);
      echo self::write($fp, $arrValues, $filename);
      fclose($fp);
    }else Lib::sd('THE FILE ' . $filename . ' IS NOT WRITABLE!');
  }

  public static function createDir($nameDir) {
    $permission = 0777;
    $recursive = true;
    mkdir($nameDir, $permission, $recursive);
    return $nameDir;
  }

  public static function getDirAndFiles($path) {
    $filetype = [
      'files' => [],
      'dir'   => []
    ];
    $dir = self::getNameSubDir($path);
    foreach($dir as $files){
      $filetype = self::buildArrayOfFiles($filetype, $files, $path);
    }
    return $filetype;
  }

  public static function buildArrayOfFiles($filetype, $files, $path) {
    if(is_file($path . '/' . $files)) $filetype['files'][] = $path . '/' . $files;
    else if(!str_starts_with($files, '.')) $filetype['dir'][] = $path . '/' . $files;
    else Lib::sd('ERROR! ...');
    return $filetype;
  }

  public static function getNameSubDir($nameDir) {
    if(!is_dir($nameDir)) {
      Lib::sd('ERROR! DIRECTORY NOT FOUND');
    } else {
      $subDir = scandir($nameDir);
      unset ($subDir[0]);
      unset ($subDir[1]);
      return $subDir;
    }
  }

  public static function createDirIfNotExist($pathDir) {
    if(!is_dir($pathDir)) self::createDir($pathDir);
  }

  public static function createFileIfNotExist($filename) {
    if(!file_exists($filename)) touch($filename);
  }

  public static function openFile($filename) {
    if(!$fp = fopen($filename, 'w')) Lib::sd('CANNOT OPEN FILE: ' . $filename);
    else return $fp;
  }

  public static function write($fp, $arrValues, $filename) {
    if(fwrite($fp, json_encode($arrValues, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)) === FALSE){
      Lib::sd('CANNOT WRITE TO FILE '. $filename);
    }
      return 'SUCCESS, WROTE TO FILE: ' . $filename.PHP_EOL;
  }
}
