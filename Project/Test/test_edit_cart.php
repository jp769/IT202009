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
	$name = $_POST["name"];
	$product = $_POST["product_id"];
	if ($product <= 0) {
	    $product = null;
	}
	$quantity = $_POST["quantity"]
	$price = $_POST["price"]
	$user = get_user_id();
	if (isset($id)) {
        $stmt = $db->prepare("UPDATE Cart set product_id=:product_id, quantity=:quantity, price=:price where id=:id");
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
<?php
//fetching
$result = [];
if (isset($id)) {
    $id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Cart where id = :id");
    $r = $stmt->execute([":id" => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
//get Products for dropdown
$db = getDB();
$stmt = $db->prepare("SELECT id,name,price from Products LIMIT 10");
$r = $stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="form">
    <h3>Edit Cart</h3>
    <form method="POST">
	<label for="prod-label">Product Name</label>
	<input name="name" placeHolder="Name" value="<?php echo $result["name];?>"/>
        <label for="prod-label">ProductID</label>
        <select name="product_id" value="<?php echo $result["product_id"];?>" >
            <option value="-1">None</option>
            <?php foreach ($products as $product): ?>
                <option value="<?php safer_echo($product["id"]); ?>" <?php echo ($result["product_id"] == $egg["id"] ? 'selected="selected"' : ''); ?>
                ><?php safer_echo($product["name"]); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="prod-label">Quantity</label>
        <input type="number" min="0" name="quantity" value="<?php echo $result["quantity"]; ?>"/>
        <label for="prod-label">Price</label>
        <input type="number" min="0.00" step=".01"  name="price" value="<?php echo $result["price"]; ?>"/>
        <input type="submit" name="save" value="Update"/>
    </form>
</div>

<?php require(__DIR__ . "/../partials/flash.php");
