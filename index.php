

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!--Sweet Alert JS FILE-->
<script src="plugins/sweetalert/sweetalert.js"></script>

<!--php code-->

<?php
include_once 'connectdb.php';
session_start();

if(isset($_POST['btn_login'])){
    $useremail=$_POST['txt_email'];
    $password=$_POST['txt_password'];
    
    
//    echo $useremail."-".$password;
    
    $select = $pdo->prepare("select * from tbl_user where useremail='$useremail' AND password='$password'");
    
    $select->execute();
    
    $row=$select->fetch(PDO::FETCH_ASSOC);
    
    if($row['useremail']==$useremail AND $row['password']==$password AND $row['role']=="Admin"){

    $_SESSION['userid']=$row['userid'];
    $_SESSION['username']=$row['username'];
    $_SESSION['useremail']=$row['useremail'];
    $_SESSION['role']=$row['role'];
        
    
//    echo $success="Login Successful";
        
        //using sweetalert.js
        
         echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Welcome '.$_SESSION['username'].'",
        text: "You are Logged In!",
        icon: "success",
        button: "Loading...",
        });
        
        });
        
        </script>
        ';
        
        header('refresh:2;dashboard.php');//to redirect us to the dashboard page if login successful.
        
        
    }
    else if($row['useremail']==$useremail AND $row['password']==$password AND $row['role']=="User"){   
        
    $_SESSION['userid']=$row['userid'];
    $_SESSION['username']=$row['username'];
    $_SESSION['useremail']=$row['useremail'];
    $_SESSION['role']=$row['role'];
        
//       $success="Login Successful";
        
        //using sweetalert
        echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Welcome!",
        text: "You are Logged In!",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
        
        header('refresh:2;user.php');//to redirect us to the user page if login successful.
    
    }
    else{
//        echo "incorrect credentials";
        echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning",
        text: "Incorrect Password or Email!",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
    }
    
}

?>





<!--html markup-->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RoverTechPOS 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="index.php"><b>RoverTech</b>POS</a>
  </div>
  <!-- /.login-logo -->
  <div class="card card-login">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Login to Proceed</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input name="txt_email" type="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="txt_password" type="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
          <!-- /.col -->
          <div class="button-wrapper">
            <button name="btn_login" type="submit" class="btn btn-primary btn-block ">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
        <a href="#" onclick="swal('To Retrieve Password','Please Contact Admin or Service Provider','error')">Forgot Password</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->


</body>
</html>
