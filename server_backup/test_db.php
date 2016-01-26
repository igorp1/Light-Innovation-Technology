<?php
/************************************************************************/
/***************** Little LIBRARY for mysqli connect *********************/
/************************************************************************/
class db extends mysqli {
    public function prepare($query) {
        return new stmt($this,$query);
    }
}

class stmt extends mysqli_stmt {
    public function __construct($link, $query) {
        $this->mbind_reset();
        parent::__construct($link, $query);
    }

    public function mbind_reset() {
        unset($this->mbind_params);
        unset($this->mbind_types);
        $this->mbind_params = array();
        $this->mbind_types = array();
    }

    //use this one to bind params by reference
    public function mbind_param($type, &$param) {
        $this->mbind_types[0].= $type;
        $this->mbind_params[] = &$param;
    }

    //use this one to bin value directly, can be mixed with mbind_param()
    public function mbind_value($type, $param) {
        $this->mbind_types[0].= $type;
        $this->mbind_params[] = $param;
    }


    public function mbind_param_do() {
        $params = array_merge($this->mbind_types, $this->mbind_params);
        return call_user_func_array(array($this, 'bind_param'), $this->makeValuesReferenced($params));
    }

    private function makeValuesReferenced($arr){
        $refs = array();
        foreach($arr as $key => $value)
        $refs[$key] = &$arr[$key];
        return $refs;

    }

    public function execute() {
        if(count($this->mbind_params))
            $this->mbind_param_do();

        return parent::execute();
    }

    private $mbind_types = array();
    private $mbind_params = array();
}
/************************************************************************/
/************************************************************************/
/************************************************************************/

$host = "localhost";        // aka: server83.web-hosting.com
$user = "igorrxbi_SD";      // username for our database
$pass = "zYa-2T4-M4U-HK2";  // super secret password for db
$db   = "igorrxbi_SD";      // table name for senior design

$mysqli = new db($host, $user, $pass, $db);
/* stablish and check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* write up query and parameters */
$sql = "SELECT * FROM users WHERE id = ?";
$user_id = 0;



/* prepare statement( $stmt ) */
if( $stmt = $mysqli->prepare($sql) ){

  // objective:
  $stmt->mbind_param('d',$user_id);
  $stmt->execute();


  $stmt->store_result();
  /* bind variables to prepared statement */
  $stmt->bind_result($col1, $col2, $col3, $col4, $col5);
  /* fetch values */
  printf("<table>");
  printf("<tr>");
  printf("<th>id</th>");
  printf("<th>username</th>");
  printf("<th>password</th>");
  printf("<th>igordepaula1@gmail.com</th>");
  printf("<th>xtra</th>");


  while ($stmt->fetch()) {
       printf("<tr>");
       printf("<td>%s</td>", $col1);
       printf("<td>%s</td>", $col2);
       printf("<td>%s%s</td>", substr($col3,0,1), str_repeat("*", strlen($col3) - 1));
       printf("<td>%s</td>", $col4);
       printf("<td>%s</td>", $col5);
       printf("</tr>");
   }

   printf("</tables>");
  $stmt->close();

}
else{
  echo 'Error: ' . $mysqli->error;
  return false;
}

/* Close mysqli connection */
$mysqli->close();


// errors:
// Call to undefined method mysqli_stmt::get_result()
// $result = $stmt->get_result();



/*                great references:
 * http://php.net/manual/en/mysqli-stmt.bind-param.php
 * http://php.net/manual/en/mysqli.prepare.php
 */

 ?>
<style>
table, th, td {
    border: 1px solid #eee;
}
</style>

