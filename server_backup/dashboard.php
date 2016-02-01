<?php

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

      <?php

      // CONNECT MYSQL
      $mysqli = new db($host, $user, $pass, $db);
      /* stablish and check connection */
      if (mysqli_connect_errno()) {
          printf("Connect failed: %s\n", mysqli_connect_error());
          exit();
      }

      /* write up query and parameters */
      $sql = "SELECT * FROM units WHERE user_id = ?";

      /* WHICH USER DO YOU WANT: */
      $user_id = rand(1,6);


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
        printf("<th>email</th>");
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



      # populate this row with devices:
      $c = 1;
      while(true){
        echo '<div class="row">';
        echo '<div class="col-md-1">';
        echo "[ ]";
        echo '</div>';
        echo '<div class="col-md-1">';
        echo "Device " . $c;
        echo '</div>';
        echo '<div class="col-md-1">';
        echo "STUFF 1";
        echo '</div>';
        echo '<div class="col-md-1">';
        echo "STUFF 2";
        echo '</div>';
        echo '<div class="col-md-1">';
        echo "STUFF 3";
        echo '</div>';
        echo "</div>";

        if($c++ == 10){ break; }

      }

      ?>


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

<!--------------- ADD DEVICE --------------->
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

<!--------------- ABOUT --------------->
<div id="abtModal" class="modal fade" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">About</h4>
    </div>
    <div class="modal-body">
      <p><strong>The control</strong></p><br>
      <p>Move the yellow circle around the grey one. As you move it around, the ligh direction will replicate the direction you moved the circle towards.</p><br>
      <img src="" height="" width="">
      <br>
      <br>

      <p><strong>The status</strong></p><br>
      <p>There are a few possible status options for the lights.</p><br>
      <p>- OK: your device is up and running.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:#00ff55"></i></p>
      <p>- OFF: just flip the switch and we are good to go.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:#1a8cff"></i></p>
      <p>- UNREACHABLE: we are having a connectivity issue.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:#ff3333"></i></p>
      <br>
      <br>

      <p><strong>The Groups</strong></p><br>
      <p>At the bottom you will find controls to act on a collection of devices.
        You can use those controls to setup groups of devices and create defined layouts.
        Groups could also be used to quickly used to control several lights at the same time.
      </p><br>
      <div class="modal-footer">
        <a target="_blank" href="https://docs.google.com/document/d/1WhqY5YRCtOqpnpdnvNsOofP2qyVHINs9XAyrwVNl3Xo/edit?usp=sharing">I need more help.</a>
      </div>
    </div>
    <!-- <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-sign-out"></i></button>
    </div> -->
  </div>

</div>
</div>
<!---------------------------------------------------------------->





</HTML>
