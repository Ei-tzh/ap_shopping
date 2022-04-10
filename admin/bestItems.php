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
            <div class="card-header bg-info">
              <h3 class="card-title text-white"><i class="fa-solid fa-certificate"></i> Best Seller Items</h3>
            </div>
            <!-- /.card-header -->
            <?php
                $stmt=$db->prepare("SELECT product_id,SUM(quantity) as qty FROM sale_order_details GROUP BY product_id HAVING SUM(quantity)>=3");
                $stmt->execute();
                $saleProductResult=$stmt->fetchAll();
            ?>
            <div class="card-body">
                <table id="order-table" class="display" style="width:100%">
                    <thead>                  
                    <tr>
                        <th style="width: 10px">ID</th>
                        <th>Seller Items</th>
                        <th>Quantities</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($saleProductResult as $val):
                                $product_id=$val['product_id'];
                                $stmt=$db->prepare("SELECT * FROM products WHERE id=$product_id");
                                $stmt->execute();
                                $productResult=$stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($productResult['name']); ?></td>
                            <td><?php echo escape($val['qty'])." items"; ?></td>
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

