<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

 /**
  * Connect to database using either mysqli object-oriented, procedural or pdo
  */
 class ConnectDB {
  // object-oriented mysqli
  public static function start_db_with_oo() {
    $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    if (mysqli_connect_error()){
      die("Failed to connect to database: " . mysqli_connect_error());
    }
    // echo "Connected to database using object-oriented mysqli!";
    return $conn;
  }
  // procedural msqli
  public static function start_db_with_pro() {
    $conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    if (!$conn) {
      die("Failed to connect to database: " . mysqli_connect_error());
    }
    // echo "Connected to database using prodecural mysqli!";
    return $conn;
  }
  // PDO (works for 12 different databases)
  public static function start_db_with_pdo() {
    try {
      $conn = new PDO("mysql:host=".HOSTNAME.";"."dbname=".DATABASE."", USERNAME, PASSWORD);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connected to database using PDO!";
      return $conn;
    } catch (PDOException $err) {
      echo "Failed to connect to database: " . $err->getMessage();
    }
    $conn = null;
  }
 }

/**
 * Create database 
 */
class CreateDB {
  // object-oriented mysqli
  public static function create_db_with_oo() {
    // Create connection
    $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD);
    if (mysqli_connect_error())  {
      die("Connection failed: " . mysqli_connect_error());
    }
    // Create database
    $sql = "CREATE DATABASE test";
    if ($conn->query($sql) === TRUE) {
      echo "Database created successfully using object-oriented mysqli!";
    } else {
      echo "Failed to create database: " . $conn->error;
    }
    $conn->close();
  }
  // procedural mysqli
  public static function create_db_with_pro($dbname) {
    // Create connection
    $conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD);
    // Check connection
    if (mysqli_connect_error()) {
      die("Connection failed: " . mysqli_connect_error());
    }
    // Create database
    $sql = "CREATE DATABASE $dbname";
    if(mysqli_query($conn, $sql)) {
      echo "Database created successfully using procedural mysqli!";
    } else {
      echo "Failed to create database: " . mysqli_error($conn);
    }
    mysqli_close($conn);
  }
  // using pdo
  public static function create_db_with_pdo($dbname) {
    try {
      // Create connection
      $conn = new PDO("mysql:host=".HOSTNAME."", USERNAME, PASSWORD);
      // set PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      // Create database
      $sql = "CREATE DATABASE $dbname";
      // use exec() because no results are return
      $conn->exec($sql);
      echo "Database created successfully using PDO!";
    } catch (PDOException $e) {
      echo "Error creating database: " . $e->getMessage();
    }
    $conn = null;
  }
}

/**
 * Create Table
 */
class CreateTable {
  /**
   * Creates table using object-oriented approach.
   * 
   * Takes the table name and columns.
   * 
   * Columns should be an associative array with column attributes as the associated values
   * Example `id => INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY`
   */
  public static function create_table_with_oo(string $table_name, array $columns) {
    // Create connection
    $conn = ConnectDB::start_db_with_oo();
    // Get last element of columns (array)
    $last_el = array_slice($columns, -1, true);
    // Initiate sql statement
    $sql = "CREATE TABLE $table_name (";
    foreach ($columns as $key => $name) {
      // check if last element of array is set and is same as the current element in loop
      if (isset($last_el[$key]) and $columns[$key] == $last_el[$key]) {
        // close statement with a closing parenthesis instead
        $sql .= $key . " " . $name . ")"; 
      } else {
        // else add a comma after element
        $sql .= $key . " " . $name . ", ";
      }
    }
   // Query statement and echo success or error
    if ($conn->query($sql) === TRUE) {
      echo "Table `<b>$table_name</b>` created successfully.";
    } else {
      echo "Error creating table: " .$conn->error;
    }
    // Close connection
    $conn->close(); 
  }

  /**
   * Creates table using procedural approach.
   * 
   * Takes the table name and columns.
   * 
   * Columns should be an associative array with column attributes as the associated values
   * Example `id => INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY`
   */
  public static function create_table_with_pro(string $table_name, array $columns) {
    // Create connection 
    $conn = ConnectDB::start_db_with_pro();
    // Get last element of columns (array)
    $last_el = array_slice($columns, -1, true);
    // Initiate sql statement
    $sql = "CREATE TABLE $table_name (";
    foreach ($columns as $key => $name) {
      // check if last element of array is set and is same as the current element in loop
      if (isset($last_el[$key]) and $columns[$key] == $last_el[$key]) {
        // close statement with a closing parenthesis instead
        $sql .= $key . " " . $name . ")"; 
      } else {
        // else add a comma after element
        $sql .= $key . " " . $name . ", ";
      }
    }
    // Query statement and echo success or error
    if (mysqli_query($conn, $sql)) {
      echo "Table `<b>$table_name</b>` created successfully.";
    } else {
      echo "Error creating table: " . mysqli_error($conn);
    }
    // Close connection
    mysqli_close($conn);
  }

  /**
   * Creates table using procedural approach.
   * 
   * Takes the table name and columns.
   * 
   * Columns should be an associative array with column attributes as the associated values
   * Example `id => INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY`
   */
  public static function create_table_with_pdo(string $table_name, array $columns) {
    // Get last element of columns (array)
    $last_el = array_slice($columns, -1, true);
    // Initiate sql statement
    $sql = "CREATE TABLE $table_name (";
    foreach ($columns as $key => $name) {
      // check if last element of array is set and is same as the current element in loop
      if (isset($last_el[$key]) and $columns[$key] == $last_el[$key]) {
        // close statement with a closing parenthesis instead
        $sql .= $key . " " . $name . ")"; 
      } else {
        // else add a comma after element
        $sql .= $key . " " . $name . ", ";
      }
    }
    // 
    try {
      // Create connection 
      $conn = ConnectDB::start_db_with_pdo();
      // set PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Create table
      $conn->exec($sql);
      echo "Table `<b>$table_name`</b> created successfully.";
    } catch (PDOException $err) {
      echo "Error creating table `<b>$table_name</b>`: " . $err->getMessage();
    }
    $conn = null;
  }
}

/**
 * Create record
 */
class CreateRecord {
  /**
   * Using object-oriented approach
   * 
   * Takes table name and columns
   * 
   * Columns should be an associative array with values
   * Example `firstname => John`
   */ 
  public static function create_record_with_oo(string $table_name, array $columns) {
    $len = count($columns); // get length of columns
    $conn = ConnectDB::start_db_with_oo(); // initiate connection
    $column_names = "("; // column names or keys
    $default_column_values = "("; // column default values for prepared statement
    $column_values = array(); // column values
    $last_el = array_slice($columns, -1, true); // columns last element
    $stmt = "INSERT INTO $table_name "; // initial statement

    // add a comma to elements except the last element
    foreach ($columns as $key => $value) {
      if (isset($last_el[$key]) and $columns[$key] == $last_el[$key]) {
        $column_names .= $key . ")";
        $default_column_values .= "?)";
      } else {
        $column_names .= $key . ", ";
        $default_column_values .= "?, ";
      }
      // fill in the column_values array
      $column_values[] = $value;
    }
   
    $stmt .= $column_names . " VALUES " . $default_column_values; // statement ready to be prepared
    $stmt = $conn->prepare($stmt); // prepared statement
    $stmt->bind_param(str_repeat("s", $len), ...$column_values); // bind statement with real input values
    $stmt->execute(); // execute statement
    $last_id = $conn->lastInsertId();
    echo "New record: " . $last_id . " created successfully";
    $stmt->close(); // close statement
    $conn->close(); // close connection
  }

  /**
   * Using pdo approach
   * 
   * Takes table name and columns
   * 
   * Columns should be an associative array with values
   * Example `firstname => John`
   */ 
  public static function create_record_with_pdo(string $table_name, array $columns) {
    $len = count($columns); // get length of columns
    $column_names = "("; // column names or keys
    $default_column_values = "("; // column default values for prepared statement
    $column_values = array(); // column values
    $last_el = array_slice($columns, -1, true); // columns last element
    $stmt = "INSERT INTO $table_name "; // initial statement
    
    // add a comma to elements except the last element
    foreach ($columns as $key => $value) {
      if (isset($last_el[$key]) and $columns[$key] == $last_el[$key]) {
        $column_names .= $key . ")";
        $default_column_values .= ":$key)";
      } else {
        $column_names .= $key . ", ";
        $default_column_values .= ":$key, ";
      }
      // fill in the column_values array
      $column_values[] = $value;
    }
   
    try {
      $conn = ConnectDB::start_db_with_pdo(); // initiate connection
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt .= $column_names . " VALUES " . $default_column_values; // statement ready to be prepared
      $stmt = $conn->prepare($stmt); // prepared statement
    
      foreach($columns as $key => &$value) {
         $stmt->bindParam(':'.$key.'', $value); // bind statement   
      }  
      $stmt->execute(); 
     
      echo "New record created successfully";
    } catch (PDOException $err) {
      echo "Error creating record: " . $err->getMessage();
    }
    $conn = null;
  }
}

class SelectRecord {
  /**
   * Using object-oriented approach
   * 
   * Select records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function query_oo(string $table_name, $column_names = null, $condition = null) {
    $sql = "SELECT ";
    
    if ($condition == null and $column_names == null ) { // selects all records without a condition
      $sql .= "* FROM " . $table_name;
    } else if ($column_names !== null and $condition == null) { // selects records by query columns
      $last_el = array_slice($column_names, -1, true);
      foreach ($column_names as $value) {
        // check if last element of array is set and is same as the current element in loop
        if (isset($last_el) and $value == $last_el[0]) {
          // close statement with a closing parenthesis instead
          $sql .= $value . ""; 
        } else {
          // else add a comma after element
          $sql .= $value . ", ";
        }
      }
      $sql .= " FROM $table_name";
      
    } else if ($column_names == null and $condition !== null) { // select all records with a condition
      $sql .= "* FROM " . $table_name . " " .  $condition;
    } else { // selects records by query columns and a condition
      $last_el = array_slice($column_names, -1, true);
      foreach ($column_names as $value) {
        // check if last element of array is set and is same as the current element in loop
        if (isset($last_el) and $value == $last_el[0]) {
          // close statement with a closing parenthesis instead
          $sql .= $value . ""; 
        } else {
          // else add a comma after element
          $sql .= $value . ", ";
        }
      }
      $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
      if ($matches) {
        $sql .= " FROM " . $table_name . " ";
        $cond = explode(" ", $condition);
        foreach ($cond as $key => $value) {
            $s = explode("=", $value);
            $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
            if (!$regex and isset($s[1])) {
              $type = preg_match('/[a-zA-z]+/', $s[1]);
              if ($type) {
                $s[1] = "'" . $s[1] . "'";
              } 
            }
            $cond = implode("=", $s);
            $condition = explode(" ", $cond);
           
            foreach ($condition as $key => $value) {
              $sql .= " " . $value; 
            }
        }
      } else {
        $sql .= " FROM " . $table_name . " " . $condition;
      }
      
    }
   
    $conn = ConnectDB::start_db_with_oo();
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo json_encode($row);
      }
    } else {
      echo "No result found!";
    }
    $conn->close();
  }

  /**
   * Using pdo approach
   * 
   * Select records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function query_pdo(string $table_name, $column_names = null, $condition = null) {
    $sql = "SELECT ";
    
    if ($condition == null and $column_names == null ) { // selects all records without a condition
      $sql .= "* FROM " . $table_name;
    } else if ($column_names !== null and $condition == null) { // selects records by query columns
      $last_el = array_slice($column_names, -1, true);
      foreach ($column_names as $value) {
        // check if last element of array is set and is same as the current element in loop
        if (isset($last_el) and $value == $last_el[0]) {
          // close statement with a closing parenthesis instead
          $sql .= $value . ""; 
        } else {
          // else add a comma after element
          $sql .= $value . ", ";
        }
      }
      $sql .= " FROM $table_name";
      
    } else if ($column_names == null and $condition !== null) { // select all records with a condition
      $sql .= "* FROM " . $table_name . " " .  $condition;
    } else { // selects records by query columns and a condition
      $last_el = array_slice($column_names, -1, true);
      foreach ($column_names as $value) {
        // check if last element of array is set and is same as the current element in loop
        if (isset($last_el) and $value == $last_el[0]) {
          // close statement with a closing parenthesis instead
          $sql .= $value . ""; 
        } else {
          // else add a comma after element
          $sql .= $value . ", ";
        }
      }
      $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
      if ($matches) {
        $sql .= " FROM " . $table_name . " ";
        $cond = explode(" ", $condition);
        foreach ($cond as $key => $value) {
            $s = explode("=", $value);
            $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
            if (!$regex and isset($s[1])) {
              $type = preg_match('/[a-zA-z]+/', $s[1]);
              if ($type) {
                $s[1] = "'" . $s[1] . "'";
              } 
            }
            $cond = implode("=", $s);
            $condition = explode(" ", $cond);
           
            foreach ($condition as $key => $value) {
              $sql .= " " . $value; 
            }
        }     
      } else {
        $sql .= " FROM " . $table_name . " " . $condition;
      }
      
    }
   
    try {
      $conn = ConnectDB::start_db_with_pdo();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      
      // set the result array to associative
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      foreach ($stmt->fetchAll() as $key => $value) {
        foreach ($value as $k => $v) {
          echo $k. ": ".$v . "<br />";
        } 
      }
    } catch (PDOException $err) {
      echo "Error: " . $err->getMessage();
    }
    $conn = null;
  } 
}

class DeleteRecord {
  /**
   * Using object-oriented approach
   * 
   * Delete records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function delete_records_oo(string $table_name, $condition) {
    $sql = "DELETE FROM " . $table_name;
  
    $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
    if ($matches) {
      $cond = explode(" ", $condition);
      foreach ($cond as $key => $value) {
        $s = explode("=", $value);
        $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
          if (!$regex and isset($s[1])) {
            $type = preg_match('/[a-zA-z]+/', $s[1]);
            if ($type) {
              $s[1] = "'" . $s[1] . "'";
            } 
          }
          $cond = implode("=", $s);
          $condition = explode(" ", $cond);
           
          foreach ($condition as $key => $value) {
            $sql .= " " . $value; 
          }
        }
      } else {
        $sql .= " " . $condition;
      }

    $conn = ConnectDB::start_db_with_oo();
    $query = $conn->query($sql);

    if ($query === TRUE) {
      echo "Record(s) deleted successfully.";
    } else {
      echo "Error deleting record(s)" . $conn->error;
    }
    $conn->close();
  }

  /**
   * Using pdo approach
   * 
   * Delete records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function delete_records_pdo(string $table_name, $condition) {
    $sql = "DELETE FROM " . $table_name;
  
    $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
    if ($matches) {
      $cond = explode(" ", $condition);
      foreach ($cond as $key => $value) {
        $s = explode("=", $value);
        $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
          if (!$regex and isset($s[1])) {
            $type = preg_match('/[a-zA-z]+/', $s[1]);
            if ($type) {
              $s[1] = "'" . $s[1] . "'";
            } 
          }
          $cond = implode("=", $s);
          $condition = explode(" ", $cond);
           
          foreach ($condition as $key => $value) {
            $sql .= " " . $value; 
          }
        }
      } else {
        $sql .= " " . $condition;
      }
   
    try {
      $conn = ConnectDB::start_db_with_pdo();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->exec($sql);
      
      echo "Record(s) deleted successfully.";
    } catch (PDOException $err) {
      echo "Error deleting record(s): " . $err->getMessage();
    }
    $conn = null;
  }
}

class UpdateRecord {
  /**
   * Using object-oriented approach
   * 
   * Update records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function update_records_oo(string $table_name, $condition) {
    $sql = "UPDATE " . $table_name;
  
    $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
    if ($matches) {
      $cond = explode(" ", $condition);
      foreach ($cond as $key => $value) {
        $s = explode("=", $value);
        $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
          if (!$regex and isset($s[1])) {
            $type = preg_match('/[a-zA-z]+/', $s[1]);
            if ($type) {
              $s[1] = "'" . $s[1] . "'";
            } 
          }
          $cond = implode("=", $s);
          $condition = explode(" ", $cond);
           
          foreach ($condition as $key => $value) {
            $sql .= " " . $value; 
          }
        }
      } else {
        $sql .= " " . $condition;
      }
    
    $conn = ConnectDB::start_db_with_oo();
    $query = $conn->query($sql);

    if ($query === TRUE) {
      echo "Record(s) updated successfully.";
    } else {
      echo "Error updating record(s)" . $conn->error;
    }
    $conn->close();
  }

  /**
   * Using pdo approach
   * 
   * Update records with/without condition(s)
   * Takes table name and/or columns
   * 
   */
  public static function update_records_pdo(string $table_name, $condition) {
    $sql = "UPDATE " . $table_name;
  
    $regex = preg_match_all('/=[\'|"]?[a-zA-z0-9]+[\'|"]?/', $condition, $matches);
    if ($matches) {
      $cond = explode(" ", $condition);
      foreach ($cond as $key => $value) {
        $s = explode("=", $value);
        $regex = preg_match('/[a-zA-z0-9]+?=[\'|"][a-zA-z0-9]+[\'|"]/', $value);
            
          if (!$regex and isset($s[1])) {
            $type = preg_match('/[a-zA-z]+/', $s[1]);
            if ($type) {
              $s[1] = "'" . $s[1] . "'";
            } 
          }
          $cond = implode("=", $s);
          $condition = explode(" ", $cond);
           
          foreach ($condition as $key => $value) {
            $sql .= " " . $value; 
          }
        }
      } else {
        $sql .= " " . $condition;
      }
   
    try {
      $conn = ConnectDB::start_db_with_pdo();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // Prepare statement
      $stmt = $conn->prepare($sql);
      // execute the query
      $stmt->execute();
      
      echo $stmt->rowCount() . " record(s) updated successfully.";
    } catch (PDOException $err) {
      echo "Error updating record(s): " . $err->getMessage();
    }
    $conn = null;
  }
}
//  UpdateRecord::update_records_oo('customers', 'SET email=stephen.gilley@tuto.com WHERE id=16');
?>
