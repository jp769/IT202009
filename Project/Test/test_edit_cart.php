<?php require_once(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: ../login.php"));
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

	$product = $_POST["product_id"];
	if ($product <= 0) {
	    $product = null;
	}
	$quantity = $_POST["quantity"]
	$price = $_POST["price"]
	$user = get_user_id();
	$db = getDB();
	if (isset($id)) {
            $stmt = $db->prepare("UPDATE Cart set product_id=:product_id, price=:price, quantity=:quantity where id=:id");
	    $r = $stmt->execute([
            	":product_id" => $product,
		":quantity" => $quantity,
		":price" => $price,
            	":id" => $id
	    ]);
	    if ($r) {
	        flash("Updated successfully with id: " . $id);
            }
            else {
            	$e = $stmt->errorInfo();
            	flash("Error updating: " . var_export($e, true));
            }
	}
      	else {
       	    flash("ID isn't set, we need an ID in order to update");
    	}
}
?>
//fetching


<div class="form">
    <form method="POST">
        <label for="prod-label">ProductID</label>
        <label for="prod-label">Quantity</label>
        <input type="number" min="0" name="quantity" value="<?php echo $result["quantity"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
    </form>
</div>

<?php require(__DIR__ . "/../partials/flash.php");
