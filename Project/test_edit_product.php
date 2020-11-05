<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php

//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$name = $_POST["name"];
	$quantity = $_POST["quantity"];
	$price = $_POST["price"];
	$description = $_POST["description"];
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE Products set name=:name, quantity=:quantity, price=:price, description=:description where id=:id");
		//$stmt = $db->prepare("INSERT INTO F20_Eggs (name, state, base_rate, mod_min, mod_max, next_stage_time, user_id) VALUES(:name, :state, :br, :min,:max,:nst,:user)");
		$r = $stmt->execute([
			":name"=>$name,
			":quantity"=>$quantity,
			":price"=>$price,
			":description"=>$description
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Products where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="form">
<form method="POST">
        <label for="prod-label">Name</label>
        <input name="name" placeholder="Name" value="<?php echo $result["name"];?>"/>
        <label for="prod-label">Quantity</label>
        <input type="number" min="0" name="quantity" value="<?php echo $result["quantity"];?>"/>
        <label for="prod-label">Price</label>
        <input type="number" min="0.00" step=".01" name="price" value="<?php echo $result["price"];?>"/>
        <label for="prod-label">Description</label>
        <input type="text" name="description" placeholder="Small Description" value="<?php echo $result["description"];?>"/>
        <input type="submit" name="save" value="Update"/>
</form>
</div>

<?php require(__DIR__ . "/partials/flash.php");
