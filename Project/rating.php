<?php require_once(__DIR__ . "/partials/nav.php");

if (is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must log in to access this page");
    die(header("Location: login.php"));
}
?>
<?php if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
?>

    <form method="POST">
        <label for="prod-label">Rating (1-5)</label>
        <input type="number" min="1" max="5" name="rating"/>

        <label for ="prod-label">Comment</label>
        <input type="text" name="comment" placeholder="Comment on Product"/>

        <input type="submit" name="rate" id="rate" value="Post Rating"/>
    </form>

<?php
    if(isset($_POST["rate"])){

    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    $user = get_user_id();
    $product_id = $id;
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Ratings (product_id, user_id, rating, comment) VALUES(:product_id, :user_id, :rating, :comment)");
    $r = $stmt->execute([
        ":product_id"=>$product_id,
        ":user_id"=>$user,
        ":rating"=>$rating,
        ":comment"=>$comment
    ]);
    if($r){
        flash("Rating created successfully");
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error when creating rating: " . var_export($e, true));
    }
}

    require(__DIR__ . "/partials/flash.php");