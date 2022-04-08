<?php 
session_start();
require('config/config.php');
require('config/common.php');

if(empty($_SESSION['user_id']) && empty($_SESSION['username'])){
	header('location:login.php');
}
?>
<?php include('header.php'); ?>
    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <?php if(isset($_SESSION['cart'])) :?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total=0;
                            foreach($_SESSION['cart'] as $key=>$qty):
                                $id=str_replace('id:','',$key);
                               $stmt=$db->prepare("SELECT * FROM products WHERE id=$id");
                               $stmt->execute();
                               $result=$stmt->fetch(PDO::FETCH_ASSOC);

                               $total += $result['price']*$qty;
                            ?>
                            <tr>
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="admin/<?php echo escape($result['image']);?>" style="width:100px;height:110px;object-fit:cover;">
                                        </div>
                                        <div class="media-body">
                                            <p><?php echo escape($result['name']) ;?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo escape($result['price'])." MMK" ;?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="text" name="qty" id="sst"  value="<?php echo $qty;?>" title="Quantity:" readonly
                                            class="input-text qty">
                                        
                                    </div>
                                </td>
                                <td>
                                    <h5><?php echo escape($result['price'])*$qty.' MMK' ;?></h5>
                                </td>
                                <td>
                                    <a href="clearItem.php?item_id=<?php echo escape($result['id']); ?>" class="primary-btn" style="line-height:inherit;padding:5px 30px;">Clear</a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                            <tr>
                                <td></td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo $total.' MMK' ;?></h5>
                                </td>
                            </tr>
                            <!-- <tr class="shipping_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Shipping</h5>
                                </td>
                                <td>
                                    <div class="shipping_box">
                                        <ul class="list">
                                            <li><a href="#">Flat Rate: $5.00</a></li>
                                            <li><a href="#">Free Shipping</a></li>
                                            <li><a href="#">Flat Rate: $10.00</a></li>
                                            <li class="active"><a href="#">Local Delivery: $2.00</a></li>
                                        </ul>
                                        <h6>Calculate Shipping <i class="fa fa-caret-down" aria-hidden="true"></i></h6>
                                        <select class="shipping_select">
                                            <option value="1">Bangladesh</option>
                                            <option value="2">India</option>
                                            <option value="4">Pakistan</option>
                                        </select>
                                        <select class="shipping_select">
                                            <option value="1">Select a State</option>
                                            <option value="2">Select a State</option>
                                            <option value="4">Select a State</option>
                                        </select>
                                        <input type="text" placeholder="Postcode/Zipcode">
                                        <a class="gray_btn" href="#">Update Details</a>
                                    </div>
                                </td>
                            </tr> -->
                            <tr class="out_button_area">
                                <td></td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                    <a class="gray_btn" href="clearAll.php">Clear All</a>
                                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                                        <a class="gray_btn" href="orderSubmit.php">Order Submit</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="alert alert-secondary">
                    There are no items in cart!
                    <a href="index.php">Go Shopping</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

<?php include('footer.php'); ?>  
