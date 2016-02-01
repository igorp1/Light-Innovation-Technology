<?php

require("./assets/helpers.php");

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

$mysqli->close();



?>
