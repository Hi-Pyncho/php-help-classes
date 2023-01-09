<?php

class JAssocArray {
  protected $assocArray = [];

  function __construct(array $assocArray) {
    $this->assocArray = $assocArray;
  }

  function getKeys() {
    $result = [];
    
    foreach($this->assocArray as $key => $value) {
      array_push($result, $key);
    }

    return $result;
  }

  function getValues() {
    $result = [];

    foreach($this->assocArray as $key => $value) {
      array_push($result, $value);
    }

    return $result;
  }

  function filter($callback) {
    $newAssocArray = [];
    foreach($this->assocArray as $key => $value) {
      if($callback($key, $value)) {
        $newAssocArray[$key] = $value;
      }
    }

    $this->assocArray = $newAssocArray;

    return $this;
  }

  function isEmpty() {
    return $this->getLength() === 0;
  }

  function getLength() {
    return count($this->assocArray);
  }

  function getResult() {
    return $this->assocArray;
  }
}
