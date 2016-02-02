<?php

/************ The controller: **************/
function getController($id){
$str = <<<EOF
  <button type="button" class="btn btn-info btn-md" id="B_lit_{$id}" data-toggle="modal" data-target="#ctrModal">Control <i class="fa fa-lightbulb-o"></i></button>
EOF;

return $str;

}
/******************************************/



require("./assets/helpers.php");
$host = "localhost";        // aka: server83.web-hosting.com
$user = "igorrxbi_SD";      // username for our database
$pass = "zYa-2T4-M4U-HK2";  // super secret password for db
$db   = "igorrxbi_SD";      // table name for senior design

session_start();

// check if there is a session:
if( !isset($_SESSION[user_id]) ){
  header("location: http://igordepaula.com/projects/demo/senior_design/log.php");
}

// debug:
//var_dump($_SESSION);

?>

<HTML>
  <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

    <title>-- LIT: dashboard --</title>
  </head>

  <body style="background-color:#FFF; font-family: 'Raleway', sans-serif; ">
    <div class="container">

      <!-- HEADER -->
      <div class="page-header">
        <h1>LIT: user dashboard</h1>
        <p class="lead">Use this dashboard to manage your account and control your devices.</p>
      </div>

      <!-- get started -->
      <h3>Where to get started_ </h3>
      <p>Some <strong>resources</strong> to get you quickly to uselful functions.</p>
      <br><br>
      <div class="row">
        <div class="col-md-4" style="text-align: center;">
          <i class="fa fa-plus-circle" style="font-size:60px"></i><br>
          <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#addModal">Add device!</button>
        </div>
        <div class="col-md-4" style="text-align: center;">
          <i class="fa fa-user" style="font-size:60px"></i><br>
          <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#accModal">Manage account.</button>
        </div>
        <div class="col-md-4" style="text-align: center;">
          <i class="fa fa-lightbulb-o" style="font-size:60px"></i><br>
          <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#abtModal">About.</button>
        </div>
      </div>

      <br><br><br>

      <!--  -->
      <h3>Quick control_ </h3>
      <p>Take control of your <strong>devices</strong> from here.</p>
      <br>
      <?php

      // CONNECT MYSQL
      $mysqli = new db($host, $user, $pass, $db);
      /* stablish and check connection */
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }

      /* write up query and parameters */
      $sql = "SELECT name, addr, id, position_x, position_y, status FROM `units` WHERE owner=?";

      /* WHICH USER DO YOU WANT: */
      $user_id = $_SESSION["user_id"];

      //
      /* prepare statement( $stmt ) */
      if( $stmt = $mysqli->prepare($sql) ){
      //
        // objective:
        $stmt->mbind_param('d',$user_id);
        $stmt->execute();

        $stmt->store_result();
        /* bind variables to prepared statement */
        $stmt->bind_result($name,$addr, $device_id, $position_x, $position_y, $status);
        /* fetch values */

        echo '<div style="font-size:22px;" id="D_lit_">';
        while ($stmt->fetch()) {
          // start row:
          echo '<div class="row" style="padding:5px;border-bottom: 2px solid #EEE;">';

          // checkbox
          echo '<div class="col-md-1" align="center">';
          echo '<input type="checkbox" id=C_lit_' . $device_id . ">";
          echo '</div>';

          // device "name"
          echo '<div class="col-md-2" align="left">';
          echo $name;
          echo '</div>';

          // device "ip"
          echo '<div class="col-md-1" align="left">';
          echo $addr;
          echo '</div>';

          // device status
          echo '<div class="col-md-1" align="center">';
          if($status == 1) { echo '<i class="fa fa-bullseye" style="color:#00ff55"></i>'; }
          elseif ($status == 2) { echo '<i class="fa fa-bullseye" style="color:#1a8cff"></i>'; }
          elseif ($status == 3) { echo '<i class="fa fa-bullseye" style="color:#ff3333"></i>'; }
          echo '</div>';

          // X and Y positions
          echo '<div class="col-md-1" align="center">';
          echo $position_x;
          echo '</div>';
          echo '<div class="col-md-1" align="center">';
          echo $position_y;
          echo '</div>';

          // the controller for the device
          echo '<div class="col-md-3" align="left">';
          echo getController($device_id);
          echo '</div>';
          echo "</div>";
        }
        echo '</div>';

        $stmt->close();
      }
      else{
        echo 'Error: ' . $mysqli->error;
        return false;
      }
      ?>

      <br><br>
      <h3>Group Setup_ </h3>
      <p>You can control <strong>multiple</strong> devices and store setups to use later.</p>
      <br><br>
        <div class="row">
          <div class="col-md-4">
            wait on it...
          </div>
        </div>
      </div>




</div>

  </body>

  <!--------------- MODAL FOR ACCOUNT MANAGEMENT --------------->
  <div id="accModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Account</h4>
      </div>
      <div class="modal-body">
        Username: <?php echo $_SESSION["user_name"]; ?><br><br>
        Email: <?php echo $_SESSION["user_email"]; ?> <br><br>
        <a href="http://igordepaula.com/projects/demo/senior_design/logout.php">Sign out.</a>
      </div>
    </div>

  </div>
</div>
<!---------------------------------------------------------------->

<!--------------- MODAL FOR ADD DEVICE --------------->
<div id="addModal" class="modal fade" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">New device</h4>
    </div>
    <div class="modal-body">
      <p> wait on it.</p>
    </div>
  </div>

</div>
</div>
<!---------------------------------------------------------------->

<!--------------- MODAL FOR ABOUT --------------->
<div id="abtModal" class="modal fade" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">About</h4>
    </div>
    <div class="modal-body">
      <p><strong>The control</strong></p>
      <p>Move the yellow circle around the grey one. As you move it around, the ligh direction will replicate the direction you moved the circle towards.</p><br>
      <img src="" height="" width="">
      <br>

      <p><strong>The status</strong></p>
      <p>There are a few possible status options for the lights.</p>
      <p>- OK: your device is up and running.&nbsp;<i class="fa fa-bullseye" style="color:#00ff55"></i></p>
      <p>- OFF: just flip the switch and we are good to go.&nbsp;<i class="fa fa-bullseye" style="color:#1a8cff"></i></p>
      <p>- UNREACHABLE: we are having a connectivity issue.&nbsp;<i class="fa fa-bullseye" style="color:#ff3333"></i></p>
      <br>

      <p><strong>The Groups</strong></p>
      <p>At the bottom you will find controls to act on a collection of devices.
        You can use those controls to setup groups of devices and create defined layouts.
        Groups could also be used to quickly used to control several lights at the same time.
      </p><br>
      <div class="modal-footer">
        <a target="_blank" href="https://docs.google.com/document/d/1WhqY5YRCtOqpnpdnvNsOofP2qyVHINs9XAyrwVNl3Xo/edit?usp=sharing">I need more help.</a>
      </div>
    </div>
  </div>

</div>
</div>
<!---------------------------------------------------------------->


<!------------------------ MODAL FOR CONTROLLER ----------------------->
<div id="ctrModal" class="modal fade" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Control</h4>
    </div>
    <div class="modal-body">
      <!-- SOME CRAZY ABOUT TO GO HERE -->
      ;)
      <!--------------------------------->
    </div>
  </div>

</div>
</div>
<!--------------------------------------------------------------------->



</HTML>
