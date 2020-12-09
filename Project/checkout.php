<?php require_once(__DIR__ . "/partials/nav.php");

if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
if(isset($_GET["user_id"])){
$id = $_GET["user_id"];
}
?>

<div class="card">
    <div class="card-title"></div>
        <form method="POST">
            <div class="card-body">
                <div>

                </div>
            </div>
        </form>
    </div>
</div>