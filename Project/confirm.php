<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
if(isset($_GET["id"])){
    $o_id = $_GET["id"];
    $u = get_user_id();
    safer_echo($o_id);
    
}

$result = [];

if(isset($id)){
    $o_id = $_GET["id"];
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM OrderItems where order_id = :order_id");
    $r = $stmt->execute([":0_id"=>$o_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        $e = $stmt->errorInfo();
        flash($e[2]);
    }
}
?>



<?php require(__DIR__ . "/partials/flash.php");
