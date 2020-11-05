<?php require_once(__DIR__ . "/partials/nav.php");?>
<?php
if (!has_role("Admin")) {
	flash("You don't have permission to access this page.");
	die(header("Location: login.php"));
}
?>

<form method="POST">
	<label>Name</label>
	<input name="name" placeholder="Name"/>
	<label>Quantity</label>
	<input type="number" min="0" name="quantity"/>
	<label>Price</label>
	<input type="number" min="0.00" value="0.00" step=".01" name="price"/>
	<label>Description</label>
	<input type="text" name="description" placeholder="Small Description"/>
	<input type="submit" name="save" value="Create"/>
</form>

<?php
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$name = $_POST["name"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$dscrp = $_POST["description"];
	$crt = date('Y-m-d H:i:s');//calc
	$modf = date("Y-m-d  H:i:s.");
	$user = get_user_id();
	$db = getDB();
	$stmt = $db->prepare("INSERT INTO Products (name, quantity, price, description, modified, created, user_id) VALUES(:name, :quantity, :price, :dscrp, :crt, :modf, :user)");
	$r = $stmt->execute([
		":name"=>$name,
		":price"=>$price,
		":quantity"=>$quantity,
		":description"=>$dscrp,
		":created"=>$crt,
		":modified"=>$modf,
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
<?php require(__DIR__ . "/partials/flash.php");
