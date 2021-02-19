<?php 
include_once 'connectdb.php';

session_start();
if($_SESSION['useremail']=="" OR $_SESSION['role']=="Admin"){
    header('location:index.php');
}


include_once 'headeruser.php'; 
?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>User Dashboard</h1>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



 <?php include_once 'footer.php'; ?>
