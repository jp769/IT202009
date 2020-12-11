<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php

if(isset($_GET["user_id"])){
$id = $_GET["user_id"];
}
$continueB = False;
$db = getDB();

if(isset($_POST["update"])){
    $stmt = $db->prepare("UPDATE Cart set quantity = :q where id = :id");
    $r = $stmt->execute([":id"=>$_POST["cartID"], ":q"=>$_POST["quantity"]]);
    if($r){
        flash("Updated quantity", "success");
    }
}
if(isset($_POST["address"])){
    if(isset($db)){
    $addr = $_POST["addr"] . " " . $_POST["city"] . ", " . $_POST["state"] ." " . $_POST["zip"];
    $payment_method = $_POST["payMethod"];
    $total_price = $_POST["total"];
    echo($addr);
    echo($payment_method);
    echo($total_price);
    $stmt = $db->prepare("INSERT INTO Orders(user_id, total_price, address, payment_method) VALUES( :user_id, :total_price, :address, :payment_method)");
    $r = $stmt->execute([":user_id"=>get_user_id(), ":total_price"=>$total_price, ":address"=>$addr, ":payment_method"=>$payment_method]);
    $e = $stmt->errorInfo();
    if($r){
        flash("Updated orders");
        $continueB = True;
//        header("Location: confirm.php?id=$total_price");
    }
    else{
        flash("Unable to create order");
    }
    }
}

//fetching
$result = [];


$stmt = $db->prepare("SELECT cart.id, cart.quantity, cart.price, (cart.quantity * cart.price) as subtotal, cart.product_id, cart.user_id, Product.quantity as prodQuantity, Product.name as prod FROM Cart as cart LEFT JOIN Products Product on Product.id = cart.product_id where cart.user_id = :user_id");
$r = $stmt->execute([":user_id" => get_user_id()]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$result) {
    $e = $stmt->errorInfo();
    flash($e[2]);
}
?>

<div><h3>Checkout</h3></div>

<?php if (count($result) > 0): ?>
<?php $total = 0; $boolCheckout=True;?>
<?php foreach ($result as $r): ?>
<div class="card">
    <div class="card-title"></div>
        <form method="POST">
            <div class="card-body">
                <div>
                    <div>Product: <?php safer_echo($r["prod"]);?></div>
                    <?php if($r['prodQuantity'] < $r['quantity']): ?>
                    <?php $boolCheckout = False; ?>
                    <div>Quantity<input type = "number" style="width: 10%"min="0" name="quantity" value="<?php safer_echo($r["quantity"]); ?>"/>
                        <input type="hidden" name="cartID" value="<?php echo $r["id"];?>"/>
                    </div>
                    <?php else: ?>
                        <div>Quantity: <?php safer_echo($r["quantity"]); ?></div>
                    <?php endif; ?>
                    <div>Price: <?php safer_echo($r["price"]); ?></div>
                    <div>Subtotal: <?php echo ($r["subtotal"]); ?></div>
                    <?php $total += ($r['quantity'] * $r['price']); ?>
                </div>
                <?php if($r['prodQuantity'] < $r['quantity']): ?>
                    <div>Currently only have <?php echo $r['prodQuantity'];?> in stock. Please change quantity</div>
                    <input type="submit" class="btn btn-success" name="update" value="Update">
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
    <div class="Total">
        <div> Total: <?php safer_echo($total); ?></div>
    </div>
<?php if($boolCheckout and !($continueB)): ?>

    <br><br>
    <h4>Enter Shipping Address</h4>
    <div style="align-content: space-evenly">
        <form method="POST" id="address">
            <label for="adr">Address</label>
            <input type="text" id="adr" name="addr" placeholder="123 Example Street" required>
            <label for="city">City</label>
            <input type="text" id="city" name="city" placeholder="New York" required>

            <label for="state">State</label>
            <input type="text" id="state" name="state" placeholder="NY" maxlength="2" required>
            <label for="zip">Zip</label>
            <input type="number" id="zip" name="zip" placeholder="10001" maxlength="5" required>
            <br>
            <label for="payMethod">Payment Method</label>
            <input type="text" id="payMethod" name="payMethod" placeholder="Cash" required>
            <input type="hidden" name="total" value="<?php echo $total;?>"/>
            <input type="submit" class="btn btn-success" name="address" value="Enter Address">

        </form>
    </div>
<?php endif;?>
<?php if($continueB): ?>
    <a type="button" href="<?php echo getURL("confirm.php");?>?id=<?php safer_echo($total); ?>">Continue Checkout</a>
<!--    <input type="submit" class="btn btn-success" name="checkout" value="Confirm Checkout">-->
<?php endif;?>

<?php require(__DIR__ . "/partials/flash.php");