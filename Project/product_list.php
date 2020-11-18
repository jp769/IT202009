<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$sort = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
    if(isset($_POST["price_sort"]))
        $sort = $_POST["price_sort"];
    safer_echo($sort);
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id,name,quantity,price,description,user_id,visibility,category from Products WHERE name like :q or category like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>

    <form method="POST">
        <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>

        <select name="price_sort" placeholder="Sort by:">
            <option value="">None</option>
            <option value="ASC">Ascending Price</option>
            <option value="DESC">Descending Price</option>
        </select>
        <input type="submit" value="Search" name="search"/>
    </form>


    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>

                <?php if (($r["visibility"] == "true") || (has_role('Admin'))): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Name:</div>
                            <div><?php safer_echo($r["name"]); ?></div>
                        </div>
                        <div>
                            <div>Quantity:</div>
                            <div><?php safer_echo($r["quantity"]); ?></div>
                        </div>
                        <div>
                            <div>Price:</div>
                            <div><?php safer_echo($r["price"]); ?></div>
                        </div>
                        <div>
                            <div>Description:</div>
                            <div><?php safer_echo($r["description"]); ?></div>
                        </div>
                        <div>
                            <div>Category:</div>
                            <div><?php safer_echo($r["category"]); ?></div>
                        </div>
                        <div>
                            <div>Owner Id:</div>
                            <div><?php safer_echo($r["user_id"]); ?></div>
                        </div>
                        <?php if (has_role("Admin")): ?>
                        <div>
                            <div>Visibility</div>
                            <div><?php safer_echo($r["visibility"]); ?></div>
                        </div>
                        <?php endif;?>

                        <div>
                            <a type="button" >Edit</a>
                            <a type="button" >View</a>
                        </div><br>
                    </div>
                <?php endif;?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>

<?php require(__DIR__ . "/partials/flash.php");
