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

$db = getDB();

if(isset($_POST["update"])){
    $stmt = $db->prepare("UPDATE Cart set quantity = :q where id = :id");
    $r = $stmt->execute([":id"=>$_POST["cartID"], ":q"=>$_POST["quantity"]]);
    if($r){
        flash("Updated quantity", "success");
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
                    <div>Quantity<input type = "number" min="0" name="quantity" value="<?php safer_echo($r["quantity"]); ?>"/>
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
        <div> Total: <?php safer_echo($total); ?> </div>
    </div>
<?php if($boolCheckout): ?>
<form>
    <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
    <input type="text" id="adr" name="address" placeholder="123 Example Street">
    <label for="city"><i class="fa fa-institution"></i> City</label>
    <input type="text" id="city" name="city" placeholder="New York">

    <label for="state">State</label>
    <input type="text" id="state" name="state" placeholder="NY" maxlength="2">
    <label for="zip">Zip</label>
    <input type="number" id="zip" name="zip" placeholder="10001" maxlength="5">

</form>
<?php else: ?>
    <div><?php flash("Change Quantity To Continue"); ?></div>
<?php endif; ?>