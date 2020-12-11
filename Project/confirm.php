<?php
if(isset($_GET["total_price"])){
$total_price = $_GET["total_price"];
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

<div>
    <form>
        <label for="test">Testing</label>
        <input type="text" id="test" name="test" placeholder="Test">
    </form>
</div>
