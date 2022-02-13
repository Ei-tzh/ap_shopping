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
  $stmt=$db->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetch();
 if($_POST){
  if(empty($_POST['title']) || empty($_POST['content'])  ){
    if(empty($_POST['title'])){
      
      $titleError="Title Field is required!";
    }
    if(empty($_POST['content'])){
      $contentError="Content Field is required!";
    }
    
  }else{
      $id=$_POST['id'];
      $title=$_POST['title'];
      $content=$_POST['content'];
     
      if($_FILES['image']['name'] != null){
        $files='images/'.$_FILES['image']['name'];
        $tmp=$_FILES['image']['tmp_name'];
        $imagetype=pathinfo($files,PATHINFO_EXTENSION);
        
            if($imagetype != 'png' && $imagetype!= 'jpg' && $imagetype!='jpeg'){
                echo "<script>alert('Image must be jpg,png,jpeg.');</script>";
                }else{
                    move_uploaded_file($tmp,$files);
                    $stmt=$db->prepare("UPDATE posts SET title=:title,content=:content,image=:image WHERE id=:id");
                    $result=$stmt->execute([
                        ':title'=>$title,
                        ':content'=>$content,
                        ':image'=>$files,
                        ':id'=>$id,
                    ]);
                    if($result){
                    echo "<script>alert('Successfully Added!');window.location.href='index.php';</script>";
                        }
                    }
        }else{
            $stmt=$db->prepare("UPDATE posts SET title=:title,content=:content WHERE id=:id");
            $result=$stmt->execute([
                ':title'=>$title,
                ':content'=>$content,
                ':id'=>$id,
            ]);
            if($result){
            echo "<script>alert('Successfully Added!');window.location.href='index.php';</script>";
                }
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
            <form action="" method="post" enctype="multipart/form-data">
            <input name="_token" type="hidden"  value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $result['id'] ?>">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control <?php echo empty($titleError) ? '': 'is-invalid'; ?>" value="<?php echo escape($result['title']) ?>" >
                    <div class="invalid-feedback">
                        <?php echo empty($titleError) ? '': $titleError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" class="form-control <?php echo empty($contentError) ? '':'is-invalid'; ?>" id="content" cols="30" rows="10" ><?php echo escape($result['content']) ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo empty($contentError) ? '': $contentError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label <?php echo empty($imageError) ? '':'is-invalid'; ?>">Image</label><br>
                    <img src="<?php echo escape($result['image']) ?>" alt="" class="img-thumbnail w-25 h-25 mb-2">
                    <input class="form-control py-1" type="file" id="image" name="image">
                    <div class="invalid-feedback">
                        <?php echo empty($imageError) ? '': $imageError; ?>
                    </div>
                </div>
                <input type="submit" value="Edit" class="btn btn-success">
                <a href="index.php" class="btn btn-secondary">Back</a>
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
