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
          <h1 style="font-size: 30px;">Product List</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div style="width:100%;height:auto;" class="card">
      <div style="overflow-x:auto;margin:30px 0;padding:30px">
      <table class="table table-striped" id="producttable">
        <thead>
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Purchase Price</th>
            <th>Sale Price</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Image</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $select = $pdo->prepare("select * from tbl_product order by pid desc");

          $select->execute();

          while ($row = $select->fetch(PDO::FETCH_OBJ)) {
            echo '
                      <tr>
                      <td>' . $row->pid . '</td>
                      <td>' . $row->pname . '</td>
                      <td>' . $row->pcategory . '</td>
                      <td>' . $row->purchaseprice . '</td>
                      <td>' . $row->saleprice . '</td>
                      <td>' . $row->pstock . '</td>
                      <td>' . $row->pdescription . '</td>
                      <td><img src="productimages/' . $row->pimage . '" class="img-rounded" width="40px" height="40px"></td>
                      <td>
                      <a href="viewproduct.php?id=' . $row->pid . '" data-toggle="tooltip" title="View product" class="btn btn-success" role="button"><i class="fas fa-eye"></i></a>
                      </td>
                      <td>
                      <a href="editproduct.php?id=' . $row->pid . '"  data-toggle="tooltip" title="Edit product" class="btn btn-info" role="button"><i class="fas fa-edit"></i></a>
                      </td>
                      <td>
                      <button id=' . $row->pid . ' class="btn btn-danger btndelete" data-toggle="tooltip" title="Delete product" ><i class="fas fa-trash-alt"></i></button>
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

<script>
  $(document).ready(function() {
    $('#producttable').DataTable({
      "order": [
        [0, "desc"]
      ]
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

<!--Ajax requests for delete -->

<script>
  $(document).ready(function() {
    $('.btndelete').click(function() {
      // alert("test");
      var tdh = $(this);
      var id = $(this).attr("id");
      // Sweet Alert

      swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Product!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {

            // Ajax Call;
      $.ajax({
        url: 'productdelete.php',
        type: 'post',
        data: {
          pidd: id
        },
        success: function(data) {
          tdh.parents('tr').hide();
        }
      })

            swal("Poof! Your product has been deleted!", {
              icon: "success",
            });
          } else {
            swal("Your product is safe!");
          }
        });
    })
  })
</script>



<?php include_once 'footer.php'; ?>