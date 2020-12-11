<?php

require_once(__DIR__ . "/../lib/helpers.php");
if (!is_logged_in()) {
    die(header(':', true, 403));
}

if(isset($_POST["userID"])){
    if(isset($_POST["total"]))
        $total = (int)$_POST["total"];

    $userID = (int)$_POST["userID"];
    $db = getDB();
    //
    $stmt = $db->prepare("INSERT INTO Orders (user_id, total_price, address, payment_method) VALUES (:user_id, :total_price, :address, :payment_method)");
    $r = $stmt->execute([":user_id"=>get_user_id(), ":total_price"=>$total, ":address",":payment_method"]);
    //
    $stmt = $db->prepare("SELECT * from Cart where user_id = :user_id");
    $stmt->execute([":user_id"=>$userID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result) {
        $name = $result["name"];
        $price = $result["price"];
        $quantity = 1;
        $stmt = $db->prepare("INSERT INTO Cart (user_id, product_id, price, quantity) VALUES(:user_id, :product_id, :price, :quantity) ON DUPLICATE KEY UPDATE quantity = quantity + 1, price = :price");
        $r = $stmt->execute([":user_id"=>get_user_id(), ":total_price"=>$total, ":quantity"=>$quantity]);
        if ($r) {
            $response = ["status" => 200, "message" => "Purchased cart"];
            echo json_encode($response);
            die(header("Location: purchase_history.php"));
        }
        else{
            $response = ["status" => 400, "error" => "There was an error putting in your order"];
            echo json_encode($response);
            die(header("Location: cart.php"));
        }
    }
    else{
        $response = ["status" => 404, "error" => "Your cart was not found"];
        echo json_encode($response);
        die(header("Location: home.php"));
    }
}

else{
    $response = ["status" => 400, "error" => "An unexpected error occurred, please try again"];
    echo json_encode($response);
    die(header("Location: cart.php"));
}

?>
