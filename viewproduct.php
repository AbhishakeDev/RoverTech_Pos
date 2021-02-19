<?php
include_once 'connectdb.php';
session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == 'User') {
    header('location:index.php');
}

include_once 'header.php'; ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Product</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <a style="margin-bottom:20px;" href="productlist.php?" class="btn btn-primary" role="button">Back To Product List</a>
            <div class="row">
                <?php
                $id = $_GET['id'];
                $select = $pdo->prepare("select * from tbl_product where pid=" . $id);
                $select->execute();
                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                    echo '
                <div class="col-md-6">
                
                <ul class="list-group">
                <center><p class="list-group-item list-group-item-success"><b>Product Detail</b></p></center>
                <li class="list-group-item"><b>ID :</b><span  style="float:right;" class="badge">' . $row->pid . '</span></li>
                <li class="list-group-item"><b>Product Name :</b><span style="float:right;" class="label label-info pull-right">' . $row->pname . '</span></li>
                <li class="list-group-item"><b>Product Category :</b><span style="float:right;" class="label label-primary pull-right">' . $row->pcategory . '</span></li>
                <li class="list-group-item"><b>Purchase Price :</b><span style="float:right;" class="label label-warning pull-right">' . $row->purchaseprice . '</span></li>
                <li class="list-group-item"><b>Sale Price :</b><span style="float:right;" class="label label-warning pull-right">' . $row->saleprice . '</span></li>
                <li class="list-group-item"><b>Product Profit :</b><span style="float:right;" class="label label-success pull-right">' . ($row->saleprice - $row->purchaseprice) . '</span></li>
                <li class="list-group-item"><b>Stock :</b><span style="float:right;" class="label label-danger pull-right">' . $row->pstock . '</span></li>
                <li class="list-group-item"><b>Description:  </b> <span  class="">' . $row->pdescription . '</span></li>
                </ul>
                </div>

                <div class="col-md-6">
                
                <ul class="list-group">
                <center><p class="list-group-item list-group-item-success"><b>Product Detail</b></p></center>
                <img src="productimages/' . $row->pimage . '" class="img-responsive"/>
                </ul>

                </div>

                ';
                }
                ?>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->



<?php include_once 'footer.php'; ?>