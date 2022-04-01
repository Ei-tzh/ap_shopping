<?php 
session_start();
require('config/config.php');
require('config/common.php');

if(empty($_SESSION['user_id']) && empty($_SESSION['username'])){
	header('location:login.php');
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
<?php include('header.php') ?>
		<?php
		if(!empty($_GET['category_id'])){
			exit();
		}
            if(empty($_GET['pageno'])){
              $pageno=1;
            }else{
              $pageno=$_GET['pageno'];
            }
            $numOfRec=6;
            $offset=($pageno-1)*$numOfRec;
            if(empty($_POST['search']) && empty($_COOKIE['search'])){
				if(empty($_GET['category_id'])){
					echo"Hello";
					$stmt=$db->prepare("SELECT * FROM products ORDER BY id");
					$stmt->execute();
					$rawresult=$stmt->fetchAll();
					$totalpages=ceil(count($rawresult)/$numOfRec);

					$stmtproducts=$db->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRec");
					$stmtproducts->execute();
					$resultproducts=$stmtproducts->fetchAll();
				}else{
					$id=$_GET['id'];
					exit();
					$stmt=$db->prepare("SELECT * FROM products ORDER BY id WHERE id=$id");
					$stmt->execute();
					$rawresult=$stmt->fetchAll();
					$totalpages=ceil(count($rawresult)/$numOfRec);

					$stmtproducts=$db->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRec WHERE id=$id");
					$stmtproducts->execute();
					$resultproducts=$stmtproducts->fetchAll();
				}
              
            }else{
              if(!empty($_POST['search'])){
                $search=$_POST['search'];
              }else{
                $search=$_COOKIE['search'];
              }
			  echo $search;
              $stmt=$db->prepare("SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id");
              $stmt->execute();
              $rawresult=$stmt->fetchAll();
              $totalpages=ceil(count($rawresult)/$numOfRec);

              $stmtproducts=$db->prepare("SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfRec");
              $stmtproducts->execute();
              $resultproducts=$stmtproducts->fetchAll();

              
            }
			?>
	<div class="container">
		<div class="row">
			<?php
				$catstmt=$db->prepare('SELECT * FROM categories');
				$catstmt->execute();
				$catresult=$catstmt->fetchALL();
			?>
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
						<ul class="main-categories">
							<?php foreach($catresult as $val): ?>
							<li class="main-nav-list">
								<a data-toggle="collapse" href="?category_id=<?php echo escape($val['id']); ?>">
									<span class="lnr lnr-arrow-right"></span><?php echo escape($val['name']) ?>
								</a>
							</li>
							<?php endforeach ?>
						</ul>
					</div>
				</div>
			<!-- end category section -->
			<!-- pagination -->
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?pageno=1" class="">First</a>

						<a href="<?php if($pageno<=1){ echo '#';}else{ echo '?pageno='.$pageno-1;} ?>" class="prev-arrow" style="<?php if($pageno<=1){ echo 'pointer-events: none';} ?>">
							<i class="fa fa-long-arrow-left <?php if($pageno<=1){ echo 'text-secondary';} ?>" aria-hidden="true"></i>
						</a>

						<a href="" style="pointer-events: none" class="active"><?php echo $pageno; ?></a>

						<a href="<?php if($pageno>=$totalpages){echo '#';} else{ echo "?pageno=".($pageno+1);} ?>" class="next-arrow" style="<?php if($pageno>=$totalpages){ echo 'pointer-events: none';} ?>">
							<i class="fa fa-long-arrow-right <?php if($pageno>=$totalpages){ echo 'text-secondary';} ?>" aria-hidden="true"></i>
						</a>

						<a href="?pageno=<?php echo $totalpages; ?>" class="">Last</a>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">
						<?php foreach($resultproducts as $value): ?>
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<img class="img-fluid" src="admin/<?php echo escape($value['image']) ?>" alt="" style="height:250px;object-fit: cover;">
								<div class="product-details">
									<h6><?php echo escape($value['name']) ?></h6>
									<div class="price">
										<h6><?php echo escape($value['price']) ?></h6>
									</div>
									<div class="prd-bottom">

										<a href="" class="social-info">
											<span class="ti-bag"></span>
											<p class="hover-text">add to bag</p>
										</a>
										<a href="" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach ?>
					</div>
				</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>
