<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    die(header("Location: login.php"));
}

$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
    $email = $_SESSION["user"]["email"];
}
?>
	<p>Welcome To ShopeName TBH,  <?php echo $email; ?></p>
<?php require(__DIR__ . "/partials/flash.php");
