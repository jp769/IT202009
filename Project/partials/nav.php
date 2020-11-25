<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<link rel="stylesheet" href=<?php echo getURL("static/css/styles.css");?>>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
<nav>
    <ul class="nav">
        <li><a href=<?php echo getURL("home.php");?>>Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a href=<?php echo getURL("login.php");?>>Login</a></li>
            <li><a href=<?php echo getURL("register.php");?>>Register</a></li>
        <?php endif; ?>
        <li><a href=<?php echo getURL("product_list.php");?>>Products</a></li>
        <?php if (is_logged_in()): ?>
            <li><a href=<?php echo getURL("profile.php");?>>Profile</a></li>
            <li><a href=<?php echo getURL("cart.php");?>>Cart</a></li>
            <li style="float:right"><a class="active" href=<?php echo getURL("logout.php");?>>Logout</a></li>
        <?php endif; ?>
        <?php if (has_role("Admin")): ?>
            <li style="float:right"><a href=<?php echo getURL("create_product.php");?>>Create Product</a></li>
            <div class="dropdown">
                <button style="float:right" class="dropbtn">/Test Files<i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a class="active" href=<?php echo getURL("Test/test_create_product.php");?>>Create Product</a>
                    <a class="active" href=<?php echo getURL("Test/test_list_product.php");?>>List Product</a>
                    <a class="active" href=<?php echo getURL("Test/test_create_cart.php");?>>Create Cart</a>
                    <a class="active" href=<?php echo getURL("Test/test_list_cart.php");?>>List Cart</a>
                </div>
            </div>
            <!--    <div>-->
            <!--        <button style="float:right" class="dropbtn">ADMIN<i class="fa fa-caret-down"></i></button>-->
            <!--        <div class="dropdown-content">-->
            <!--            <a class="active" href="create_product.php")>Create Product</a>-->
            <!--        </div>-->
            <!--	</div>-->
        <?php endif; ?>

    </ul>
</nav>
