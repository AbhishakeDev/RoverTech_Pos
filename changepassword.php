<?php
include_once 'connectdb.php';//because we need to use pdo object

session_start();
if($_SESSION['useremail']==""){
    header('location:index.php');
}

if($_SESSION['role']=="User"){
    include_once 'headeruser.php'; 
}
else{
    include_once 'header.php'; 
}
//when click on update password we get our values from user into variables

if(isset($_POST['btnupdate'])){
    $oldpassword=$_POST['txtoldpass'];
    $newpassword=$_POST['txtnewpass'];
    $confpassword=$_POST['txtconfpass'];


//using of select query we get out database record according to user mail
$email=$_SESSION['useremail'];
    
    $select=$pdo->prepare("select * from tbl_user where useremail='$email'");
    
    $select->execute();
    $row=$select->fetch(PDO::FETCH_ASSOC);
    
    $useremail_db=$row['useremail'];
    $password_db=$row['password'];

//we compare userinput and data base values
    if($oldpassword == $password_db){
       //if value matched then we run update query
        
        if($newpassword == $confpassword){
            
            $update=$pdo->prepare("update tbl_user set password=:pass where useremail=:email");
            
            $update->bindParam(':pass',$confpassword);
            $update->bindParam(':email',$email);
            
            if($update->execute()){
                echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "Password Changed Successfully",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
            }
            else{
                
                echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Error Changing Password",
        text: "Please contact Admin",
        icon: "error",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
                
            }
            
        }
        else{
            
            echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning",
        text: "New Passwords do not match",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
            
        }
        
    }
    else{
        
         echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning",
        text: "Please enter correct password",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
        
    }



}
?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <center>
<div style="width:100%;height:auto;padding:10px;" class="card">

    <!-- Content Header (Page header) -->
    <div style="margin-bottom: 30px;" class="content-header">
      <div class="container-fluid">
        
            <h1 class="m-0">Change Password Panel</h1>
         
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="card card-change card-primary">
              <div  class="card-header">
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputPassword1">Old Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtoldpass" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">New Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtnewpass" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Confirm Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtconfpass" required>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="btnupdate">Update</button>
                </div>
              </form>
            </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    </center>
  </div>
  <!-- /.content-wrapper -->



 <?php include_once 'footer.php'; ?>
