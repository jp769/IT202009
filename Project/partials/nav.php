<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href=<?php echo getURL("static/css/styles.css");?>>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>

<ul class="navbar navbar-expand-md navbar-light" style="background-color: #087ead;">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item"><a class="nav-link" href=<?php echo getURL("home.php");?>>Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("login.php");?>>Login</a></li>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("register.php");?>>Register</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href=<?php echo getURL("product_list.php");?>>Products</a></li>
        <?php if (is_logged_in()): ?>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("profile.php");?>>Profile</a></li>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("cart.php");?>>Cart</a></li>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("purchase_history.php");?>>Purchase History</a></li>
        <?php endif; ?>
    </ul>
    <?php if (is_logged_in()): ?>
    <ul class="navbar-nav ml-auto">

        <li class="nav-item" ><a class="nav-link" href=<?php echo getURL("logout.php");?>>Logout</a></li>

        <?php if (has_role("Admin")): ?>
            <li class="nav-item"><a class="nav-link" href=<?php echo getURL("create_product.php");?>>Create Product</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Test Files
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href=<?php echo getURL("Test/test_create_product.php");?>>Create Product</a>
                    <a class="dropdown-item" href=<?php echo getURL("Test/test_list_product.php");?>>List Product</a>
                    <a class="dropdown-item" href=<?php echo getURL("Test/test_create_cart.php");?>>Create Cart</a>
                    <a class="dropdown-item" href=<?php echo getURL("Test/test_list_cart.php");?>>List Cart</a>
                </div>
            </li>
    </ul>
    <?php endif; ?>
    <?php endif; ?>
</ul>
</nav>
