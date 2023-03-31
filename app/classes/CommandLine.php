<?php
namespace app\classes;

use app\models\Firestore;

class CommandLine{
  public $actions = [
    'importFS', 
    'exportFS', 
    'deleteDocument',
    // 'addDoc' //firestore + authentication
  ];
  public $error = [
    'ERROR: Pass action on the first param',
    'ERROR: Unknow action', 
    'ERROR: Pass name colletion on the second param or \'all\' for instantiate all collections',
    'ERROR: Pass name file on the third param (ex.: namefile.json)',
    'ERROR: Pass name directory on the third param (ex.: bkp_firestore)',
    'ERROR: Pass name document on the third param (ex.: jorge)'
  ];
  public $separator = '---------------------------------------------------------------------------------';
  // constructor
  public function __construct($num=0, $arr=[]) {
    if (!isset($arr[1])) sd($this->error[0].$this->listActions().PHP_EOL);
    if (!in_array($arr[1], $this->actions)) sd($this->error[1].$this->listActions().PHP_EOL);
    $action = $arr[1];
    if (!isset($arr[2])) sd($this->error[2].$this->listRootCollections().PHP_EOL);
    if($arr[1] == 'deleteDocument' && !isset($arr[3])){
      sd($this->error[5].PHP_EOL);
    }
    if (!isset($arr[3])) sd($this->error[4].PHP_EOL); //nome do diretorio
    $this->$action($arr[2],$arr[3]);
    die;
  }
  // export firestore data
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
  // import data to firestore
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
  //------aux methods-------
  // instanced firestore class
  private function instanceFirestore(){
    return new Firestore();
  }
  // list actions
  public function listActions($arr=null, $msg='ACTIONS LIST:') {
    if (!$arr) $arr = $this->actions;
    return "\n$this->separator\n$msg\n  - " . implode("\n  - ", $arr);
  }
  // list root collections in terminal
  public function listRootCollections($arr=null, $msg='COLLECTIONS LIST:'){
    $collections = $this->instanceFirestore()->listCollection();
    $msgFinal = "\n$this->separator\n$msg\n";
    foreach($collections as $coll){
      $msgFinal.="- ".$coll->id()." \n";
    }
    return $msgFinal;
  }
  // import banner
  public function bannerImport(){
    echo "--------------------------------------------------\n";
    echo "Iniciando IMPORTAÇÃO dos dados para o <firestore> \n";
    echo "--------------------------------------------------\n";
    echo "Path:\n";
  }
  // export banner
  public function bannerExport($name){
    echo "--------------------------------------------------------------\n";
    echo "Iniciando EXPORTAÇÃO dos dados para o diretorio $name \n";
    echo "--------------------------------------------------------------\n";
    echo "Path:\n";
  }
}
