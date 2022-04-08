<?php
session_start();
unset($_SESSION['cart']['id:'.$_GET['item_id']]);
header("Location:cart.php");
?>