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
      <form role="form" method="post" action="">
      <div style="width:100%;height:auto;padding:20px;" class="card">
      <h1 style="margin-bottom: 20px;font-size:20px;"> From :  <?php echo $_POST['date_1']?> ----------------- To :  <?php echo $_POST['date_2']?></h1>
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

      <!-- For total earnings -->
      <?php
          $select = $pdo->prepare("select order_date,sum(total) as price from tbl_invoice where order_date between :fromdate AND :todate group by order_date");
            $select->bindParam(':fromdate',$_POST['date_1']);
            $select->bindParam(':todate',$_POST['date_2']);
          $select->execute();
          $total=[];
          $date=[];

          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
              extract($row);
              $total[]=$price;
              $date[]=$order_date;
          }
        //   echo json_encode($total);
              ?>
        <canvas id="myChart" width="400" height="200"></canvas>
          <br>
        <!-- For best sold product -->
        <?php
          $select = $pdo->prepare("select product_name,sum(qty) as q from tbl_invoice_details where order_date between :fromdate AND :todate group by product_id");
            $select->bindParam(':fromdate',$_POST['date_1']);
            $select->bindParam(':todate',$_POST['date_2']);
          $select->execute();
          $pname=[];
          $qty=[];

          while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
              extract($row);//you will get the data in the same name as a variable present in the database
              $pname[]=$product_name;
              $qty[]=$q;
          }
        //   echo json_encode($total);
              ?>
        <canvas id="Bestsellingproduct" width="400" height="200"></canvas>

        </div>
      </div><!-- /.container-fluid -->
        </form>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- for total earnings -->
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($date);?>,
        datasets: [{
            label: 'Total Earnings',
            backgroundColor: 'rgb(180, 77, 224)',
            borderColor: 'rgb(241, 103, 254)',
            data: <?php echo json_encode($total);?>
        }]
    },

    // Configuration options go here
    options: {}
});

</script>

<!-- for best selling product -->
<script>
var ctx = document.getElementById('Bestsellingproduct').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($pname);?>,
        datasets: [{
            label: 'Total Quantity Sold',
            backgroundColor: 'rgb(185, 241, 168)',
            borderColor: 'rgb(21, 121, 3)',
            data: <?php echo json_encode($qty);?>
        }]
    },

    // Configuration options go here
    options: {}
});

</script>

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

 <?php include_once 'footer.php'; ?>
