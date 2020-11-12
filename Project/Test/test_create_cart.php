<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
	flash("You don't have permission to access this page.");
	die(header("Location: ../login.php"));
}
?>

<div class="form">
<form method="POST">
	<label for="prod-label">Cart:</label>

	<label for="prod-label">Product ID</label>
	<input name="product_id" placeholder="Product ID"/>
	<label for="prod-label">Quantity</label>
	<input type="number" min="0" name="quantity"/>
	<label for ="prod-label">Price</label>
	<input type="number" min="0.00" value="0.00" step=".01" name="price"/>


	<input type="submit" name="save" value="Create"/>
</form>
</div>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$product_id = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Cart (product_id, quantity, price, user_id) VALUES(:product_id, :quantity, :price, :user)");
	$r = $stmt->execute([
		":product_id"=>$product_id,
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
