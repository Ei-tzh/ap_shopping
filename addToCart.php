<?php
session_start();
require('config/config.php');
require('config/common.php');

if(empty($_SESSION['user_id']) && empty($_SESSION['username'])){
	header('location:login.php');
}

if($_POST){
    
    $id=$_POST['id'];
    $qty=$_POST['qty'];

    $stmt=$db->prepare("SELECT * FROM products WHERE id=$id");
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if($qty>$result['quantity']){
        $_SESSION['error']="Not Enough Stock!";
        header('Location:product_detail.php?id='.$id);
    }else{
        if(isset($_SESSION['cart']['id:'.$id])){
            $_SESSION['cart']['id:'.$id] +=$qty;
        }else{
            $_SESSION['cart']['id:'.$id] =$qty;
        }
       header('Location:cart.php');
    }
   
}
?>