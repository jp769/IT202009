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
//fetching
$result = [];
if (is_logged_in()) {
    $db = getDB();
    $user_id = $_SESSION["user"]["id"];
    $stmt = $db->prepare("SELECT cart.id, cart.quantity, cart.price, cart.product_id, cart.user_id,Product.name as prod FROM Cart as cart JOIN Users on cart.user_id = Users.id LEFT JOIN Products Product on Product.id = cart.product_id where cart.user_id = :user_id");
    $r = $stmt->execute([":user_id" => $user_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<h3>Cart</h3>
<?php if (count($result) > 0): ?>
<?php $total = 0; ?>
    <?php foreach ($result as $r): ?>
    <div class="card">
        <div class="card-title">
        </div>
        <div class="card-body">
            <div>
                <p> - Info - </p>
                <div>Name:<a href=<?php echo getURL("product_edit.php");?>?id=<?php safer_echo($r['product_id']); ?>> <?php safer_echo($r["prod"]);?></a></div>
                <div>Quantity: <?php safer_echo($r["quantity"]); ?></div>
                <div>Price: <?php safer_echo($r["price"]); ?></div>
                <div>Subtotal: <?php echo ($r["quantity"] * $r["price"]); ?></div>
                <?php $total += ($r['quantity'] * $r['price']); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="Total">
        <div> Total: <?php safer_echo($total); ?> </div>
    </div>
<?php else: ?>
    <p>Empty Cart</p>
<?php endif; ?>

<?php require(__DIR__ . "/partials/flash.php");
