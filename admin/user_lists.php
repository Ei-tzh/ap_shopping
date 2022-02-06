<?php
session_start();
require('../config/config.php');
require('../config/common.php');
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location:login.php');
  }
  //echo password_hash('admin',PASSWORD_DEFAULT,['cost'=>12]);
  
  if($_SESSION['role']!=1){
    header('Location:login.php');
  }
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
            <h3 class="card-title">User Listings</h3>
          </div>
          <!-- /.card-header -->
          <?php 
            if(empty($_GET['pageno'])){
                $pageno=1;
            }else{
                $pageno=$_GET['pageno'];
            }
            $numOfRec=3;
            $offset=($pageno-1)*$numOfRec;

            if (empty($_POST['search']) && empty($_COOKIE['search'])) {
            
            $stmt=$db->prepare("SELECT * FROM users ORDER BY id DESC");
            $stmt->execute();
            $rawresultusers=$stmt->fetchAll();

            $totalpages=ceil(count($rawresultusers)/$numOfRec);
            $stmtusers=$db->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfRec");
            $stmtusers->execute();
            $resultusers=$stmtusers->fetchAll();
        }else{
            // $search= $_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
            if(empty($_POST['search'])){
              $search=$_COOKIE['search'];
            }else{
              $search= $_POST['search'];
            }
            $stmt=$db->prepare("SELECT * FROM users WHERE name LIKE '%$search%' ORDER BY id DESC");
            $stmt->execute();
            $rawresultusers=$stmt->fetchAll();

            $totalpages=ceil(count($rawresultusers)/$numOfRec);
            $stmtusers=$db->prepare("SELECT * FROM users  WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRec");
            $stmtusers->execute();
            $resultusers=$stmtusers->fetchAll();
        }
            
            // print_r($resultusers);exit();

          ?>
          <div class="card-body">
            <a href="useradd.php" class="btn btn-success mb-3">New User</a>
            <table class="table table-bordered">
              <thead>                  
                <tr>
                  <th style="width: 10px">ID</th>
                  <th style="width: 250px">Username</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Phone.no</th>
                  <th>Role</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                  <?php 
                    if($resultusers):
                        $i=1;
                        foreach($resultusers as $value):
                  
                  ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= escape($value['name']) ?></td>
                        <td><?= escape($value['email'])  ?></td>
                        <td><?= escape($value['address'])  ?></td>
                        <td><?= escape($value['phone'])  ?></td>
                        <td><?=  escape($value['role'])==1?'Admin':'User' ?></td>
                        <td>
                        <a href="useredit.php?id=<?= escape($value['id']) ?>" class="btn btn-outline-primary">Edit</a>
                        <a href="userdelete.php?id=<?= escape($value['id']) ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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
