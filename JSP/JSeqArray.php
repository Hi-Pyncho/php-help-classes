<?

class JSeqArray {
  protected $array = [];

  function __construct(array $array) {
    $this->array = $array;
  }

  function map($callback) {
    $newArray = array_map($callback, $this->array);
    return new JSeqArray($newArray);
  }
  
  function filter($callback) {
    $newArray = array_filter($this->array, $callback, ARRAY_FILTER_USE_BOTH);
    return new JSeqArray($newArray);
  }

  /**
   * @return array
   */

  function getResult() {
    return $this->array;
  }

  /**
   * @return number
   */

  function getLength() {
    return count($this->array);
  }



  /**
   * @param callable $callback (mixed $item, number $index, array $array): mixed
   */
  
  function some($callback) {
    return $this->filter($callback)->getLength() !== 0;
  }

  function every($callback) {
    $result = true;

    foreach($this->array as $item) {
      if(!$callback($item)) {
        $result = false;
        break;
      }
    }

    return $result;
  }

  function isEmpty() {
    return $this->getLength() === 0;
  }

  function find($callback) {
    $result = null;

    foreach($this->array as $index => $item) {
      if($callback($item, $index)) {
        $result = $item;
        break;
      }
    }

    return $result;
  }

  function indexOf($value) {
    $result = array_search($value, $this->array);
    return $result === false ? -1 : $result;
  }

  function flat($depth = 1) {
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

    return iter($depth, $this->array);
  }

  function includes($value) {
    return in_array($value, $this->array);
  }

  function forEach($callback) {
    $newArray = $this->clone()->getResult();
    array_walk($newArray, $callback);
  }

  function clone() {
    return new JSeqArray($this->array);
  }

  function join($separator = ',') {
    implode($separator, $this->array);
  }

  function push(...$values) {
    $newArray = $this->clone()->getResult();
    array_push($newArray, $values);

    return $newArray;
  }

  function pop() {
    return array_pop($this->array);
  }

  function shift() {
    return array_shift($this->array);
  }

  function unshift(...$values) {
    return array_unshift($this->array, $values);
  }

  function reduce($callback, $initial = 0) {
    return array_reduce($this->array, $callback, $initial);
  }

  function slice($start, $end) {

  }

  function sort($callback) {
    return usort($this->clone()->getResult(), $callback);
  }

  function concat(...$arrays) {
    return array_merge($this->array, $arrays);
  }

  function reverse() {
    return array_reverse($this->array);
  }
}
