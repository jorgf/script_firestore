<?php
namespace App\Lib;

use App\Lib\File;
use Google\Cloud\Firestore\FirestoreClient;

class Firestore {
  public FirestoreClient $firestore;
  public $projectId;

  public function __construct(){
    $this->firestore = new FirestoreClient([
      'keyFilePath' => 'config/settings.json'
    ]);
  }

  // firestore -> local
  public function exportData($pathColl, $nameDir){
    $obj= [$pathColl=>[]];
    $arrDoc = $this->getDocuments($pathColl);
    $obj[$pathColl] = $arrDoc;
    foreach ($obj as $key => $value) {
      $this->writeData($value, $nameDir);
    }
  }

  // local -> firestore
  public function importData($nameColl, $nameDir){
    $strPath = $nameColl == '' ? $nameDir : $nameDir.'/'.$nameColl;
    $arrSubDir = File::getDirAndFiles($strPath);
    foreach ($arrSubDir['dir'] as $key) {
      $this->importData('',$key);
    }
    $this->setDocuments($arrSubDir['files']);
  }

  public function delete($coll, $doc){
    if($this->documentExist($coll, $doc)){
      $this->firestore->collection($coll)->document($doc)->delete();
      return 'Documento deletado com sucesso' . PHP_EOL;
    }else {return 'Documento não encontrado' . PHP_EOL;}
  }

  private function writeData($arrValues, $nameDir){
    foreach ($arrValues as $nameDoc => $value) {
      $str = Lib::trimString($nameDoc, "projects/{$this->getProjectId()}/databases/(default)/documents/", '');
      File::writeFile($str, $value, $nameDir);
      $this->exportSubCollectionPath($nameDoc, $nameDir);
    }
  }

  private function exportSubCollectionPath($nameDoc, $nameDir){
    $subCol = $this->firestore->document($nameDoc)->collections();
    foreach($subCol as $sub){
      $this->exportData($sub->path(), $nameDir);
    }
  }

  private function setDocuments($arrSub){
    foreach ($arrSub as $key) {
      echo $key.PHP_EOL;
      $read = File::readfile($key);
      $data = json_decode($read, true);
      $strPath = Lib::trimString($key,'bkp_firestore/','/data.json');
      $this->firestore->document($strPath)->set($data);
    }
  }

  public function createDocument(){
    //...
  }
  public function authuser(){
    //...
  }

  public function getProjectId(){
    $obj = json_decode(FILE::readFile('config/settings.json'));
    return $obj->project_id;
  }

  public function getDocuments($pathColl){
    $arr = [];
    $collRef = $this->firestore->collection($pathColl);
    foreach($collRef->documents() as $doc){
      $arr[$doc->name()] = $doc->data();
    }
    return $arr;
  }

  public function documentExist($coll, $name){
    $docRef = $this->firestore->collection($coll)->document($name);
    $snapshot = $docRef->snapshot();
    return $snapshot->exists();
  }

  public function colectionExist($collectionName){
    $documents = $this->firestore->collection($collectionName)->limit(1)->documents();
    return $documents->isEmpty();
  }

  public function listCollection(){
    return $this->firestore->collections();
  }
}
