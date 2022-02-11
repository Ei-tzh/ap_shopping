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
              <h3 class="card-title">Category Listings</h3>
            </div>
            <!-- /.card-header -->
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $num_of_rec=2;
              $offset=($pageno-1)*$num_of_rec;

              if(empty($_POST['search'])  && empty($_COOKIE['search'])){
                $stmt=$db->prepare('SELECT * FROM categories ORDER BY id DESC');
                $stmt->execute();
                $rawresult=$stmt->fetchALL();
                $totalpage=ceil(count($rawresult)/$num_of_rec);

                $stmt=$db->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$num_of_rec");
                $stmt->execute();
                $result=$stmt->fetchALL();
              }else{
                if(empty($_POST['search'])){
                  $searchkey=$_COOKIE['search'];
                }else{
                  $searchkey=$_POST['search'];
                }
                $stmt=$db->prepare("SELECT * FROM categories WHERE name LIKE '%$searchkey%' ORDER BY id DESC");
                $stmt->execute();
                $rawresult=$stmt->fetchALL();
                $totalpage=ceil(count($rawresult)/$num_of_rec);

                $stmt=$db->prepare("SELECT * FROM categories WHERE name LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$num_of_rec");
                $stmt->execute();
                $result=$stmt->fetchALL();
              }

                // $totalpage=ceil(50/7);//value nearest number.
                // echo $totalpage;
                // print_r($result[0]['content']);
            ?>
            <div class="card-body">
              <a href="cat_add.php" class="btn btn-primary mb-3">New Category</a>
              <table class="table table-bordered">
                <thead>                  
                  <tr>
                    <th style="width: 10px">ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                      if($result):
                        $i=1;
                        foreach($result as $value): ?>
                          <tr>
                            <td><?= $i ?></td>
                            <td><?= escape($value['name'])?></td>
                            <td><?= escape(substr($value['description'],0,50)) ?></td>
                            <td>
                              <a href="cat_edit.php?id=<?= escape($value['id']) ?>" class="btn btn-info">Edit</a>
                              <a href="cat_del.php?id=<?= escape($value['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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
