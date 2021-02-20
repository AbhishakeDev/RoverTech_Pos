<?php
include_once 'connectdb.php';session_start();
error_reporting(0);
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
            <h1 class="m-0">Table Report</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
      <div style="width:100%;height:auto;padding:20px;" class="card">
      <h1 style="margin-bottom: 20px;font-size:20px;"> From :  <?php echo $_POST['date_1']?> ----------------- To :  <?php echo $_POST['date_2']?></h1>
      <form role="form" method="post" action="">
      <div class="row">
          <div class="col-md-5">
          <div class="form-group">
                            <label>Select Date: </label>
                            <div id="datepicker1" class="input-group date" data-date-format="yyyy-mm-dd">
                                <input data-date-format="yyyy-mm-dd" name="date_1" class="form-control" type="text"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
          </div>
          <div class="col-md-5">
          <div class="form-group">
                            <label>Select Date: </label>
                            <div id="datepicker2" class="input-group date" data-date-format="yyyy-mm-dd">
                                <input data-date-format="yyyy-mm-dd" name="date_2" class="form-control" type="text"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
          </div>
          <div class="col-md-2">
          <div align="left" style="padding-top:24px;">
                    <input type="submit" name="btndatefilter" value="Filter By Date" class="btn btn-success">
                </div>
          </div>
      </div>
      <br>

      <?php
          $select = $pdo->prepare("select sum(total) as total ,sum(subtotal) as stotal, count(invoice_id) as invoice from tbl_invoice where order_date between :fromdate AND :todate");
            $select->bindParam(':fromdate',$_POST['date_1']);
            $select->bindParam(':todate',$_POST['date_2']);
          $select->execute();

    $row = $select->fetch(PDO::FETCH_OBJ);

    $net_total=$row->total;
    $stotal=$row->stotal;
    $invoice=$row->invoice;

    ?>

      <div class="row">
       
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i style="font-size: 40px;" class="far fa-copy"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Invoice</span>
              <span style="font-size: 25px;" class="info-box-number"><?php echo number_format($invoice);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
         <div class="col-md-4 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i style="font-size: 40px;" class="fas fa-dollar-sign"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Subtotal</span>
              <span style="font-size: 25px;" class="info-box-number"><?php echo '$ '.number_format($stotal);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i style="font-size: 40px;" class="fas fa-money-check-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Net Total</span>
              <span style="font-size: 25px;" class="info-box-number"><?php echo '$ '.number_format($net_total);?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <br>
      <br>
<!-- table -->
<div style="overflow-x:auto;margin:30px 0;padding:30px">
<table class="table table-striped" id="salesreporttable">
        <thead>
          <tr>
            <th>Invoice Id</th>
            <th>Customer Name</th>
            <th>Subtotal</th>
            <th>Discount</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Due</th>
            <th>Order Date</th>
            <th>Payment Type</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $select = $pdo->prepare("select * from tbl_invoice where order_date between :fromdate AND :todate");
            $select->bindParam(':fromdate',$_POST['date_1']);
            $select->bindParam(':todate',$_POST['date_2']);
          $select->execute();

          while ($row = $select->fetch(PDO::FETCH_OBJ)) {
            echo '
                      <tr>
                      <td>' . $row->invoice_id. '</td>
                      <td>' . $row->customer_name. '</td>
                      <td>' . $row->subtotal . '</td>
                        <td>' . $row->discount . '</td>
                      <td><span class="label label-danger">'."$" . $row->total . '</span></td>
                      <td>' . $row->paid . '</td>
                      <td>' . $row->due . '</td>
                      
                      <td>' . $row->order_date. '</td>
                      
                      ';
                      if($row->payment_type == "cash"){
                          echo '<td><span class="label label-primary">' . $row->payment_type . '</span></td></tr>';
                      }
                      else if($row->payment_type == "card"){
                        echo '<td><span class="label label-warning">' . $row->payment_type . '</span></td></tr>';
                          
                      }
                      else{
                          echo '<td><span class="label label-info">' . $row->payment_type . '</span></td></tr>';

                      }
          }
          ?>
        </tbody>
      </table>
      </div>
</form>


      </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
    $(function() {
        $("#datepicker1").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', new Date());
    });
    </script>

<script>
    $(function() {
        $("#datepicker2").datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', new Date());
    });
    </script>

<script>
  $(document).ready(function() {
    $('#salesreporttable').DataTable({
      "order": [
        [0, "desc"]
      ]
    });
  });
</script>

 <?php include_once 'footer.php'; ?>
