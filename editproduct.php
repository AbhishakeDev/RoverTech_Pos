<?php
include_once 'connectdb.php';

session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == 'User') {
    header('location:index.php');
}

include_once 'header.php';


//Code for placing at placeholder

$id = $_GET['id'];

$select = $pdo->prepare("select * from tbl_product where pid=" . $id);

$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['pid'];

// echo $id_db;

$productname_db = $row['pname'];
$category_db = $row['pcategory'];
$purchaseprice_db = $row['purchaseprice'];
$saleprice_db = $row['saleprice'];
$stock_db = $row['pstock'];
$description_db = $row['pdescription'];
$productimage_db = $row['pimage'];


//for updation of product




if (isset($_POST['btnupdate'])) {
    $productname_txt = $_POST['txtpname'];
    $category_txt = $_POST['txtselect_option'];
    $purchaseprice_txt = $_POST['txtpprice'];
    $saleprice_txt = $_POST['txtsaleprice'];
    $stock_txt = $_POST['txtstock'];
    $description_txt = $_POST['txtdescription'];

    $f_name = $_FILES['myfile']['name'];

    //if new photo is uploaded
    if (!empty($f_name)) {

        $f_tmp = $_FILES['myfile']['tmp_name'];

        $f_size = $_FILES['myfile']['size'];
        $f_extension = strtolower(end(explode('.', $f_name)));


        $f_newfile = uniqid() . '.' . $f_extension;
        $store = "productimages/" . $f_newfile;
        if ($f_extension == 'jpeg' || $f_extension == 'jpg' || $f_extension == 'png') {
            if ($f_size >= 1000000) {
                $error = '
            <script type="text/javascript">
            jQuery(function validation(){
            
            swal({
            title: "Error!",
            text: "max file size is 1MB",
            icon: "warning",
            button: "Ok",
            });
            
            });
            
            </script>
            ';
                echo $error;
            } else {
                if (move_uploaded_file($f_tmp, $store)) {
                    if (!isset($error)) {

                        $update = $pdo->prepare("update tbl_product set pname=:pname, pcategory=:pcategory, purchaseprice=:purchaseprice, saleprice=:saleprice, pstock=:pstock, pdescription=:pdescription, pimage=:pimage where pid = $id");
                        $update->bindParam(':pname', $productname_txt);
                        $update->bindParam(':pcategory', $category_txt);
                        $update->bindParam(':purchaseprice', $purchaseprice_txt);
                        $update->bindParam(':saleprice', $saleprice_txt);
                        $update->bindParam(':pstock', $stock_txt);
                        $update->bindParam(':pdescription', $description_txt);
                        $update->bindParam(':pimage', $f_newfile);

                        if ($update->execute()) {
                            echo '
            <script type="text/javascript">
                jQuery(function validation() {
                    swal({
                      title: "Updated!!!",
                      text: "The product is updated successfully.",
                      icon: "success",
                      button: "Ok",
                    });
                });
            </script>';
                        } else {
                            echo '
            <script type="text/javascript">
                jQuery(function validation() {
                    swal({
                      title: "Error!",
                      text: "The product is not updated.",
                      icon: "error",
                      button: "Ok",
                    });
                });
            </script>';
                        }
                    }
                }
            }
        } else {
            echo '
            <script type="text/javascript">
            jQuery(function validation(){
            
            swal({
            title: "Warning!",
            text: "upload correct extension",
            icon: "error",
            button: "Ok",
            });
            
            });
            
            </script>
            ';
        }
    }
    //if we dont want to update the old pimage
    else {
        $update = $pdo->prepare("update tbl_product set pname=:pname, pcategory=:pcategory, purchaseprice=:purchaseprice, saleprice=:saleprice, pstock=:pstock, pdescription=:pdescription, pimage=:pimage where pid = $id");
        $update->bindParam(':pname', $productname_txt);
        $update->bindParam(':pcategory', $category_txt);
        $update->bindParam(':purchaseprice', $purchaseprice_txt);
        $update->bindParam(':saleprice', $saleprice_txt);
        $update->bindParam(':pstock', $stock_txt);
        $update->bindParam(':pdescription', $description_txt);
        $update->bindParam(':pimage', $productimage_db);

        if ($update->execute()) {
            echo '
            <script type="text/javascript">
                jQuery(function validation() {
                    swal({
                      title: "Updated!!!",
                      text: "The product is updated successfully.",
                      icon: "success",
                      button: "Ok",
                    });
                });
            </script>';
        } else {
            echo '
            <script type="text/javascript">
                jQuery(function validation() {
                    swal({
                      title: "Error!",
                      text: "The product is not updated.",
                      icon: "error",
                      button: "Ok",
                    });
                });
            </script>';
        }
    }
}

//to show latest updated values
$id = $_GET['id'];

$select = $pdo->prepare("select * from tbl_product where pid=" . $id);

$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['pid'];

// echo $id_db;

$productname_db = $row['pname'];
$category_db = $row['pcategory'];
$purchaseprice_db = $row['purchaseprice'];
$saleprice_db = $row['saleprice'];
$stock_db = $row['pstock'];
$description_db = $row['pdescription'];
$productimage_db = $row['pimage'];

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Update Product</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <form enctype="multipart/form-data" role="form" method="post" action="" name="productform">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Product Form</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
                <a style="margin-bottom:20px;" href="productlist.php?" class="btn btn-primary" role="button">Back To Product List</a>
                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Product Name</label>
                            <input type="text" value="<?php echo $productname_db; ?>" class="form-control" id="exampleInputEmail1" placeholder="Enter Product Name" name="txtpname" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Purchase Price</label>
                            <input type="number" min="1" step="1" value="<?php echo $purchaseprice_db; ?>" class="form-control" id="exampleInputEmail1" placeholder="Purchase Price" name="txtpprice" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Sale Price</label>
                            <input type="number" min="1" step="1" value="<?php echo $saleprice_db; ?>" class="form-control" id="exampleInputEmail1" placeholder="Sale Price" name="txtsaleprice" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelectBorder">Category</label>
                            <select class="custom-select form-control-border" id="exampleSelectBorder" name="txtselect_option" required>
                                <option value="empty" disabled selected>Select Category</option>

                                <?php

                                $select = $pdo->prepare("select * from tbl_category order by catid desc");
                                $select->execute();

                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                ?>
                                    <option <?php if ($row['category'] == $category_db) { ?> selected="selected" <?php } ?>>
                                        <?php echo $row['category']; ?>
                                    </option>
                                <?php

                                }

                                ?>


                            </select>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Stock </label>
                            <input type="number" min="1" step="1" value="<?php echo $stock_db; ?>" class="form-control" id="exampleInputEmail1" placeholder="Stock" name="txtstock" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Description</label>
                            <textarea name="txtdescription" value="<?php echo $description_db; ?>" placeholder="Enter Description" class="form-control"><?php echo $description_db; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Upload Product Image</label>
                            <img src="productimages/<?php echo $productimage_db; ?>" class="img-rounded" width="50px" height="50px">
                            <input type="file" class="input-group" id="" name="myfile">
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button name="btnupdate" type="submit" class="btn btn-warning">Update Product</button>
            </div>

        </div>
    </form>
</div>
<!-- /.content-wrapper -->



<?php include_once 'footer.php'; ?>