<?php
require('../config/config.php');

$stmt=$db->prepare("DELETE FROM products WHERE id=".$_GET['id']);
$stmt->execute();

header('Location:index.php');