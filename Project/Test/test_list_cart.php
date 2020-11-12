<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: ../login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT cart.id,cart.product_id, Users.username from Cart as cart JOIN Users on cart.user_id = Users.id LEFT JOIN Products as product on cart.product_id = product.id WHERE cart.id like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>

    <h3>List Cart</h3>
    <form method="POST">
        <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
        <input type="submit" value="Search" name="search"/>
    </form>

    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Product ID:</div>
                            <div><?php safer_echo($r["product_id"]); ?></div>
                        </div>
                        <div>
                            <div>Product:</div>
                            <div><?php safer_echo($r["product"]); ?></div>
                        </div>
                        <div>
                            <div>Owner:</div>
                            <div><?php safer_echo($r["username"]); ?></div>
                        </div>
                        <div>
                            <a type="button" href=<?php echo getURL("Test/test_edit_cart.php");?>?id=<?php safer_echo($r['id']); ?>>Edit</a>
                            <a type="button" href=<?php echo getURL("Test/test_view_cart.php");?>?id=<?php safer_echo($r['id']); ?>>View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>
<?php require(__DIR__ . "/../partials/flash.php");
