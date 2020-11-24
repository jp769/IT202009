<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php

//Load cart into rows

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT cart.id,cart.quantity,cart.price,cart.product_id, Users.username, Product.name as prod FROM Cart as cart JOIN Users on cart.user_id = Users.id LEFT JOIN Products Product on Product.id = cart.product_id where cart.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>
<h3>Cart</h3>
<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["product_id"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p> - Info - </p>
                <div>Name:<a href=""> <?php safer_echo($result["prod"]);?></a></div>
                <div>Quantity: <?php safer_echo($result["quantity"]); ?></div>
                <div>Price: <?php safer_echo($result["price"]); ?></div>
                <div>Subtotal: <?php $result["quantity"] * $result["price"] ?></div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>

<?php require(__DIR__ . "/../partials/flash.php");
