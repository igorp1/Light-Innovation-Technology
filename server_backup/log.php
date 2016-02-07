<?php

// get assets:
require("./assets/php/helpers.php");
require("./assets/php/variables.php");

// elements visibility:
$view_button_user   = "block";
$view_button_login  = "none";
$view_button_signup = "none";
$text_user = "block";
$text_email = "none";
$text_pass = "none";
$text_chck = "none";

// greeting:
$greet = "Please, enter your name below...";

//connect to db:
$mysqli = new db($_db_host , $_db_user, $_db_pass, $_db_database);
/* stablish and check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

/* see if anything was submited */
if( isset($_POST['submit_username']) ){

  $view_button_user = "none";
  $userval = $_POST["user"];

  /* look for username in db */
  $sql = "SELECT id FROM users WHERE username = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s',$userval);

  $stmt->execute();

  $stmt->store_result();

  $in_db = $stmt->num_rows >= 1;
  /***************************/
  if( $in_db ){
    $view_button_login  = "block";
    $text_pass = "block";
    $greet = "your password, " . $_POST["user"] . "...";
  }else{
    $view_button_signup = "block";
    $text_email = "block";
    $text_pass = "block";
    $text_chck = "block";
    $greet = "please signup before you continue.";
  }


}elseif ( isset($_POST['submit_login']) ) {   // echo "SUBMIT LOGIN";

  $userval = $_POST['user'];
  $sql = "SELECT id, password, email FROM users WHERE username = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s', $userval);
  $stmt->execute();

  $stmt->store_result();
  /* bind variables to prepared statement */
  $stmt->bind_result($id, $pass_hash, $email);
  $stmt->fetch();


  $pass = md5($_POST['pass']);

  if($pass_hash != $pass){
    $msg = "* Wrong password. *";
    $text_pass = "block";
    $email_val = $_POST['email'];
    $userval = $_POST["user"];
    $view_button_login = "block";
    $view_button_user = "none";
  }
  else{

    // INITIATE SESSION AND REDIRECT:
    session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $userval;
    $_SESSION['user_email'] = $email;

    header('Location: http://igordepaula.com/projects/demo/senior_design/dashboard.php');

  }


}elseif ( isset($_POST['submit_signup']) ) {  // echo "SUBMIT SIGNUP";

  // check values:
  if($_POST['pass'] != $_POST['chck']){

    $msg = " * Password didn't match. * ";

    $email_val = $_POST['email'];

    $text_email = "block";
    $text_pass = "block";
    $text_chck = "block";

  }
  else{
    /************ add to database: ************/

    // prepare statement:
    $sql = "INSERT INTO users(email, password, username ) VALUES(?,?,?)";
    $stmt = $mysqli->prepare($sql);

    // get variables:
    $email = $_POST['email'];
    $username = $_POST['user'];
    $pass = md5($_POST['pass']);

    // bind params
    $stmt->bind_param('sss', $email, $pass, $username);

    // execuate
    $stmt->execute();
    unset($stmt);

    //get user ID:
    $sql = "SELECT `id`, `username`,`email` FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('s',$username);
    $stmt->execute();

    $stmt->store_result();
    /* bind variables to prepared statement */
    $stmt->bind_result($id, $username, $email);
    $stmt->fetch();

    // INITIATE SESSION AND REDIRECT:
    session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $username;
    $_SESSION['user_email'] = $email;

    header('Location: http://igordepaula.com/projects/demo/senior_design/dashboard.php');


  }



}



?>

<HTML>

<head>
  <!-- Bootstrap -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <title>-- LIT --</title>
</head>

<body>

  <div class="container">

    <div class="row">
    <div <div class="col-md-4"></div>
    <div <div class="col-md-4">
      <form class="form-signin" action="log.php" method="post">
        <h2 class="form-signin-heading"><?php echo $greet; ?></h2>
        <p class="form-signin-heading"><?php echo $msg; ?></p>

        <input style="display:<?php echo $text_user;?>" type="username" name="user" value="<?php if(isset($userval)){ echo $userval; } ?>" id="inputUser" class="form-control" placeholder="Username" autofocus="">
        <input style="display:<?php echo $text_email;?>" type="email"   name="email" value="<?php if(isset($email_val)){ echo $email_val; } ?>" id="inputEmail" class="form-control" placeholder="Email">
        <input style="display:<?php echo $text_pass;?>" type="password" name="pass" id="inputPassword" class="form-control" placeholder="Password">
        <input style="display:<?php echo $text_chck;?>" type="password" name="chck" id="inputPassword" class="form-control" placeholder="Confirm Password">




        <!-- BUTTONS -->
        <button class="btn btn-lg btn-primary btn-block" style="display:<?php echo $view_button_user; ?>" type="submit" name="submit_username" id="submit_username" >Go</button>
        <button class="btn btn-lg btn-primary btn-block" style="display:<?php echo $view_button_login; ?>" type="submit" name="submit_login" id="submit_login" >Log In</button>
        <button class="btn btn-lg btn-primary btn-block" style="display:<?php echo $view_button_signup; ?>" ype="submit" name="submit_signup" id="submit_signup" >Sign Up</button>
      </form>
    </div>
    </div>

    </div>

</body>
</HTML>
