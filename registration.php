<?php
include_once 'connectdb.php';//because we need to use pdo object

session_start();
if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){
    header('location:index.php');
}
include_once 'header.php';



//this is for deleting user or admin from the displayed table trash button


if($_GET['id']){

//    echo $_GET['id'];
    
$id=$_GET['id'];

$delete=$pdo->prepare("delete from tbl_user where user_id=".$id);

$delete->execute();

if($delete->rowCount()){
    echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "User Deleted",
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
        title: "Error",
        text: "Could not delete user",
        icon: "error",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
}
}

//End if delete


//New user registration starts here


if(isset($_POST['btnsave'])){

$username=$_POST['txtname'];
$useremail=$_POST['txtemail'];
$password=$_POST['txtpassword'];
$userrole=$_POST['txtselect_option'];
    
    //now checking if the email we are going to register already exists or not , if yes then we cannot register with same mail id a new user again.only one user from one email id
if(isset($_POST['txtemail'])){
    
$select=$pdo->prepare("select useremail from tbl_user where useremail='$useremail'");
    
$select->execute();
    
    if($select->rowCount() > 0){
        echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning",
        text: "Email Already Registered",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
    }
    else{

$insert=$pdo->prepare("insert into tbl_user(username,useremail,password,role) values(:name,:email,:password,:role)"); 

    
$insert->bindParam(':name',$username);
$insert->bindParam(':email',$useremail);
$insert->bindParam(':password',$password);
$insert->bindParam(':role',$userrole);
    
    $insert->execute();
    
    if($insert->rowCount() AND $userrole=="User"){
       
         echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success!",
        text: "User Successfully Registered",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
        
    }
     else if($insert->rowCount() AND $userrole=="Admin"){
       
         echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success!",
        text: "Admin Successfully Registered",
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
        title: "Technical Glitch",
        text: "Please Contact Admin",
        icon: "error",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
        
    }
}
}
}//user registration ends
?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User Registration</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      <div class="container-fluid-reg">
      
      <div class="col-md-4">
          
           <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registration Form</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" action="">
                <div class="card-body">
                 <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" name="txtname" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="txtemail" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtpassword" required>
                  </div>
                  <div class="form-group">
                  <label for="exampleSelectBorder">Role</label>
                  <select class="custom-select form-control-border" id="exampleSelectBorder" name="txtselect_option" required>
                    <option value="empty" disabled selected>Select Role</option>
                    <option>Admin</option>
                    <option>User</option>
                  </select>
                </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button name="btnsave" type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          
      </div>
      
      
      <div class="col-md-8">
          
          <table class="table table-striped">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Password</th>
                      <th>Role</th>
                      <th>Delete</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  $select=$pdo->prepare("select * from tbl_user order by user_id desc");
                  
                  $select->execute();
                  
                  while($row=$select->fetch(PDO::FETCH_OBJ)){
                      echo '
                      <tr>
                      <td>'.$row->user_id.'</td>
                      <td>'.$row->username.'</td>
                      <td>'.$row->useremail.'</td>
                      <td>'.$row->password.'</td>
                      <td>'.$row->role.'</td>
                      <td>
                      <a href="registration.php?id='.$row->user_id.'" class="btn btn-danger" role="button"><i class="fas fa-trash-alt"></i></a>
                      </td>
                      </tr>
                      ';
                  }
                  ?>
                  
              </tbody>
          </table>
          
      </div>
      
      
      </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



 <?php include_once 'footer.php'; ?>
