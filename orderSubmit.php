<?php
session_start();
require('config/config.php');
require('config/common.php');

if(empty($_SESSION['user_id']) && empty($_SESSION['username'])){
	header('location:login.php');
}
if(isset($_SESSION['cart'])){
	$total=0;
	$user=$_SESSION['user_id'];
	foreach($_SESSION['cart'] as $key=>$qty){
		$id=str_replace('id:','',$key);
		
		$stmt=$db->prepare("SELECT * FROM products WHERE id=$id");
		$stmt->execute();
		$productResult=$stmt->fetch(PDO::FETCH_ASSOC);
		$total +=$productResult['price']*$qty;
	}
	$saleOrder=$db->prepare('INSERT INTO sale_orders(customer_id,total_price,order_date) VALUES(:cut_id,:total,:orderDate)');
	$saleOrder_Result=$saleOrder->execute([
		":cut_id"=>$user,
		":total"=>$total,
		":orderDate"=>date("Y-m-d H:i:s"),
	]);
	if($saleOrder_Result){
		$saleOrder_id=$db->lastInsertId();
		foreach($_SESSION['cart'] as $key=>$qty){
			$id=str_replace('id:','',$key);
			
			$saleOrderDetailStmt=$db->prepare('INSERT INTO sale_order_details(sale_order_id,product_id,quantity,order_date) VALUES(:sale_order_id,:product_id,:quantity,:orderDate)');
			$saleOrderDetailResult=$saleOrderDetailStmt->execute([
				":sale_order_id"=>$saleOrder_id,
				":product_id"=>$id,
				":quantity"=>$qty,
				":orderDate"=>date("Y-m-d H:i:s"),
			]);

			$stmt=$db->prepare("SELECT * FROM products WHERE id=$id");
			$stmt->execute();
			$productResult=$stmt->fetch(PDO::FETCH_ASSOC);
			$stockProduct=$productResult['quantity']-$qty;

			$updateProduct= $stmt=$db->prepare("UPDATE products SET quantity=:stockqty WHERE id=$id");
			$updateProduct->execute([
				':stockqty'=>$stockProduct,
			]);
		}
	}
	unset($_SESSION['cart']);
}
?>
<?php include('header.php'); ?>
	<!--================Order Details Area =================-->
	<section class="order_details section_gap">
		<div class="container">
			<h3 class="title_confirmation">Thank you. Your order has been received.</h3>
		</div>
	</section>
	<!--================End Order Details Area =================-->
	<?php include('footer.php'); ?>
	
