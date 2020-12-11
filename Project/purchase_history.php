<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();

//fetch OrderItems using userId
$result =[];
if(isset($db)) {
    $id = get_user_id();

    $stmt = $db->prepare("SELECT * FROM ORERS WHERE user_id = :user_id LIMIT 10");
    $r = $stmt->execute(["user_id"=>get_user_id()]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }

    if (count($result) > 0) {

        foreach ($result as $r) {
            $o_id = $r['order_id'];
            $stmt1 = $db->prepare("SELECT * FROM OrderItems LEFT JOIN Products Product on Product.id = OrderItems.product_id WHERE order_id = :order_id");
            $r1 = $stmt1->execute(["order_id"=>$o_id]);
            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result1 as $r2):?>
                <div class="card">
                <div class="card-title"></div>
                <form method="POST">
                    <div class="card-body">
                        <div>
                            <p> - Info - </p>
                            <div>Name: <?php safer_echo($r2["name"]);?> </div>
                            <div>Quantity: <?php safer_echo($r2["quantity"]); ?> </div>
                            <div>Unit Price: <?php safer_echo($r2["unit_price"]); ?></div>

                        </div>
                    </div>
                </form>
                </div>
                <div class="Total">
                <div> Total: <?php safer_echo($r["total_price"]); ?> </div>
                </div>
            <?php endforeach;
        }

    }
}

require(__DIR__ . "/partials/flash.php");
