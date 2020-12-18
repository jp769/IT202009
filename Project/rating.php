<?php require_once(__DIR__ . "/partials/nav.php");

if (is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must log in to access this page");
    die(header("Location: login.php"));
}

?>

</div>
<?php include(__DIR__."/partials/pagination.php");?>
</div>

<?php require(__DIR__ . "/partials/flash.php");