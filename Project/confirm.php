<?php
if(isset($_GET["o_id"])){
$o_id = $_GET["o_id"];
$u = get_user_id();
echo $o_id;
echo $u;
}
?>

<div>
    <form>
        <label for="test">Testing</label>
        <p><?php echo $o_id;?></p>
        <p><?php echo $u;?></p>
    </form>
</div>
