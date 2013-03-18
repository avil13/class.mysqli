<?php

/**
 *
$conf = array();
$conf['host'] = 'localhost';
$conf['user'] = 'root';
$conf['pass'] = '';
$conf['db'] = 'db_name';

$db = new DB($conf);


$res = $db->query("SELECT * FROM table")->fetch(); // return 1 Array elements from table
$res = $db->query("SELECT name FROM table WHERE id=2 ")->fetch('name'); // return value of name
$res = $db->query("SELECT * FROM table")->fetchAll(); // return all Array elements from table

$data = array('id'=>NULL, 'key'=>'value');

$db->insert('table', $data); // insert value in to the 'table'
$get_id = $db->id(); // get last modefide ID

$db->update('table', $data, $where = array('id'=>12)); // update data where id = 12 in 'table'

$var = $db->clean('var'); // clean ' and ` 

 */
class DB
{

      private $result;

      public function __construct($db)
      {

            $this->mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['db']);

            if ( mysqli_connect_errno() ) {
                  printf("<b>Connection failed:</b> %s\n", mysqli_connect_error());
                  exit;
            } else {
                  //            $this->mysqli->query("SET CHARACTER SET 'cp1251'");
                  $this->mysqli->query("SET NAMES 'utf8'");
            }
      }

      public function query($SQL)
      {
            //$SQL = $this->mysqli->real_escape_string($SQL);
            $SQL = strtr(strval($SQL), array(
                // "\n" => ' ',
                // "\r\n" => ' ',
                // "\n\r" => ' ',
                '  ' => ' '));
            $this->result = $this->mysqli->query($SQL);

            if ( $this->result == true ) {
                  return $this;
            } else {
                  echo $this->mysqli->error . '<br>';
                  echo "<b>Problem with SQL:</b>  <span style=\"color:red;\">$SQL</span>";
                  exit;
            }
      }

      public function id()
      {
            return $this->mysqli->insert_id;
      }

      public function fetchAll()
      {
            $arr = array();
            while ( $r = mysqli_fetch_assoc($this->result) ) {
                  $arr[] = $r;
            }
            return $arr;
      }

      public function fetch($column = NULL)
      {
            if ( empty($column) ) {
                  return mysqli_fetch_assoc($this->result);
            } else {
                  $res = mysqli_fetch_assoc($this->result);
                  return $res[$column];
            }
      }

      public function insert($table, $data = null)
      {
            if ( is_null($data) ) {
                  $this->errors .= '<h3>Значения не катируются, нужен массив</h3>';
                  return false;
            }

            $keys = array();
            $vals = array();

            foreach ( $data as $k => $v ) {
                  if ( !empty($k) ) {
                        $keys[] = '`' . strtr($k, array("'" => "", "`" => "")) . '`';
//                        $vals[] = "'" . strtr($v, array("'" => "&#039;")) . "'";
                        $vals[] = "'" . nl2br(htmlspecialchars($v, ENT_QUOTES)) . "'";
                  } else {
                        continue;
                  }
            }

            $query = ' INSERT INTO `' . $table . '` (' . implode(', ', $keys) . ') VALUES (' .
                    implode(', ', $vals) . ')';

            return $this->query($query);
      }

      public function update($table, $data = null, $where = null)
      {
            if ( is_null($data) ) {
                  $this->errors .= '<h3>Значения не катируются, нужен массив</h3>';
                  return false;
            }


            $set = array();

            foreach ( $data as $k => $v ) {
                  if ( !empty($k) ) {
                        $set[] = '`' . strtr($k, array("'" => "", "`" => "")) . "` = '" . htmlspecialchars($v, ENT_QUOTES) . "'";
                  } else {
                        continue;
                  }
            }

            $whr = array();

            if ( !empty($where) ) {

                  foreach ( $where as $k => $v ) {
                        if ( !empty($k) ) {
                              $whr[] = '`' . strtr($k, array("'" => "", "`" => "")) . "` = '" . strtr($v, array("'" => "&#039;")) . "'";
                        } else {
                              continue;
                        }
                  }
            }

            $query = ' UPDATE ' . $table . ' SET ' . implode(', ', $set);

            if ( !empty($whr) ) {
                  $query = $query . ' WHERE ' . implode(' AND ', $whr);
            }

            return $this->query($query);
      }

      public function clean($var)
      {
            return strtr($var, array("'" => "", "`" => ""));
      }

      /**
       * @desc	Automatically close the connection when finished with this object.
       */
      public function __destruct()
      {
            $this->mysqli->close();
      }

}

