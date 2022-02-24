<?php
session_start();
require('../config/config.php');
require('../config/common.php');

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  if($_SESSION['role']!=1){
    header('Location:login.php');
  }
  //print_r($_SESSION);
//   if (!empty($_POST['search'])) {
//     setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
    
//   }
//   else{
//     if (empty($_GET['pageno'])) {
//       unset($_COOKIE['search']); 
//       setcookie('search', null, -1, '/'); 
//     }
//   } 
?>

<?php include('header.php'); ?>

   <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Order Details</h3>
            </div>
            <!-- /.card-header -->
            <?php
                if(!empty($_GET['pageno'])){
                    $pageno=$_GET['pageno'];
                }else{
                    $pageno=1;
                }
                $num_of_rec=5;
                $offset=($pageno-1)*$num_of_rec;

                $id=$_GET['id'];
                $stmt=$db->prepare("SELECT * FROM sale_order_details WHERE sale_order_id=$id");
                $stmt->execute();
                $rawresult=$stmt->fetchALL();
                $totalpage=ceil(count($rawresult)/$num_of_rec);
               
                $stmt=$db->prepare("SELECT * FROM sale_order_details WHERE sale_order_id=$id  LIMIT $offset,$num_of_rec");
                $stmt->execute();
                $result=$stmt->fetchALL();
                

                // $totalpage=ceil(50/7);//value nearest number.
                // echo $totalpage;
                // print_r($result[0]['content']);
            ?>
            <div class="card-body">
              <a href="order_lists.php" class="btn btn-outline-dark mb-3">Back</a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Customer Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    
                  </tr>
                </thead>
                <tbody>
                <?php
                      if($result):
                        $i=1;
                        foreach($result as $value):
                            $pstmt=$db->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                            $pstmt->execute();
                            $pResult=$pstmt->fetch();
                        ?>
                          <tr>
                            <td><?= $i ?></td>
                            <td><?= escape($pResult['name'])?></td>
                            <td><?= escape($pResult['price'])?></td>
                            <td><?= escape($value['quantity']) ?></td>
                            <td><?= escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                        
                          </tr>
                  <?php
                          $i++;
                        endforeach;
                      endif;
                  ?>
                </tbody>
              </table>
              <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end mt-3">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno<=1){ echo 'disabled';}?>"><a class="page-link" href="<?php if($pageno<=1){echo '#';} else{ echo "?pageno=".($pageno-1);} ?>">Previous</a></li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno>=$totalpage){ echo 'disabled';}?>"><a class="page-link" href="<?php if($pageno>=$totalpage){echo '#';} else{ echo "?pageno=".($pageno+1);} ?>">Next</a></li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpage; ?>">Last</a></li>
                </ul>
              </nav> 
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
