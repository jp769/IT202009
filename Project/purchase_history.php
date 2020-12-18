<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();

//pagination
$per_page = 10;

if(!has_role("Admin")){
$query = "SELECT count(*) as total from Orders o where o.user_id = :id";
$params = [":id"=>get_user_id()];
paginate($query, $params, $per_page);
}
else{
    $query = "SELECT count(*) as total from Orders";
    $params = [":id"=>get_user_id()];
    paginate($query, $params, $per_page);
}

//fetch OrderItems using userId
$result =[];
if(isset($db)) {
    if(!has_role("Admin")) {
        $id = get_user_id();

        $stmt = $db->prepare("SELECT * FROM Orders WHERE user_id = :user_id ORDER BY created DESC LIMIT :offset, :count");
        $stmt->bindValue(":user_id", $id, PDO::PARAM_STR);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $r = $stmt->execute();
    }
    else{
        $stmt = $db->prepare("SELECT * FROM ORDER BY created DESC Orders LIMIT :offset, :count");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $r = $stmt->execute();
    }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            $e = $stmt->errorInfo();
            flash($e[2]);
        }

    if (count($result) > 0) {
        foreach ($result as $r) {
            $o_id = $r['id'];
            $stmt1 = $db->prepare("SELECT * FROM OrderItems LEFT JOIN Products Product on Product.id = OrderItems.product_id WHERE order_id = :order_id ");
            $r1 = $stmt1->execute(["order_id"=>$o_id]);
            $result1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            ?><p> Products (Order: <?php echo $o_id;?>)</p>
            <?php
            foreach ($result1 as $r2):?>
                <div class="card">
                <div class="card-title"></div>
                <form method="POST">
                    <div class="card-body">
                        <div>
                            <div>Name: <?php safer_echo($r2["name"]);?> </div>
                            <div>Quantity: <?php safer_echo($r2["quantity"]); ?> </div>
                            <div>Unit Price: <?php safer_echo($r2["unit_price"]); ?></div>
<br>
                        </div>
                    </div>
                </form>
                </div>
            <?php endforeach;?>
                <div class="Total">
                    <div> Total: <?php safer_echo($r["total_price"]); ?> </div>
                </div>
            <br>
            <br>
<?php
        }

    }
}?>

</div>
<?php include(__DIR__."/partials/pagination.php");?>
</div>

<?php require(__DIR__ . "/partials/flash.php");
