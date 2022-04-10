
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Ap Shopping Admin</title>

  <!-- Font Awesome Icons -->
  <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <script src="https://kit.fontawesome.com/dd2800783e.js" crossorigin="anonymous"></script>
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <?php 
      $link=$_SERVER['PHP_SELF'];
      $arraylink=explode('/',$link);
      $page=end($arraylink);
    ?>
    <!-- SEARCH FORM -->
    <?php if($page=='index.php' || $page=='category_lists.php' || $page=='user_lists.php'): ?>
      <form class="form-inline ml-3" method="post" action="<?php if($page=='index.php'){ echo 'index.php';}elseif($page=='category_lists.php'){  echo 'category_lists.php'; }else{ echo 'user_lists.php'; } ?>">
        <input name="_token" type="hidden"  value="<?php echo $_SESSION['_token']; ?>">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" name="search" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    <?php endif ?>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Ap Shopping</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['username'] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="index.php" class="nav-link">
            <i class="fas fa-box-open"></i>
              <p>
                Product
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="category_lists.php" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Category
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="user_lists.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="order_lists.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Order
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview menu 
          <?php if($page=='weeklyReport.php' || $page=='monthlyReport.php' || $page=='royalUser.php' || $page=='bestItems.php'){ echo 'menu-open';}  ?>">
            <a href="weeklyReport.php" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
               Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="weeklyReport.php" class="nav-link <?php echo $page=="weeklyReport.php"?'active':'' ?>">
                
                  <i class="far fa-circle nav-icon"></i>
                  <p>Weekly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="monthlyReport.php" class="nav-link <?php echo $page=="monthlyReport.php"?'active':'' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="royalUser.php" class="nav-link <?php echo $page=="royalUser.php"?'active':'' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Royal User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="bestItems.php" class="nav-link <?php echo $page=="bestItems.php"?'active':'' ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Best Seller Items</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="content-header">
  </div>
