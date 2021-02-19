<?php

include_once 'connectdb.php';

session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == 'User') {
    header('location:index.php');
}

include_once 'header.php';

if (isset($_POST['btnaddproduct'])) {
    $productname = $_POST['txtpname'];
    $category = $_POST['txtselect_option'];
    $purchaseprice = $_POST['txtpprice'];
    $saleprice = $_POST['txtsaleprice'];
    $stock = $_POST['txtstock'];
    $description = $_POST['txtdescription'];

    $f_name = $_FILES['myfile']['name'];
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
                $productimage = $f_newfile;
                if (!isset($error)) {

                    $insert = $pdo->prepare("insert into tbl_product(pname,pcategory,purchaseprice,saleprice,pstock,pdescription,pimage) values(:pname,:pcategory,:purchaseprice,:saleprice,:pstock,:pdescription,:pimage)");

                    $insert->bindParam(':pname', $productname);
                    $insert->bindParam(':pcategory', $category);
                    $insert->bindParam(':purchaseprice', $purchaseprice);
                    $insert->bindParam(':saleprice', $saleprice);
                    $insert->bindParam(':pstock', $stock);
                    $insert->bindParam(':pdescription', $description);
                    $insert->bindParam(':pimage', $productimage);



                    if ($insert->execute()) {
                        echo '
        <script type="text/javascript">
        jQuery(function validation(){
        
        swal({
        title: "Success",
        text: "Product Successfully Added",
        icon: "success",
        button: "Ok",
        });
        
        });
        
        </script>
        ';
                    } else {
                        echo '
                        <script type="text/javascript">
                            jQuery(function validation() {
                                swal({
                                  title: "Error!",
                                  text: "The product is not added.",
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


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header ( Page header ) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Products</h1>
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
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Product Name" name="txtpname" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Purchase Price</label>
                            <input type="number" min="1" step="1" class="form-control" id="exampleInputEmail1" placeholder="Purchase Price" name="txtpprice" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Sale Price</label>
                            <input type="number" min="1" step="1" class="form-control" id="exampleInputEmail1" placeholder="Sale Price" name="txtsaleprice" required>
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
                                    <option><?php echo $row['category']; ?></option>
                                <?php

                                }

                                ?>


                            </select>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Stock </label>
                            <input type="number" min="1" step="1" class="form-control" id="exampleInputEmail1" placeholder="Stock" name="txtstock" required>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Description</label>
                            <textarea name="txtdescription" placeholder="Enter Description" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Upload Product Image</label>
                            <input type="file" class="input-group" id="" name="myfile" required>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button name="btnaddproduct" type="submit" class="btn btn-info">Add Product</button>
            </div>

        </div>
    </form>

</div>
<!-- /.content-wrapper -->

<?php include_once 'footer.php';
?>