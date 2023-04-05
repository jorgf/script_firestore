<?php
namespace App\Classes;

use App\Lib\File;
use App\Lib\Lib;
use App\Lib\Firestore;

class CommandLine {
  public $actions = [
    'importFS', 
    'exportFS', 
    'deleteDocument'
  ];
    public $error = [
    'action_null' => 'ERROR: Pass action on the first param',
    'action_unknow' => 'ERROR: Unknow action', 
    'collection_name' => 'ERROR: Pass name colletion on the second param or \'all\' for instantiate all collections',
    'file_name' => 'ERROR: Pass name file on the third param (ex.: namefile.json)',
    'dir_name' => 'ERROR: Pass name directory on the third param (ex.: bkp_firestore)',
    'doc_name' => 'ERROR: Pass name document on the third param (ex.: jorge)'
  ];
  public $separator = '---------------------------------------------------------------------------------';
  // TODO - criar um diretorio default, caso o usuario nao informe um
  public function __construct($num=0, $arr=[]) { 
    if (!isset($arr[1])) Lib::sd($this->error['action_null'].$this->listActions().PHP_EOL);
    elseif (!in_array($arr[1], $this->actions)) Lib::sd($this->error['action_unknow'].$this->listActions().PHP_EOL);
    elseif (!isset($arr[2])) Lib::sd($this->error['collection_name'].$this->listRootCollections().PHP_EOL);
    elseif($arr[1] == 'deleteDocument' && !isset($arr[3])) Lib::sd($this->error['doc_name'].PHP_EOL);
    elseif (!isset($arr[3])) Lib::sd($this->error['dir_name'].PHP_EOL); //nome do diretorio
    else{
      $action = $arr[1];
      $this->$action($arr[2], $arr[3]);
      die;
    }
  }
  
  // Firestore to local
  public function exportFS($nameColl, $nameDir) {
    $this->bannerExport($nameDir);
    $firestore = $this->instanceFirestore();
    if($nameColl == 'all') {
      $collections = $firestore->listCollection();
      foreach ($collections as $coll) {
        $firestore->exportData($coll->id(), $nameDir);
      }
    }else {
      $firestore->exportData($nameColl, $nameDir);
    }
  }
  
  // local to firestore
  public function importFS($nameColl, $nameDir){
    $this->bannerImport();
    $firestore = $this->instanceFirestore();
    if($nameColl == 'all') {
      $rootColl = File::getNameSubDir($nameDir);
      foreach($rootColl as $nameColl){
        $firestore->importData($nameColl, $nameDir);
      }
    }else {
      $firestore->importData($nameColl, $nameDir);
    }
  }

  // delete doc
  public function deleteDocument($nameColl, $nameDoc){
    $firestore = $this->instanceFirestore();
    echo $firestore->delete($nameColl, $nameDoc);
  }

  private function instanceFirestore(){
    return new Firestore();
  }

  public function listActions($arr=null, $msg='ACTIONS LIST:') {
    if (!$arr) $arr = $this->actions;
    return "\n$this->separator\n$msg\n  - " . implode("\n  - ", $arr);
  }

  public function listRootCollections($arr=null, $msg='COLLECTIONS LIST:'){
    $collections = $this->instanceFirestore()->listCollection();
    $msgFinal = "\n$this->separator\n$msg\n";
    foreach($collections as $coll){
      $msgFinal.= "- ".$coll->id()." \n";
    }
    return $msgFinal;
  }

  public function bannerImport(){
    echo "--------------------------------------------------\n";
    echo "Iniciando IMPORTAÇÃO dos dados para o <firestore> \n";
    echo "--------------------------------------------------\n";
    echo "Path:\n";
  }

  public function bannerExport($name){
    echo "--------------------------------------------------------------\n";
    echo "Iniciando EXPORTAÇÃO dos dados para o diretorio $name \n";
    echo "--------------------------------------------------------------\n";
    echo "Path:\n";
  }
}
