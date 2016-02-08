<?php

/************ The controller: **************/
function getController($id){
$str = <<<EOF
  <button type="button" class="btn btn-info btn-md" id="B_lit_{$id}" data-toggle="modal" data-target="#ctrModal" onclick="fetchID(this)">
    <i class="fa fa-lightbulb-o"></i>&nbsp;Control
  </button>
EOF;

return $str;

}
/******************************************/



require("./assets/php/helpers.php");
require("./assets/php/variables.php");

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
    <title>-- LIT: dashboard --</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://igordepaula.com/projects/demo/senior_design/assets/js/jquery.knob.js"></script>
    <script src="http://igordepaula.com/projects/demo/senior_design/assets/js/underscore.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
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
      $mysqli = new db($_db_host , $_db_user, $_db_pass, $_db_database);
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
      <p><strong>All</strong> your devices in one place.</p><br>
        <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-3" style="border-left-style: solid;border-left-color: #2389af">
            <p>
              <button type="button" class="btn btn-info btn-md" id="G_lit_" data-toggle="modal" data-target="#ctrModal" onclick="fetchID(this)">
                <i class="fa fa-arrows"></i>&nbsp;Move
              </button>
            </p>
            <p>
              <button type="button" class="btn btn-info btn-md" id="G_lit_" data-toggle="modal" data-target="#ctrModal" onclick="fetchID(this)">
                <i class="fa fa-sitemap"></i>&nbsp;Group
              </button>
            </p>
            <p>
              <button type="button" class="btn btn-info btn-md" id="G_lit_" data-toggle="modal" data-target="#ctrModal" onclick="fetchID(this)">
                <i class="fa fa-trash-o"></i>&nbsp;Delete
              </button>
            </p>


          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4" style="border-left-style: solid;border-left-color: #2389af">
            <p>Your groups: </p>
            <?php
              # code ...
              /*
              Sample on inner join:

              SELECT s.id, s.name, sp.unit_id, sp.pos_X, sp.pos_y, sp.width FROM setups AS s
              INNER JOIN setup_positions AS sp
              ON sp.setup_id = s.id

              */
              // CONNECT MYSQL
              $mysqli = new db($_db_host , $_db_user, $_db_pass, $_db_database);
              /* stablish and check connection */
              if (mysqli_connect_errno()) {
                  printf("Connect failed: %s\n", mysqli_connect_error());
                  exit();
              }

              /* write up query and parameters */
              $sql = "SELECT id, name FROM `setups` WHERE owner=?";

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
                $stmt->bind_result($setup_id, $setup_name);


                /* fetch values */
                $c = 0;
                while ($stmt->fetch()) {

                echo "<div class='row'>";
                echo "<div class='col-md-3'><p><strong>{$setup_name}</strong></div>";

                echo "<div class='col-md-3'><button type='button' class='btn btn-info btn-sm' id='S_lit_{$setup_id}' data-toggle='modal' data-target='#' onclick=''>";
                echo '<i class="fa fa-caret-square-o-right"></i></button></p></div>';
                echo '</div>';


                  $c++;
                }

                if($c == 0){
                  echo 'To create a group just choose the action &nbsp;&nbsp;<i class="fa fa-sitemap"></i>&nbsp;<i>Group</i>';
                }

                $stmt->close();

              }else{
                echo 'Error: ' . $mysqli->error;
                return false;
              }


             ?>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>




</div>

<br><br><br><br>
<div style="font-size:10px;text-align:right;padding-right:10px;">
<p>Sftwr dev <a target="_blank" href="http://igordepaula.com">@idp</a></p>
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
      <p></p>
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
      <p>Move the yellow circle around the grey one. As you move it around, the ligh direction will replicate the direction you moved the circle towards.</p>
      <center><img src="http://igordepaula.com/projects/demo/senior_design/assets/img/controller.png" height="" width="60%" style="box-shadow: 10px 10px 5px #BBBBBB;"></center>
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
      <h4 class="modal-title" id="control_modal_title">Control</h4>
    </div>
    <div id="modal_ctrl_body" class="modal-body">
      <!-- SOME CRAZY ABOUT TO GO HERE FOR THE CONTROLLER-->
      <center>
        <table>
        <tr >
          <th style="padding:20px">
            <input type="text" class="dial" id="X-axis"
              value="0"
              data-thickness=".3"
              data-linecap=round
              data-cursor=true
              data-step="5"
              data-min="-100"
              data-max="100"
              data-width="200"
            ><br>
          </th>
          <th style="padding:20px">
            <input type="text" class="dial" id="Y-axis"
              value="0"
              data-thickness=".3"
              data-linecap=round
              data-cursor=true
              data-step="5"
              data-min="-100"
              data-max="100"
              data-width="200"
            ><br>
          </th>
        </tr>
        <tr>
          <td><center>X</center></td>
          <td><center>Y</center></td>
        </tr>
      </table>
      </center>


      <br>
      <center>

      <input type="text" class="dial"
        value="0"
        data-linecap=round
        data-cursor=true
        data-step="1"
        data-min="0"
        data-max="10"
        data-width="150"
        data-thickness=".3"
        data-fgColor="#AAAAAA"
        data-bgColor="#EEEEEE"
      ><br>Size
    </center>
      <!--------------------------------->
    </div>
  </div>

</div>
</div>
<!--------------------------------------------------------------------->


<!---------------------- JAVASCRIPT RIGHT HERE ------------------------>
<script>

/********* CONTROL VARIABLES ***********/
var control_signal = {
  u: [],                  //      # control units IDs on db
  x_position: 0,          //      # y position of beam
  y_position: 0,          //      # x position of beam
  beam_width: 0,          //      # width of the beam
};
/***************************************/

/************* TIMER FOR SENDING CTRL VALUES ******************/
var redirect=_.debounce(function() {
    /* AJAX REQUEST GOES HERE */
    $.post( "/assets/js/", control_signal)
  .done(function( data ) {
    alert( "Data Loaded: " + data );
  });
    /**************************/
},2000); // waits 2 seconds before sending the ajax
/*************************************************/

/***************** CONTROL DIALS ******************/
$(function() {
    $(".dial").knob({
      'release' : function (v) {
        redirect();
     }
    });
});
/************************************************/

/****************** OPEN MODAL *****************/
function fetchID(b){

  // check where the modal was open:
  if($(b)[0].id[0] == "B"){

    // get value from textbox
    var row = $(b).parent().parent()
    var element = row.children()[0];
    var selected = element.children[0].checked;
    var id_num = element.children[0].id;

    // get unit name
    var name_unit = row.children()[1].innerHTML;

    // set the info on the modal title
    $("#control_modal_title")[0].innerHTML = "<strong>Control_</strong> " + name_unit;

    // define a couple control variables
    control_state = "single"; // controlling a single device
    control_units = parseInt(id_num.replace("C_lit_",""));

    // populate control_signal
    control_signal.u = control_units;

  }else if ($(b)[0].id[0] == "G") {


  }

}
/************************************************/


</script>

</HTML>
