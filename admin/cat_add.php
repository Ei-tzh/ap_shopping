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
  if($_POST){
      if(empty($_POST['name']) || empty($_POST['description'])){
          if(empty($_POST['name'])){
              $catNameError="Name field is required!";
          }
          if(empty($_POST['description'])){
              $desError="Description field is required!";
          }
      }else{
          $name=$_POST['name'];
          $des=$_POST['description'];

          $stmt=$db->prepare("INSERT INTO categories(name,description) VALUES (:name,:des)");
          $result=$stmt->execute([
            ':name'=>$name,
            ':des'=>$des,
          ]);
          if($result){
              echo "<script>alert('A New Category is successfully added');window.location.href='category_lists.php';</script>";
          }
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
          <div class="card-body">
            <form action="cat_add.php" method="post">
            <input name="_token" type="hidden"  value="<?php echo $_SESSION['_token']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name:</label>
                    <input type="text" name="name" id="name" class="form-control  <?php echo empty($catNameError) ? '': 'is-invalid'; ?>" >
                    <div class="invalid-feedback">
                        <?php echo empty($catNameError) ? '': $catNameError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" class="form-control <?php echo empty($desError) ? '':'is-invalid'; ?>" id="content" cols="30" rows="10" ></textarea>
                    <div class="invalid-feedback">
                        <?php echo empty($desError) ? '': $desError; ?>
                    </div>
                </div>
                
                <input type="submit" value="Add" class="btn btn-success">
                <a href="category_lists.php" class="btn btn-secondary">Back</a>
            </form>
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