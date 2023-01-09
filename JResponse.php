<?php

class Response {
  private $responseData;
  private $statuses;

  function __construct() {
    $this->responseData = [
      'status' => 'pending',
      'total' => null,
      'current' => null,
      'error' => null,
      'data' => null,
    ];
    $this->statuses = (object)array(
      'success' => 'success',
      'pending' => 'pending',
      'fail' => 'fail',
    );
  }

  private function sendResponse() {
    echo json_encode($this->responseData, JSON_UNESCAPED_UNICODE);
    die();
  }

  public function getStatusName($statusName){
    if(!$this->statuses->$statusName) {
      $this->sendError('Такого статуса не существует');
    }

    return $this->statuses->$statusName;
  }
  
  public function sendError($message) {
    $this->responseData['status'] = 'fail';
    $this->responseData['error'] = $message;
    
    $this->sendResponse();
  }

  public function setRequestData($params) {
    foreach($params as $key => $value) {
      $this->responseData[$key] = $value;
    }
  }

  public function sendSuccess() {
    $this->responseData['status'] = 'success';

    $this->sendResponse();
  }
}
