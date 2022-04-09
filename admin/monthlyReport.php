<?php
session_start();
require('../config/config.php');
require('../config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
?>

<?php include('header.php'); ?>

   <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-warning">
              <h3 class="card-title text-white">Monthly Report</h3>
            </div>
            <!-- /.card-header -->
            <?php
                date_default_timezone_set("Asia/Yangon");
                $currentdate=date("Y-m-d");
                $fromdate=date('Y-m-d', strtotime( $currentdate . " +1 days"));
                // echo $fromdate;
                $todate=date('Y-m-d', strtotime( $currentdate ." -1 month"));
                $stmt=$db->prepare("SELECT * FROM sale_orders WHERE order_date<:fromdate AND order_date>=:todate ORDER BY id");
                $stmt->execute([
                    ':fromdate'=>$fromdate,
                    ':todate'=>$todate,
                ]);
                $orderResult=$stmt->fetchAll();
            ?>
            <div class="card-body">
                <table id="order-table" class="display" style="width:100%">
                    <thead>                  
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Name</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($orderResult as $val):
                                $cus_id=$val['customer_id'];
                                $stmt=$db->prepare("SELECT * FROM users WHERE id=$cus_id");
                                $stmt->execute();
                                $userResult=$stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($userResult['name']); ?></td>
                            <td><?php echo escape($val['total_price']); ?></td>
                            <td><?php echo escape(date('Y-m-d',strtotime($val['order_date']))); ?></td>
                        </tr>
                        <?php 
                            $i++;
                            endforeach
                        ?>
                    </tbody>
              </table>
              
            </div>
            <!-- /.card-body -->
            
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
<?php include('footer.html'); ?>
<script>
    $(document).ready(function() {
        $('#order-table').DataTable();
    } );
</script>

