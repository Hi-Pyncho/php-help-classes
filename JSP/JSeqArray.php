<?php

class JSeqArray {
  protected $array = [];

  function __construct(array $array) {
    $this->array = $array;
  }

  /**
   * @return array
  */
  function getResult() {
    return $this->array;
  }

  /**
   * @return int
  */
  function getLength() {
    return count($this->array);
  }

  /**
   * @param int $index
  */
  function at(int $index) {
    if($index < 0) {
      return $this->array[$this->getLength() + $index];
    }

    return $this->array[$index];
  }

  /**
   * @param callable $callback (mixed $item, int $index, array $array): mixed
   * @return JSeqArray
  */
  function map(callable $callback) {
    $newArray = [];

    foreach($this->array as $index => $item) {
      array_push($newArray, $callback($item, $index, $this->array));
    }

    return new JSeqArray($newArray);
  }
  
  /**
   * @param callable $callback (mixed $item, int $index, array $array): boolean
   * @return JSeqArray
  */
  function filter(callable $callback) {
    $newArray = [];

    foreach($this->array as $index => $item) {
      if($callback($item, $index, $this->array)) {
        array_push($newArray, $item);
      }
    }

    return new JSeqArray($newArray);
  }

  /**
   * @param callable $callback (mixed $item, int $index, array $array): boolean
   * @return boolean
  */
  function some(callable $callback) {
    $result = false;

    foreach($this->array as $index => $item) {
      if($callback($item, $index, $this->array)) {
        $result = true;
        break;
      }
    }

    return $result;
  }

  /**
   * @param callable $callback (mixed $item, int $index, array $array): boolean
  */
  function every(callable $callback) {
    $result = true;

    foreach($this->array as $index => $item) {
      if(!$callback($item, $index, $this->array)) {
        $result = false;
        break;
      }
    }

    return $result;
  }

  /**
   * @return boolean
   */
  function isEmpty() {
    return $this->getLength() === 0;
  }

  /**
   * @param callable $callback (mixed $item, int $index, array $array): mixed|null
  */
  function find(callable $callback) {
    $result = null;

    foreach($this->array as $index => $item) {
      if($callback($item, $index, $this->array)) {
        $result = $item;
        break;
      }
    }

    return $result;
  }

  /**
   * @return int
  */
  function indexOf($value) {
    $result = array_search($value, $this->array);
    return $result === false ? -1 : $result;
  }

  /**
   * @param int $depth
   * @return JSeqArray
  */
  function flat(int $depth = 1) {
    function iter($depth, $current, $result = []) {
      if($depth === 0) return $current;

      foreach($current as $item) {
        if(is_array($item)) {
          $result = array_merge($result, iter($depth - 1, $item));
        } else {
          array_push($result, $item);
        }
      }

      return $result;
    }

    return new JSeqArray(iter($depth, $this->array));
  }

  /**
   * @return boolean
  */
  function includes($value) {
    return in_array($value, $this->array);
  }

  /**
   * @param callable $callback (mixed $item, int $index, array $array): void
   * @return void
  */
  function forEach($callback) {
    $newArray = $this->clone()->getResult();
    
    foreach($newArray as $index => $item) {
      $callback($item, $index, $newArray);
    }
  }

  /**
   * @return JSeqArray
  */
  function clone() {
    return new JSeqArray($this->array);
  }

  /**
   * @param string $separator
   * @return string
  */
  function join(string $separator = ',') {
    return implode($separator, $this->array);
  }

  /**
   * Mutate original array
   * @param mixed $values
   * @return int length of the final array
  */
  function push(...$values) {
    array_push($this->array, $values);
    return $this->getLength();
  }

  /**
   * Mutate original array
   * @return mixed|null last element of the array or null if the array is empty
  */
  function pop() {
    if($this->isEmpty()) return null;
    return array_pop($this->array);
  }

  /**
   * Mutate original array
   * @return mixed|null first element of the array or null if the array is empty
  */
  function shift() {
    if($this->isEmpty()) return null;
    return array_shift($this->array);
  }

  /**
   * Mutate original array
   * @param mixed $values
   * @return int length of the final array
  */
  function unshift(...$values) {
    array_unshift($this->array, $values);
    return $this->getLength();
  }

  /**
   * @param callable $callback (mixed $initial, mixed $item, int $index, array $array): mixed
  */
  function reduce(callable $callback, mixed $initial = null) {
    $newArray = $this->clone()->getResult();

    if(is_null($initial)) {
      $initial = array_shift($newArray);
    }

    foreach($newArray as $index => $item) {
      $initial = $callback($initial, $item, $index, $newArray);
    }

    return $initial;
  }

  /**
   * @param int $start
   * @param int $end
   * @return JSeqArray
  */
  function slice(int $begin = 0, int $end = null) {
    $length = $this->getLength();
    $cloned = [];

    if(is_null($end)) $end = $length;

    $start = $begin;
    $start = ($start >= 0) ? $start : $length + $start;

    $upTo = $end;
    if ($end < 0) {
      $upTo = $length + $end;
    }

    $size = $upTo - $start;

    if ($size > 0) {
      for ($i = 0; $i < $size; $i++) {
        $cloned[$i] = $this->array[$start + $i];
      }
    } else {
      for ($i = 0; $i < $size; $i++) {
        $cloned[$i] = $this->array[$start + $i];
      }
    }
  
    return $cloned;
  }

  /**
   * Mutate original array
   * @param callable $callback ($prev, $current): 1|-1|0
   * @return JSeqArray
  */
  function sort(callable $callback) {
    usort($this->array, $callback);

    return $this;
  }

  /**
   * @return JSeqArray
  */
  function concat(...$arrays) {
    array_merge($this->array, $arrays);
    return $this;
  }

  /**
   * Mutate original array
   * @return JSeqArray
  */
  function reverse() {
    array_reverse($this->array);
    return $this;
  }
}
