<?php
if(isset($_GET["id"])){
    $o_id = $_GET["id"];
    $u = get_user_id();
}

$result = [];
if(isset($id)){
    $o_id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM OrderItems where order_id = :order_id");
    $r = $stmt->execute([":0_id"=>$o_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>

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
