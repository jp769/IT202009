<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
$query = "";
$sort = "";
$per_page = 10;
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];

    if(isset($_POST["price_sort"])) {
        $sort = $_POST["price_sort"];
    }
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();

    $stmt = $db->prepare("SELECT count(*) from Products WHERE quantity > 0 AND name like :q or category like :q");
    $params = [":q" => "%$query%"];
    paginate($query, $params, $per_page);

    if($sort == "ASC"){
        $stmt = $db->prepare("SELECT id,name,quantity,price,description,user_id,visibility,category from Products WHERE quantity > 0 AND name like :q or category like :q ORDER BY price ASC LIMIT :offset, :count");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $stmt->bindValue(":q", $query);
        $r = $stmt->execute();
    }
    elseif ($sort == "DESC"){
        $stmt = $db->prepare("SELECT id,name,quantity,price,description,user_id,visibility,category from Products WHERE quantity > 0 AND name like :q or category like :q ORDER BY price DESC LIMIT :offset, :count");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $r = $stmt->execute([":q" => "%$query%"]);
    }
    else {
        $stmt = $db->prepare("SELECT id,name,quantity,price,description,user_id,visibility,category from Products WHERE quantity > 0 AND name like :q or category like :q  LIMIT :offset, :count");
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindValue(":count", $per_page, PDO::PARAM_INT);
        $r = $stmt->execute([":q" => "%$query%"]);
    }

    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
}
?>
    <script>
        function addToCart(itemId, cost){

            //https://www.w3schools.com/xml/ajax_xmlhttprequest_send.asp
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    let json = JSON.parse(this.responseText);
                    if (json) {
                        if (json.status == 200) {
                            alert(json.message);
                        } else {
                            alert(json.error);
                        }
                    }
                }
            };
            xhttp.open("POST", "<?php echo getURL("api/add_to_cart.php");?>", true);
            //this is required for post ajax calls to submit it as a form
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //map any key/value data similar to query params
            xhttp.send("itemId="+itemId);
        }
    </script>

    <form method="POST">
        <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
        <label for="p_Sort">Choose to Sort by Price:</label>
        <select name="price_sort" >
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
                            <div>Price:</div>
                            <div><?php safer_echo($r["price"]); ?></div>
                        </div>

                        <div>
                            <div>Category:</div>
                            <div><?php safer_echo($r["category"]); ?></div>
                        </div>

                        <?php if (has_role("Admin")): ?>
                        <div>
                            <div>Visibility</div>
                            <div><?php safer_echo($r["visibility"]); ?></div>
                        </div>
                        <?php endif;?>

                        <div>
                            <?php if (has_role("Admin")): ?>
                                <a type="button" href=<?php echo getURL("product_edit.php");?>?id=<?php safer_echo($r['id']); ?>>Edit</a>
                            <?php endif;?>
                            <a type="button" href="<?php echo getURL("product_view.php");?>?id=<?php safer_echo($r['id']); ?>">View</a>
                        </div><br>

                        <?php if ((is_logged_in()) && ($r["visibility"] == "true")): ?>
                            <div>
                                <button type="button" onclick="addToCart(<?php echo $r["id"];?>,<?php echo $r["price"];?>);" class="btn btn-primary btn-lg">Add to Cart</button>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>
                <?php endforeach; ?>
            </div>
    </div>
    <?php include(__DIR__."/partials/pagination.php");?>
    </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>


<?php require(__DIR__ . "/partials/flash.php");
