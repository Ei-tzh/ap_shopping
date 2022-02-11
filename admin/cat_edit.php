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
  $catID=$_GET['id'];
  $stmt=$db->prepare("SELECT * FROM categories WHERE id=$catID");
  $stmt->execute();
  $result=$stmt->fetch();
  if($_POST){
      if(empty($_POST['name']) || empty($_POST['description'])){
          if(empty($_POST['name'])){
              $catNameError="Name field is required!";
          }
          if(empty($_POST['description'])){
              $desError="Description field is required!";
          }
      }else{
          $id=$_POST['id'];
          $name=$_POST['name'];
          $des=$_POST['description'];

          $stmt=$db->prepare("UPDATE categories SET name=:name,description=:des WHERE id=:id");
          $result=$stmt->execute([
            ':id'=>$id,
            ':name'=>$name,
            ':des'=>$des,
          ]);
          if($result){
              echo "<script>alert('A New Category is successfully edited!');window.location.href='category_lists.php';</script>";
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
            <form action="" method="post">
            <input name="_token" type="hidden"  value="<?php echo $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?php echo escape($result['id']) ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name:</label>
                    <input type="text" name="name" id="name" class="form-control  <?php echo empty($catNameError) ? '': 'is-invalid'; ?>" value="<?php echo escape($result['name']) ?>">
                    <div class="invalid-feedback">
                        <?php echo empty($catNameError) ? '': $catNameError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" class="form-control <?php echo empty($desError) ? '':'is-invalid'; ?>" id="content" cols="30" rows="10" ><?php echo escape($result['description']) ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo empty($desError) ? '': $desError; ?>
                    </div>
                </div>
                
                <input type="submit" value="Edit" class="btn btn-primary">
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