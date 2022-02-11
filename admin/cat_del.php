<?php
session_start();
require('../config/config.php');
require('../config/common.php');

$category_ID=$_GET['id'];
$stmt=$db->prepare("DELETE FROM categories WHERE id=:id");
$stmt->execute([
    ':id'=>$category_ID,
]);
header('Location:category_lists.php');