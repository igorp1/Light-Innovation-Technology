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

/* make users */
$names  = array("Neo", "Morpheus", "John Appleseed");
$emails = array("neo@matrix.net", "mpfarm@matrix.net", "johnappleseed@apple.com");
/******/

/* write up query and parameters */
$sql = "INSERT INTO users(email, password, username ) VALUES(?,?,?)";
$stmt = $mysqli->prepare($sql);


for($i = 0; $i < count($names); $i++){

  // get variables:
  $email = $emails[$i]; var_dump($email);
  $username = $names[$i]; var_dump($username);
  $pass = generate_password();
  // bind params
  $stmt->bind_param('sss', $email, $pass, $username); var_dump($pass);


  $stmt->execute();
  printf("<br>");
  printf("%d Row inserted.\n", $stmt->affected_rows);
  printf($mysqli->error);
  printf("<br>");printf("<br>");

}

// Add everything:







/* HELPER */

function generate_password( $length = 8 ) {
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$password = substr( str_shuffle( $chars ), 0, $length );
return $password;
}






?>

