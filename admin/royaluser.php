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
            <div class="card-header bg-primary">
              <h3 class="card-title text-white"><i class="fa-solid fa-crown"></i> Royal User</h3>
            </div>
            <!-- /.card-header -->
            <?php
                $stmt=$db->prepare("SELECT customer_id,SUM(total_price) as total_amount FROM sale_orders GROUP BY customer_id HAVING SUM(total_price)>=100000");
                $stmt->execute();
                $saleResult=$stmt->fetchAll();
                // print_r($saleResult) ;
                // exit();
            ?>
            <div class="card-body">
                <table id="order-table" class="display" style="width:100%">
                    <thead>                  
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($saleResult as $val):
                                $cus_id=$val['customer_id'];
                                $stmt=$db->prepare("SELECT * FROM users WHERE id=$cus_id");
                                $stmt->execute();
                                $userResult=$stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($userResult['name']); ?></td>
                            <td><?php echo escape($val['total_amount'])." MMK"; ?></td>
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

