<?php

class PHelper {
  static function debug($data) {
    echo '<pre>';
    echo print_r($data, true);
    echo '</pre>';
  }

  static function setExeptionsHandler() {
    set_exception_handler(function(Throwable $exception) {
      $message = "<b style='color: red;'>Uncaught exception</b>: ";
      $message .= "<pre><b>FILE</b>: " . $exception->getFile() . "</pre>";
      $message .= "<pre><b>MESSAGE</b>: " . $exception->getMessage() . "</pre>";
      $message .= "<pre><b>LINE</b>: " . $exception->getLine() . "</pre>";
    
      echo $message;
    });
  }

  static function redirect($link) {
    header("Location: $link", true, 301);
    die();
  }

  static function setToShowErrors() {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
  }

  function writeToLog($text, $filePath) {
    date_default_timezone_set('Europe/Moscow');

    if(!is_writable($filePath)) {
      return false;
    }
    
    $time = date("H:i");
    return file_put_contents($filePath, "[ $time ]" . print_r($text, true) . "\n\n_____________________", FILE_APPEND);
  }

  static function sanitizePostValue($fieldName) {
    return filter_input(INPUT_POST, $fieldName, FILTER_SANITIZE_SPECIAL_CHARS);
  }

  static function isAssocArray($arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  static function isSequentialArray($arr) {
    return is_array($arr) && !self::isAssocArray($arr);
  }

  static function removeDirectory($path) {
    $files = glob($path . '/*');

    foreach ($files as $file) {
      is_dir($file) ? self::removeDirectory($file) : unlink($file);
    }

    rmdir($path);
  
    return;
  }

  static function includeFile($filepath, $params = []) {
    $params;
    include $filepath;
  }

  static function getUrlInfo() {
    $requestUrlParts = explode('?', $_SERVER['REQUEST_URI']);
    $query = '';
    parse_str($requestUrlParts[1], $query);

    return [
      'host' => $_SERVER['HTTP_HOST'],
      'path' => $requestUrlParts[0],
      'query' => $query,
      'agent' => $_SERVER['HTTP_USER_AGENT'],
      'protocol' => $_SERVER['REQUEST_SCHEME'],
    ];
  }

  static function getPathWithoutRoot($path) {
    return substr($path, strlen($_SERVER['DOCUMENT_ROOT']));
  }

  static function request($url, $headers = [], $data = []) {
    $ch = curl_init($url);
  
    if(!empty($data)) {
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
  
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
  
    $result = ['status' => 'success'];
  
    try {
      $result['data'] = curl_exec($ch);
    } catch(Exception $error) {
      $result['error'] = $error;
      $result['status'] = 'fail';
    }
  
    curl_close($ch);
  
    return $result;
  }
}
