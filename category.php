<?php
include_once 'connectdb.php';

session_start();
if($_SESSION['useremail']=="" OR $_SESSION['role']=="User"){
    header('location:index.php');
}

include_once 'header.php';

if ( isset( $_POST['btnsave'] ) ) {
    $category = $_POST['txtcategory'];

    if ( !empty( $category ) ) {

        $insert = $pdo->prepare( "insert into tbl_category(category) values(:category)" );

        $insert->bindParam( ':category', $category );

        $insert->execute();

        if ( $insert->rowCount() ) {

            echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "Category Inserted",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';

        }

    } else {
        echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning!",
        text: "Field is Empty",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
    }
}
//adding new data ends here

//For Updating data

if ( isset( $_POST['btnupdate'] ) ) {

    $category = $_POST['txtcategory'];

    $id = $_POST['txtid'];
    //can place btnedit as well as txtid both will return the catid only
    //     echo $id.$category;

    if ( empty( $category ) ) {

        $errorupdate = '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Warning!",
        text: "Field is Empty",
        icon: "warning",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
        echo $errorupdate;

    }
    if ( !isset( $errorupdate ) ) {

        $update = $pdo->prepare( "update tbl_category set category=:category where catid=".$id );
        $update->bindParam( ':category', $category );
        $update->execute();

        if ( $update->rowCount() ) {
            echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "Category Updated",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';

        } else {
            echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Technical Glitch!",
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
// end of updating category

if(isset($_POST['btndelete'])){
    
    $delete=$pdo->prepare("delete from tbl_category where catid=".$_POST['btndelete']);
    
    $delete->execute();
    
    if($delete->rowCount()){
            echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "Category Deleted Successfully",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
    }
    
}

?>

<!-- Content Wrapper. Contains page content -->
<div class = "content-wrapper">
<!-- Content Header ( Page header ) -->
<div class = "content-header">
<div class = "container-fluid">
<div class = "row mb-2">
<div class = "col-sm-6">
<h1 class = "m-0">Category</h1>
</div><!-- /.col -->
</div><!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class = "content">
<div class = "container-fluid">

<!-- form start -->
<form role = "form" method = "post" action = "">
<div class = "row">
<div class = "col-md-4">
<?php
if ( isset( $_POST['btnedit'] ) ) {

    $select = $pdo->prepare( "select * from tbl_category where catid=".$_POST['btnedit'] );

    $select->execute();

    if ( $select->rowCount() ) {

        $row = $select->fetch( PDO::FETCH_OBJ );

        echo ' <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Category</h3>
              </div>
              <!-- /.card-header -->
                 <div class="card-body">
                 <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="hidden" class="form-control" id="exampleInputEmail1" placeholder="Enter Category" name="txtid" value="'.$row->catid.'">
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Category" name="txtcategory" value="'.$row->category.'">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button name="btnupdate" type="submit" class="btn btn-primary" value="'.$row->catid.'">Update</button>
                </div>
            </div>';

    }

} else {

    echo ' <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Category Form</h3>
              </div>
              <!-- /.card-header -->
                 <div class="card-body">
                 <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Category" name="txtcategory">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button name="btnsave" type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>';

}
?>

</div>

<div class = "col-md-8">

<table id="tablecategory" class = "table table-striped">
<thead>
<tr>
<th>#</th>
<th>Category Name</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tbody>

<?php

$select = $pdo->prepare( "select * from tbl_category order by catid desc");

$select->execute();

while( $row = $select->fetch( PDO::FETCH_OBJ ) ) {

    echo '<tr>
                      <td>'.$row->catid.'</td>
                      <td>'.$row->category.'</td>
                      <td><button name="btnedit" value="'.$row->catid.'" type="submit" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</button></td>
                      <td><button name="btndelete" value="'.$row->catid.'" type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button></td>
                      </tr>';

}

?>
</tbody>
</table>
</div>
</div>
</form>
</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--calling datatables-->
<script>

$(document).ready( function () {
    $('#tablecategory').DataTable();
} );

</script>

<?php include_once 'footer.php';
?>
