<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
	flash("You don't have permission to access this page.");
	die(header("Location: ../login.php"));
}
?>
<?php
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Products where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

$db = getDB();
$stmt = $db->prepare("SELECT id,name from Products LIMIT 10");
$r = $stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="form">
<form method="POST">
	<label for="prod-label">Create Cart:</label>

	<label for="prod-label">Product</label>
	<select name="product_id" value="<?php echo $result["product_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php safer_echo($product["id"]); ?>"
                ><?php safer_echo($product["id"]); ?></option>
            <?php endforeach; ?>
        </select>
	<label for="prod-label">Quantity</label>
	<input type="number" min="1" name="quantity"/>
	<label for="prod-label">Price</label>
	<input type="number" name="price" value=<?php safer_echo($product["price"]);?>">
	<input type="submit" name="save" value="Create"/>
</form>
</div>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$product = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Cart (product_id,quantity,price,user_id) VALUES(:product_id,:quantity,:price,:user)");
	$r = $stmt->execute([
		":product_id"=>$product,
		":quantity"=>$quantity,
		":price"=>$price,
		":user"=>$user
	]);
	if($r){
		flash("Created successfully with id: " . $db->lastInsertId());
	}
	else{
		$e = $stmt->errorInfo();
		flash("Error creating: " . var_export($e, true));
	}
}

?>
<?php require(__DIR__ . "/../partials/flash.php");
