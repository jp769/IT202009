<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$result = [];

if(isset($_GET["id"])){
    $o_id = $_GET["id"];
    $u = get_user_id();
}

if(isset($o_id)){
    $o_id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT o.product_id, o.quantity, o.unit_price, Product.name as prod FROM OrderItems as o LEFT JOIN Products Product on Product.id = o.product_id where order_id = :order_id");
    $r = $stmt->execute([":order_id"=>$o_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }

}
?>

    <h3>Order:</h3>
<?php if (count($result) > 0): ?>
    <?php $total = 0; ?>
    <?php foreach ($result as $r): ?>
        <div class="card">
            <div class="card-title"></div>
            <form method="POST">
                <div class="card-body">
                    <div>
                        <p> - Info - </p>
                        <div>Name: <?php safer_echo($r["prod"]);?> </div>
                        <div><?php safer_echo($r["quantity"]); ?> </div>
                        <div>Unit Price: <?php safer_echo($r["unit_price"]); ?></div>

                        <?php $total += ($r['quantity'] * $r['unit_price']); ?>
                    </div>
                </div>
            </form>
        </div>
    <?php endforeach; ?>
    <div class="Total">
        <div> Total: <?php safer_echo($total); ?> </div>
    </div>

<?php endif; ?>


<?php require(__DIR__ . "/partials/flash.php");
