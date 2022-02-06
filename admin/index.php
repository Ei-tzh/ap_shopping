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
            
            <div class="card-body">
              <a href="add.php" class="btn btn-success mb-3">New  Product</a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  
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
