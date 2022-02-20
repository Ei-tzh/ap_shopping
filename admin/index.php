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
  if (!empty($_POST['search'])) {
    setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
    
  }
  else{
    if (empty($_GET['pageno'])) {
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
    }
  } 
?>

<?php include('header.php'); ?>

   <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Product Listings</h3>
            </div>
            <!-- /.card-header -->
            <?php
            if(empty($_GET['pageno'])){
              $pageno=1;
            }else{
              $pageno=$_GET['pageno'];
            }
            $numOfRec=5;
            $offset=($pageno-1)*$numOfRec;
            if(empty($_POST['search']) && empty($_COOKIE['search'])){
              $stmt=$db->prepare("SELECT * FROM products ORDER BY id");
              $stmt->execute();
              $rawresult=$stmt->fetchAll();
              $totalpages=ceil(count($rawresult)/$numOfRec);

              $stmtproducts=$db->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRec");
              $stmtproducts->execute();
              $resultproducts=$stmtproducts->fetchAll();
            }else{
              if(!empty($_POST['search'])){
                $search=$_POST['search'];
              }else{
                $search=$_COOKIE['search'];
              }
              $stmt=$db->prepare("SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id");
              $stmt->execute();
              $rawresult=$stmt->fetchAll();
              $totalpages=ceil(count($rawresult)/$numOfRec);

              $stmtproducts=$db->prepare("SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRec");
              $stmtproducts->execute();
              $resultproducts=$stmtproducts->fetchAll();

              
            }
            ?>
            <div class="card-body">
              <a href="product_add.php" class="btn btn-success mb-3">New  Product</a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>In Stock</th>
                    <th>Category_id</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      if($resultproducts):
                      $i=1;
                        foreach($resultproducts as $val):
                          $catstmt=$db->prepare("SELECT * FROM categories WHERE id=".$val['category_id']);
                          $catstmt->execute();
                          $catresult=$catstmt->fetch();
                         
                  ?>
                   <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($val['name']) ?></td>
                      <td><?= escape(substr($val['description'],0,30) ) ?></td>
                      <td><?= escape($val['price'])  ?></td>
                      <td><?= escape($val['quantity'])  ?></td>
                      <td><?= escape($catresult['name'])  ?></td>
                      <td>
                      <a href="product_edit.php?id=<?= escape($val['id']) ?>" class="btn btn-outline-primary">Edit</a>
                      <a href="product_delete.php?id=<?= escape($val['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                      </td>
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
                <li class="page-item <?php if($pageno>=$totalpages){ echo 'disabled';}?>"><a class="page-link" href="<?php if($pageno>=$totalpages){echo '#';} else{ echo "?pageno=".($pageno+1);} ?>">Next</a></li>
                <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpages; ?>">Last</a></li>
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
