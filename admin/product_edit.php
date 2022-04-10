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
      if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price']) || empty($_POST['quantity']) || empty($_POST['category'])){
            if(empty($_POST['name'])){
                $catNameError="Name field is required!";
            }
            if(empty($_POST['description'])){
                $desError="Description field is required!";
            }
            if(empty($_POST['price'])){
                $priceError="Price field is required!";
            }elseif(is_numeric($_POST['price'])!= 1){
                $priceError="Price must be only integer.";
            }
            if(empty($_POST['quantity'])){
                $qtyError="Quantity field is required!";
            }
            elseif(is_numeric($_POST['quantity'])!= 1){
                $qtyError="Quantity must be only integer.";
            }
            if(empty($_POST['category'])){
                $catError="Category field is required!";
            }
      }else{//validation success
        if(is_numeric($_POST['price'])!= 1){
            $priceError="Price must be only integer.";
        }
        if(is_numeric($_POST['quantity'])!= 1){
            $qtyError="Quantity must be only integer.";
        }
        if(empty($priceError) && empty($qtyError)){
            $id=$_POST['id'];
            $name=$_POST['name'];
            $des=$_POST['description'];
            $price=$_POST['price'];
            $qty=$_POST['quantity'];
            $category=$_POST['category'];
                if(empty($_FILES['image']['name'])){
                    $stmt=$db->prepare("UPDATE products SET name=:name,description=:des,price=:price,quantity=:qty,category_id=:category WHERE id=:id");
                    $result=$stmt->execute([
                        ':name'=>$name,
                        ':des'=>$des,
                        ':price'=>$price,
                        ':qty'=>$qty,
                        ':category'=>$category,
                        ':id'=>$id
                    ]);
                    if($result){
                        echo "<script>alert('A product is successfully updated!');window.location.href='index.php';</script>";
                    }
                }else{
                    $file='images/'.$_FILES['image']['name'];
                    $imagetype=pathinfo($file,PATHINFO_EXTENSION);
                    $tmp=$_FILES['image']['tmp_name'];//a place stored request data(image)
                    if($imagetype != 'png' && $imagetype != 'jpg' && $imagetype !='jpeg'){
                        $imageError="Image must be png,jpg and jpeg.";
                    }else{//image validation success
                    move_uploaded_file($tmp,$file);
                    $stmt=$db->prepare("UPDATE products SET name=:name,description=:des,price=:price,quantity=:qty,category_id=:category,image=:image WHERE id=:id");
                    $result=$stmt->execute([
                        ':name'=>$name,
                        ':des'=>$des,
                        ':price'=>$price,
                        ':qty'=>$qty,
                        ':category'=>$category,
                        ':id'=>$id,
                        ':image'=>$file,
                        
                    ]);
                    if($result){
                        echo "<script>alert('A product is successfully updated!');window.location.href='index.php';</script>";
                        }
                    }
                }
            }
            
        }
  }
$stmt=$db->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt->execute();
$productResult=$stmt->fetch();
// print_r($result['image']);exit();
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
            <input type="hidden" name="id" value="<?php echo escape($productResult['id']); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name:</label>
                    <input type="text" name="name" id="name" class="form-control  <?php echo empty($catNameError) ? '': 'is-invalid'; ?>" value="<?php echo escape($productResult['name']) ?>">
                    <div class="invalid-feedback">
                        <?php echo empty($catNameError) ? '': $catNameError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea name="description" class="form-control <?php echo empty($desError) ? '':'is-invalid'; ?>" id="content" cols="30" rows="10" ><?php echo escape($productResult['description']) ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo empty($desError) ? '': $desError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price:</label>
                    <input type="text" name="price" id="price" class="form-control  <?php echo empty($priceError) ? '': 'is-invalid'; ?>" value="<?php echo escape($productResult['price']) ?>">
                    <div class="invalid-feedback">
                        <?php echo empty($priceError) ? '': $priceError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="text" name="quantity" id="quantity" class="form-control  <?php echo empty($qtyError) ? '': 'is-invalid'; ?>" value="<?php echo escape($productResult['quantity']) ?>">
                    <div class="invalid-feedback">
                        <?php echo empty($qtyError) ? '': $qtyError; ?>
                    </div>
                </div>
                <?php 
                    $stmt=$db->prepare("SELECT * FROM categories");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                   
                ?>
                <div class="mb-3">
                    <label for="category" class="form-label">Categories:</label>
                    <select name="category" id="category" class="form-control  <?php echo empty($catError) ? '': 'is-invalid'; ?>"">
                        <option value="">SELECT CATEGORIES</option>
                        <?php foreach($result as $val): 
                                if($val['id']==$productResult['category_id']): ?>
                                    <option value="<?php echo  escape($val['id']) ?>" selected><?php echo escape($val['name']) ?></option>
                                <?php else: ?>
                                    <option value="<?php echo  escape($val['id']) ?>"><?php echo escape($val['name']) ?></option>
                       <?php  endif;
                       endforeach ?>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo empty($catError) ? '': $catError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label><br>
                    <img src="<?php echo escape($productResult['image']) ?>" alt="" class="rounded w-25 h-25 mb-2">
                    <input type="file" name="image" id="image" class="form-control mt-2 <?php echo empty($imageError) ? '': 'is-invalid'; ?>" >
                    <div class="invalid-feedback">
                        <?php echo empty($imageError) ? '': $imageError; ?>
                    </div>
                </div>
                <input type="submit" value="Edit" class="btn btn-warning">
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