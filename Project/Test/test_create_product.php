<?php require_once(__DIR__ . "../partials/nav.php");?>
<?php
if (!has_role("Admin")) {
	flash("You don't have permission to access this page.");
	die(header("Location: login.php"));
}
?>
<div class="form">
<form method="POST">
	<label for="prod-label">Name</label>
	<input name="name" placeholder="Name"/>
	<label for="prod-label">Quantity</label>
	<input type="number" min="0" name="quantity"/>
	<label for ="prod-label">Price</label>
	<input type="number" min="0.00" value="0.00" step=".01" name="price"/>
	<label for ="prod-label">Description</label>
	<input type="text" name="description" placeholder="Small Description"/>
	<input type="submit" name="save" value="Create"/>
</form>
</div>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$name = $_POST["name"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$description = $_POST["description"];
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Products (name, quantity, price, description, user_id) VALUES(:name, :quantity, :price, :description, :user)");
	$r = $stmt->execute([
		":name"=>$name,
		":quantity"=>$quantity,
		":price"=>$price,
		":description"=>$description,
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
