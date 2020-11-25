<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    flash("You don't have permission to access this page.");
    die(header("Location: login.php"));
}
?>

    <div class="form">
        <form method="POST">
            <label for="prod-label">Name</label>
            <input name="name" maxlength="60" placeholder="Name"/>

            <label for="prod-label">Quantity</label>
            <input type="number" min="0" name="quantity"/>

            <label for ="prod-label">Price</label>
            <input type="number" min="0.00" value="0.00" step=".01" name="price"/>

            <label for ="prod-label">Description</label>
            <input type="text" name="description" placeholder="Small Description"/>

            <label for="prod-label">Category</label>
            <input type="text" name="category" maxlength="100" placeholder="Category"/>

            <label for="prod-label">Visibility</label>
            <input type="text" name="visibility" maxlength="5" placeholder="(true/false)"/>

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
    $visibility = $_POST["visibility"];
    $category = $_POST["category"];
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Products (name, quantity, price, description, user_id, visibility, category) VALUES(:name, :quantity, :price, :description, :user, :visibility, :category)");
    $r = $stmt->execute([
        ":name"=>$name,
        ":quantity"=>$quantity,
        ":price"=>$price,
        ":description"=>$description,
        ":user"=>$user,
        ":visibility"=>$visibility,
        ":category"=>$category
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
