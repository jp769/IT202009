<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>
<?php
//fetching
$result = [];
if (isset($id)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Products.id,name,quantity,price,description,user_id, Users.username FROM Products as Products JOIN Users on Products.user_id = Users.id where Products.id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }

    $stmt2 = $db->prepare("SELECT count(*) as c FROM OrderItems oi JOIN Orders o on oi.order_id where user_id= :id AND product_id= :prod");
    $r2 = $stmt2->execute([":id"=>get_user_id(), ":prod"=>$id]);
    $check = $stmt->fetch(PDO::FETCH_ASSOC);
    $c = 0;
    if ($check) {
        $c = (int)$result["c"];
    }

}
?>

<?php if (isset($result) && !empty($result)): ?>
    <div class="card">
        <div class="card-title">
            <?php safer_echo($result["name"]); ?>
        </div>
        <div class="card-body">
            <div>
                <p>Info</p>
                <div>Quantity: <?php safer_echo($result["quantity"]); ?></div>
                <div>Price: <?php safer_echo($result["price"]); ?></div>
                <div>Description: <?php safer_echo($result["description"]); ?></div>
                <div>Creator: <?php safer_echo($result["username"]); ?></div>
            </div>
            <?php if ($c > 0):?>
            <div>
                <a type="button" href="<?php echo getURL("rating.php");?>?id=<?php safer_echo($result['id']); ?>">Rate Product</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <p>Error looking up id...</p>
<?php endif; ?>
<?php require(__DIR__ . "/partials/flash.php");
