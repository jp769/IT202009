<?php
$isValid = false;
if (isset($_POST["address"])) {
if (isset($_POST["adr"]) and isset($_POST["city"]) and isset($_POST["state"]) and isset($_POST["zip"]) and isset($_POST["payMethod"])) {
$isValid = true;
$address = $_POST["adr"] . " " . $_POST["city"] . "," . $_POST["state"] . " " . $_POST["zip"];
$payment = $_POST["payMethod"];
} else {
flash("Unable to add address, try again");
}
}
?>

<script>

    function purchaseCart(userID, total, address, payment){

        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let json = JSON.parse(this.responseText);
                if (json) {
                    if (json.status == 200) {
                        alert(json.message);
                    } else {
                        alert(json.error);
                    }
                }
            }
        };
        xhttp.open("POST", "<?php echo getURL("api/purchase_cart.php");?>", true);
        //this is required for post ajax calls to submit it as a form
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //map any key/value data similar to query params
        xhttp.send("userID="+userID +"&"+ "total="+total + "&"+ "address="+address + "&" + "payment="+payment);
    }
</script>


<br><br>
<h5>Enter Shipping Address</h5>
<div style="align-content: space-evenly">
    <form id="address" method="post">
        <label for="adr"><i class="fa fa-address-card-o"></i> Address</label>
        <input type="text" id="adr" name="address" placeholder="123 Example Street" required>
        <label for="city"><i class="fa fa-institution"></i> City</label>
        <input type="text" id="city" name="city" placeholder="New York" required>

        <label for="state">State</label>
        <input type="text" id="state" name="state" placeholder="NY" maxlength="2" required>
        <label for="zip">Zip</label>
        <input type="number" id="zip" name="zip" placeholder="10001" maxlength="5" required>
        <br>
        <label for="payMethod">Payment Method</label>
        <input type="text" id="payMethod" name="payMethod" placeholder="Cash" required>
        <input type="submit" class="btn btn-success" name="address" value="Enter Address">
    </form>
</div>

<button type="button" onclick="purchaseCart(<?php echo $r["user_id"];?>,<?php echo $total;?>, <?php safer_echo($address);?>, <?php safer_echo($payment);?>);" class="btn btn-primary btn-lg">Checkout</button>
