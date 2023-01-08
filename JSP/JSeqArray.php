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

  function getResult() {
    return $this->array;
  }

  function getLength() {
    return count($this->array);
  }
  
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
    
  }

  function indexOf($value) {

  }

  function flat($depth = 1) {

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

  }

  function unshift() {

  }

  function reduce($callback) {

  }

  function slice($start, $end) {

  }

  function sort($callback) {

  }

  function concat(...$arrays) {

  }
}
