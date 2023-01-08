<?

class JDB {
  function __construct($host, $user, $password, $dbName) {
    $this->host = $host;
    $this->user = $user;
    $this->password = $password;
    $this->dbName = $dbName;
  }

  public function createConnection() {
    $dataSourceName = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $this->connection = new PDO($dataSourceName, $this->user, $this->password, $options);
  }

  public function query($query, $keys = []) {
    if(empty($keys)) {
      return $this->queryWithoutParams($query);
    }

    $prepared = $this->connection->prepare($query);
    $prepared->execute($keys);
    $result = [];

    foreach($prepared as $row) {
      array_push($result, $row);
    }

    return $result;
  }

  protected function queryWithoutParams($query) {
    $queryResult = $this->connection->query($query);
    $result = [];
    
    while($row = $queryResult->fetch()) {
      array_push($result, $row);
    }

    return $result;
  }
}
