<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php

?>
<?php

$db = getDB();

if(isset($_POST["delete"])){
//    $stmt = $db->prepare("DELETE FROM Cart where id = :id");
//    $r = $stmt->execute([":id"=>$_POST["cartID"]]);
    //fix for example bug
    $stmt = $db->prepare("DELETE FROM Cart where id = :id AND user_id = :uid");
    $r = $stmt->execute([":id"=>$_POST["cartID"], ":uid"=>get_user_id()]);
    if($r){
        flash("Deleted item from cart", "success");
    }
}

if(isset($_POST["update"])){
    $stmt = $db->prepare("UPDATE Cart set quantity = :q where id = :id");
    $r = $stmt->execute([":id"=>$_POST["cartID"], ":q"=>$_POST["quantity"]]);
    if($r){
        flash("Updated quantity", "success");
    }
}

//fetching
$result = [];


$stmt = $db->prepare("SELECT cart.id, cart.quantity, cart.price, (cart.quantity * cart.price) as subtotal, cart.product_id, cart.user_id,Product.name as prod FROM Cart as cart LEFT JOIN Products Product on Product.id = cart.product_id where cart.user_id = :user_id");
$r = $stmt->execute([":user_id" => get_user_id()]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$result) {
    $e = $stmt->errorInfo();
    flash($e[2]);
}

?>
<h3>Cart</h3>
<?php if (count($result) > 0): ?>
<?php $total = 0; ?>
    <?php foreach ($result as $r): ?>
    <div class="card">
        <div class="card-title"></div>
        <form method="POST">
        <div class="card-body">
            <div>
                <p> - Info - </p>
                <div>Name:<a href=<?php echo getURL("product_view.php");?>?id=<?php safer_echo($r['product_id']); ?>> <?php safer_echo($r["prod"]);?></a></div>
                <div><input type = "number" min="0" name="quantity" value="<?php safer_echo($r["quantity"]); ?>"/>
                    <input type="hidden" name="cartID" value="<?php echo $r["id"];?>"/>
                </div>
                <div>Price: <?php safer_echo($r["price"]); ?></div>
                <div>Subtotal: <?php echo ($r["subtotal"]); ?></div>
                <?php $total += ($r['quantity'] * $r['price']); ?>
            </div>
        </div>
            <input type="submit" class="btn btn-success" name="update" value="Update">
        </form>
        <div>
            <form method="POST">
            <input type="hidden" name="cartID" value="<?php safer_echo($r["id"]);?>"/>
            <input type="submit" class="btn btn-danger" name="delete" value="Delete Item"/>
            </form>
        </div>>
    </div>
    <?php endforeach; ?>
    <div class="Total">
        <div> Total: <?php safer_echo($total); ?> </div>
    </div>
<?php else: ?>
    <p>Empty Cart</p>
<?php endif; ?>

<?php require(__DIR__ . "/partials/flash.php");
