<?php
if (!defined('ON_ROOT')) { include_once 'views/404.php'; die();}

class DbSource {
  protected static $connection;

  public function __construct() {
    $this->connect();
  }

  protected function connect() {
    if (!isset(self::$connection)) {
      self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

      if (self::$connection === false) {
        return false;
      }
    }

    return self::$connection;
  }

  public function query($query) {
    if (DEBUGGING) {
      echo $query."<br>";
    }

    $result = self::$connection->query($query);

    if ($this->errno()) {
      throw new Exception($this->errno() . ' ' . $this->error());
    }

    return $result;
  }

  public function getFirstRow($query) {
    $result = $this->query($query);

    if ($result === false) {
      return false;
    }

    $row = false;

    while ($r = $result->fetch_assoc()) {
      $row = $r;
      break;
    }

    $result->close();

    return $row;
  }

  public function getAllRows($query) {
    $result = $this->query($query);

    if (!$result) {
      return false;
    }

    $rows = array();

    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }

    $result->close();

    return $rows;
  }

  public function escape($value) {
    if (is_null($value)) {
      return 'NULL';
    }

    return self::$connection->real_escape_string($value);
  }

  public function quote($value) {
    if (is_null($value)) {
      return 'NULL';
    }

    return "'" . $this->escape($value) . "'";
  }

  public function startTransaction() {
    self::$connection->autocommit(false);
  }

  public function commit() {
    self::$connection->commit();
  }

  public function rollback() {
    self::$connection->rollback();
  }

  public function errno() {
    return self::$connection->errno;
  }

  public function error() {
    return self::$connection->error;
  }
}


class Model {
  var $dbSource = null;
  var $table = null;
  var $schema = null;
  var $primaryKey = array();

  public function __construct($dbSource) {
    $this->dbSource = $dbSource;
    $this->schema = $this->getSchema();
  }

  protected function getSchema() {
    $schema = array();

    foreach ($this->dbSource->getAllRows("DESCRIBE {$this->table}") as $index => $row) {
      if ('PRI' === $row['Key']) {
        $this->primaryKey[] = $row['Field'];
      }

      $schema[$row['Field']] = array('type' => $row['Type']);

      if (
        strpos($row['Type'], 'INT') ||
        strpos($row['Type'], 'DECIMAL') ||
        strpos($row['Type'], 'NUMBER')
      ) {
        $schema[$row['Field']]['quoted'] = false;
      } else {
        $schema[$row['Field']]['quoted'] = true;
      }
    }

    return $schema;
  }

  protected function quoteOrEscapeValues($tuple) {
    $processed = array();
    foreach ($tuple as $column => $value) {
      if ($this->schema[strtolower($column)]['quoted']) {
        $processed[$column] = $this->dbSource->quote($value);
      } else {
        $processed[$column] = $this->dbSource->escape($value);
      }
    }

    return $processed;
  }

  protected function createConditionClause($condition) {
    if (is_array($condition)) {
      $junctor = key($condition);

      if (0 === $junctor) {
        $cond = $condition[0];
        if (array_key_exists(strtolower($cond[0]), $this->schema)) {
          $value = $this->schema[strtolower($cond[0])]['quoted'] ? $this->dbSource->quote($cond[2]) : $this->dbSource->escape($cond[2]);
          return $cond[0] . " " . $cond[1] . " " . $value;
        } else {
          return implode(" ", $cond);
        }
      }

      $conditionClause = array();

      foreach ($condition[$junctor] as $cond) {
        if (is_array($cond) && 3 === count($cond)) {
          if (array_key_exists(strtolower($cond[0]), $this->schema)) {
            $value = $this->schema[strtolower($cond[0])]['quoted'] ? $this->dbSource->quote($cond[2]) : $this->dbSource->escape($cond[2]);
            $conditionClause[] = $cond[0] . " " . $cond[1] . " " . $value;
          } else {
            $conditionClause[] = implode(" ", $cond);
          }
        } else {
          $conditionClause[] = $cond;
        }
      }

      return implode(" $junctor ", $conditionClause);
    } else {
      return $condition;
    }
  }

  protected function createInsertQuery($tuple) {
    $processed = $this->quoteOrEscapeValues($tuple);
    $query = "INSERT INTO {$this->table}(";
    $query .= implode(", ", array_keys($processed));
    $query .= ") VALUES(";
    $query .= implode(", ", array_values($processed));
    $query .= ")";

    return $query;
  }

  protected function createUpdateQuery($tuple, $condition=null) {
    $processed = $this->quoteOrEscapeValues($tuple);
    $updates = array();

    foreach($processed as $column => $value) {
      $updates[] = "$column=$value";
    }

    $query = "UPDATE {$this->table} SET ";
    $query .= implode(", ", $updates);

    if (!empty($condition)) {
      $query .= " WHERE ";
      $query .= $this->createConditionClause($condition);
    }

    return $query;
  }

  protected function createDeleteQuery($condition=null) {
    $query = "DELETE FROM {$this->table}";

    if (!empty($condition)) {
      $query .= " WHERE ";
      $query .= $this->createConditionClause($condition);
    }

    return $query;
  }

  protected function createSelectQuery(
    $columns=null,
    $tables=null,
    $condition=null,
    $groups=null,
    $havingCondition=null,
    $orders=null,
    $limit=null,
    $offset=null
  ) {
    $query = "SELECT ";

    if (empty($columns)) {
      $query .= "*";
    } else {
      if (is_array($columns)) {
        $query .= implode(", ", $columns);
      } else {
        $query .= $columns;
      }
    }

    if (empty($tables)) {
      $query .= " FROM {$this->table}";
    } else {
      $query .= " FROM ";
      if (is_array($tables)) {
        $query .= implode(", ", $tables);
      } else {
        $query .= $tables;
      }
    }

    if (!empty($condition)) {
      $query .= " WHERE ";
      $query .= $this->createConditionClause($condition);
    }

    if (!empty($groups)) {
      $query .= " GROUP BY ";
      $query .= implode(", ", $groups);

      if (!empty($havingCondition)) {
        $query .= " HAVING ";
        $query .= $this->createConditionClause($havingCondition);
      }
    }

    if (!empty($orders)) {
      $query .= " ORDER BY ";

      if (is_array($orders)) {
        $query .= implode(", ", $orders);
      } else {
        $query .= $orders;
      }
    }

    if (!empty($limit)) {
      $query .= " LIMIT $limit";

      if (!empty($offset)) {
        $query .= ", $offset";
      }
    }

    return $query;
  }

  public function insert($tuple) {
    return $this->dbSource->query($this->createInsertQuery($tuple));
  }

  public function update($tuple, $condition) {
    return $this->dbSource->query($this->createUpdateQuery($tuple, $condition));
  }

  public function delete($condition) {
    return $this->dbSource->query($this->createDeleteQuery($condition));
  }

  public function selectFirstRow(
    $columns=null,
    $tables=null,
    $condition=null,
    $groups=null,
    $havingCondition=null,
    $orders=null,
    $limit=1
  ) {
    return $this->dbSource->getFirstRow($this->createSelectQuery(
      $columns,
      $tables,
      $condition,
      $groups,
      $havingCondition,
      $orders,
      $limit
    ));
  }

  public function selectAllRows(
    $columns=null,
    $tables=null,
    $condition=null,
    $groups=null,
    $havingCondition=null,
    $orders=null,
    $limit=null,
    $offset=null
  ) {
    return $this->dbSource->getAllRows($this->createSelectQuery(
      $columns,
      $tables,
      $condition,
      $groups,
      $havingCondition,
      $orders,
      $limit,
      $offset
    ));
  }

  public function selectFirst(
    $columns=null,
    $condition=null,
    $groups=null,
    $havingCondition=null,
    $orders=null,
    $limit=1
  ) {
    return $this->selectFirstRow(
      $columns,
      $this->table,
      $condition,
      $groups,
      $havingCondition,
      $orders,
      $limit
    );
  }

  public function selectAll(
    $columns=null,
    $condition=null,
    $groups=null,
    $havingCondition=null,
    $orders=null,
    $limit=null,
    $offset=null
  ) {
    return $this->selectAllRows(
      $columns,
      $this->table,
      $condition,
      $groups,
      $havingCondition,
      $orders,
      $limit,
      $offset
    );
  }

  public function updateRecord($record) {
    $tuple = array();
    $conds = array();

    foreach($record as $column => $value) {
      if (in_array($column, $this->primaryKey))   {
        $conds[] = array($column, '=', $value);
      } else {
        $tuple[$column] = $value;
      }
    }

    $condition = array();

    if (count($this->primaryKey) > 1) {
      $condition['AND'] = $conds;
    } else {
      $condition = $conds;
    }

    $this->update($tuple, $condition);
  }

  public function deleteRecord($record) {
    $conds = array();

    foreach($record as $column => $value) {
      if (in_array($column, $this->primaryKey)) {
        $conds[] = array($column, '=', $value);
      }
    }

    $condition = array();

    if (count($this->primaryKey) > 1) {
      $condition['AND'] = $conds;
    } else {
      $condition = $conds;
    }

    $this->delete($condition);
  }
}
