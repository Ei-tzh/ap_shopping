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
  if($_POST){
    // if(strlen($_POST['phone'])<9 || strlen($_POST['phone'])>14){
    //  echo "Phone number must be between 9 and 14 characters.";
    // }
   
    // exit();
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) ||  strlen($_POST['password']) < 4 || empty($_POST['address']) || empty($_POST['phone']) || (strlen($_POST['phone'])<9 || strlen($_POST['phone'])>14)){
     
      if(empty($_POST['name'])){
        $nameError="Name cannot be null.";
      }
      if(empty($_POST['email'])){
        $emailError="Email cannot be null.";
      }

      if(empty($_POST['password'])){
        $passwordError="Password cannot be null";
      }elseif(strlen($_POST['password']) < 4){
        $passwordError = 'Password should be 4 characters at least';
      } 

      if(empty($_POST['address'])){
        $addressError="Address cannot be null";
      }
      if(empty($_POST['phone'])){
        $telError="Phone cannot be null";
      }elseif(strlen($_POST['phone'])<9 || strlen($_POST['phone'])>14){
        $telError="Phone number must be between 9 and 14 characters.";
      }
    }else{
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
      $role=(!empty($_POST['role'])?1:0);
      $address=$_POST['address'];
      $phone=$_POST['phone'];

      $statement=$db->prepare('SELECT * FROM users WHERE email=:email');
      $statement->bindValue(':email',$email);
      $statement->execute();
      $resultusers=$statement->fetch(PDO::FETCH_ASSOC);

      if($resultusers){
          echo "<script>alert('Already created account with this email!')</script>";
      }else{
          $stmt=$db->prepare("INSERT INTO users(name,email,password,address,role,phone) VALUES (:name,:email,:password,:address,:role,:phone)");
          $result=$stmt->execute([
              ':name'=>$name,
              ':email'=>$email,
              ':password'=>$password,
              ':address'=>$address,
              ':role'=>$role,
              ':phone'=>$phone
          ]);
          if($result){
              echo "<script>alert('Successfully New User Added!');window.location.href='user_lists.php';</script>";
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
            <form action="useradd.php" method="post">
            <input name="_token" type="hidden"  value="<?php echo $_SESSION['_token']; ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">User Name:</label>
                    <input type="text" name="name" id="name" class="form-control <?php echo empty($nameError) ? '': 'is-invalid'; ?>" >
                    <div class="invalid-feedback">
                        <?php echo empty($nameError) ? '': $nameError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Enter Email:</label>
                    <input type="email" class="form-control <?php echo empty($emailError) ? '':'is-invalid'; ?>" name="email" id="email" >
                    <div class="invalid-feedback">
                        <?php echo empty($emailError) ? '': $emailError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Enter Password:</label>
                    <input type="password" class="form-control <?php echo empty($passwordError) ? '':'is-invalid'; ?>" name="password" id="password" >
                    <div class="invalid-feedback">
                        <?php echo empty($passwordError) ? '': $passwordError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Enter Address:</label>
                    <textarea name="address" id="address" cols="30" rows="10" class="form-control <?php echo empty($addressError) ? '':'is-invalid'; ?>"></textarea>
                    <div class="invalid-feedback">
                        <?php echo empty($addressError) ? '': $addressError; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Enter Ph.no:</label>
                    <input type="tel" class="form-control <?php echo empty($telError) ? '':'is-invalid'; ?>" name="phone" id="phone" placeholder="09-123-456-789">
                    <div class="invalid-feedback">
                        <?php echo empty($telError) ? '': $telError; ?>
                    </div>
                </div>
                <div class="form-check mb-5">
                    <input class="form-check-input" type="checkbox" value="1" id="role" name="role">
                    <label class="form-label" for="role">
                        <b>Admin</b>
                    </label>
                </div>
                <input type="submit" value="Add" class="btn btn-warning">
                <a href="user_lists.php" class="btn btn-secondary">Back</a>
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
