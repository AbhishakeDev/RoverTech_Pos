<?php
include_once 'connectdb.php';

session_start();
if ($_SESSION['useremail'] == "" or $_SESSION['role'] == '') {
  header('location:index.php');
}
if ($_SESSION['role'] == 'Admin') {
  include_once 'header.php';
}else{
  include_once 'headeruser.php';
} ?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Review Order</h1>
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
      <table class="table table-striped" id="orderlisttable">
        <thead>
          <tr>
            <th>Invoice Id</th>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Payment Type</th>
            <th>Print</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $select = $pdo->prepare("select * from tbl_invoice order by invoice_id desc");

          $select->execute();

          while ($row = $select->fetch(PDO::FETCH_OBJ)) {
            echo '
                      <tr>
                      <td>' . $row->invoice_id. '</td>
                      <td>' . $row->customer_name. '</td>
                      <td>' . $row->order_date. '</td>
                      <td>' . $row->total . '</td>
                      <td>' . $row->paid . '</td>
                      <td>' . $row->due . '</td>
                      <td>' . $row->payment_type . '</td>
                      <td>
                      <a target="_blank" href="invoice_80mm.php?id=' . $row->invoice_id . '" data-toggle="tooltip" title="Thermal Print Invoice" class="btn btn-warning" role="button"><i class="fas fa-print"></i></a>
                      <a target="_blank" href="invoice_db.php?id=' . $row->invoice_id . '" data-toggle="tooltip" title="Normal Print Invoice" class="btn btn-success" role="button"><i class="fas fa-print"></i></a>
                      </td>
                      <td>
                      <a href="editorder.php?id=' . $row->invoice_id . '"  data-toggle="tooltip" title="Edit Order" class="btn btn-info" role="button"><i class="fas fa-edit"></i></a>
                      </td>
                      <td>
                      <button id=' . $row->invoice_id . ' class="btn btn-danger btndelete" data-toggle="tooltip" title="Delete Order" ><i class="fas fa-trash-alt"></i></button>
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
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
<script>
  $(document).ready(function() {
    $('#orderlisttable').DataTable({
      "order": [
        [0, "desc"]
      ]
    });
  });
</script>

<script>
  $(document).ready(function() {
    $('.btndelete').click(function() {
      // alert("test");
      var tdh = $(this);
      var id = $(this).attr("id");
      // Sweet Alert

      swal({
          title: "Are you sure?",
          text: "Once deleted, you will not be able to recover this Order!",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {

            // Ajax Call;
      $.ajax({
        url: 'orderdelete.php',
        type: 'post',
        data: {
          pidd: id
        },
        success: function(data) {
          tdh.parents('tr').hide();
        }
      })

            swal("Poof! Your Order has been deleted!", {
              icon: "success",
            });
          } else {
            swal("Your Order is safe!");
          }
        });
    })
  })
</script>

 <?php include_once 'footer.php'; ?>
