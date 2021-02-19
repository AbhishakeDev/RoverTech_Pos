<?php


include_once 'connectdb.php';

session_start();
if ($_SESSION['useremail'] == "") {
  header('location:index.php');
}

$select = $pdo->prepare("select sum(total) as t, count(invoice_id) as inv from tbl_invoice");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$total_order = $row->t;
$net_total = $row->inv;

// <!-- for showing earning by date -->

$select = $pdo->prepare("select order_date,total from tbl_invoice group by order_date LIMIT 30");
$select->execute();
$ttl = [];
$date = [];

while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
  extract($row);
  $ttl[] = $total;
  $date[] = $order_date;
}
//   echo json_encode($total);


include_once 'header.php';

?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin Dashboard</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div style="width:100%;height:auto;padding:20px;" class="card">
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-lightblue">
              <div class="inner">
                <h3><?php echo "$" . number_format($total_order); ?></h3>

                <p>Total Revenue</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $net_total; ?></h3>

                <p>Total No. of Order</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->

          <?php
          $select = $pdo->prepare("select count(pname) as p from tbl_product");
          $select->execute();
          $row = $select->fetch(PDO::FETCH_OBJ);

          $total_product = $row->p;

          ?>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php echo $total_product; ?></h3>

                <p>Total No. of Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <?php
          $select = $pdo->prepare("select count(category) as c from tbl_category");
          $select->execute();
          $row = $select->fetch(PDO::FETCH_OBJ);

          $total_category = $row->c;

          ?>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php echo $total_category; ?></h3>

                <p>Categories</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <br>
        <h1>Total Earnings Datewise</h1>
        <canvas id="earningbydate" width="400" height="200"></canvas>
        <br>
        <!-- CReating a table -->
        <div class="row">
          <div  class="col-md-6">
            <h2>Best Selling Products</h2>

            <table class="table table-striped" id="bestsellingproductlist">
              <thead>
                <tr>
                  <th>Product Id</th>
                  <th>Product Name</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $select = $pdo->prepare("select product_id, product_name, price, sum(qty) as q, sum(qty*price) as total from tbl_invoice_details group by product_id order by sum(qty) desc limit 15");

                $select->execute();

                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                  echo '
                      <tr>
                      <td>' . $row->product_id . '</td>
                     <td>' . $row->product_name . '</td>
                       <td><span class="label label-info">' . $row->q . '</span></td>
                        <td><span class="label label-success">' . "$" . $row->price . '</span></td>
                   <td><span class="label label-danger">' . "$" . $row->total . '</span></td>
                      
                      
                      </tr>
                      ';
                }
                ?>

              </tbody>
            </table>


          </div>
          <div class="col-md-6">
            <h2>Recent Orders</h2>
            <table class="table table-striped" id="orderlisttable">
        <thead>
          <tr>
            <th>Invoice Id</th>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Total</th>
            <th>Payment Type</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $select = $pdo->prepare("select * from tbl_invoice order by invoice_id desc LIMIT 15");

          $select->execute();

          while ($row = $select->fetch(PDO::FETCH_OBJ)) {
            echo '
            <tr>
                <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</a></td>
                <td>'.$row->customer_name.'</td>
                <td>'.$row->order_date.'</td>
                <td><span class="label label-danger">'."$".$row->total.'</span></td>';
        if($row->payment_type == "Cash") {
            echo '<td><span class="label label-warning">'.$row->payment_type.'</span></td></tr>';
        } else if($row->payment_type == "Card") {
            echo '<td><span class="label label-success">'.$row->payment_type.'</span></td></tr>';
        } else {
            echo '<td><span class="label label-primary">'.$row->payment_type.'</span></td></tr>';
        }
          }
          ?>

        </tbody>
      </table>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
  var ctx = document.getElementById('earningbydate').getContext('2d');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
      labels: <?php echo json_encode($date); ?>,
      datasets: [{
        label: 'Total Earnings',
        backgroundColor: 'rgb(185, 241, 168)',
        borderColor: 'rgb(21, 121, 3)',
        data: <?php echo json_encode($ttl); ?>
      }]
    },

    // Configuration options go here
    options: {}
  });
</script>

<!-- <script>
  $(document).ready(function() {
    $('#bestsellingproductlist').DataTable({
      "order": [
        
      ]
    });
  });
</script>

<script>
$(document).ready( function () {
    $('#orderlisttable').DataTable({
        "order": [[0, "desc"]]
    });
} );
</script> -->

<?php include_once 'footer.php'; ?>