<?php
//since API is 100% server, we won't include navbar or flash
require_once(__DIR__ . "/../lib/helpers.php");
if (!is_logged_in()) {
    die(header(':', true, 403));
}
if(isset($_POST["itemId"])){
    $itemId = (int)$_POST["itemId"];
    $db = getDB();
    $stmt = $db->prepare("SELECT name, price from Products where id = :id");
    $stmt->execute([":id"=>$itemId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result) {
        $name = $result["name"];
        $price = $result["price"];
        $quantity = 1;
        $stmt = $db->prepare("INSERT INTO Cart (user_id, product_id, price, quantity) VALUES(:user_id, :product_id, :price, :quantity) ON DUPLICATE KEY UPDATE quantity = quantity + 1, price = :price");
        $r = $stmt->execute([":user_id"=>get_user_id(), ":product_id"=>$itemId, ":price"=>$price, ":quantity"=>1]);
        if ($r) {
            $response = ["status" => 200, "message" => "Added $name to cart"];
            echo json_encode($response);
            die();
        }
        else{
            $response = ["status" => 400, "error" => "There was an error adding $name to cart"];
            echo json_encode($response);
            die();
        }
    }
    else{
        $response = ["status" => 404, "error" => "Item $itemId not found"];
        echo json_encode($response);
        die();
    }
}
else{
    $response = ["status" => 400, "error" => "An unexpected error occurred, please try again"];
    echo json_encode($response);
    die();
}
?>