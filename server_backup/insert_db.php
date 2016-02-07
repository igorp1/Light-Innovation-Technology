<?php

require("./assets/php/helpers.php");
require("./assets/php/variables.php");


$mysqli = new db($_db_host, $_db_user, $_db_pass, $_db_database);
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
