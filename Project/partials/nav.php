<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
//require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
<ul class="nav">
    <li><a href="home.php">Home</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    <?php endif; ?>
    <?php if (is_logged_in()): ?>
        <li><a href="profile.php">Profile</a></li>
        <li style="float:right"><a class="active" href="logout.php">Logout</a></li>
    <?php endif; ?>
    <?php if (has_role("Admin")): ?>
	<div class="dropdown">
	<button style="float:right" class="dropbtn">Products<i class="fa fa-caret-down"></i></button>
	<div class="dropdown-content">
	<a class="active" href="Test/test_create_product.php")>Create Product</a>
	<a class="active" href="Test/test_list_product.php")>List Product</a>
	</div>
	</div>
    <?php endif; ?>

</ul>
</nav>
