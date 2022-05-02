<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "testdatabase");

if (isset($_POST["add_to_cart"])) {
    if (isset($_SESSION["shopping_cart"])) {
        $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id' => $_GET["id"],
                'item_name' => $_POST["hidden_name"],
                'item_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"]
            );
            $_SESSION["shopping_cart"][$count] = $item_array; //counting stuff in cart
            
        } else {//INCREASE QUANTITY OF THINGS
            foreach ($_SESSION["shopping_cart"] as $keys => $values) { //variable called session is an array
                if ($values["item_id"] == $_GET["id"]) {
                    $_SESSION["shopping_cart"][$keys]['item_quantity'] = $_SESSION["shopping_cart"][$keys]['item_quantity'] + 1; //increasing quantity if item is already there 
                }//"Shopping_cartth position in the array. 
                //foreach rips out row and checks item IDth position of that row and see if it matches id of item added. 
            }
        }
    } else {
        $item_array = array(
            'item_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'item_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"]
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}

if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
            if ($values["item_id"] == $_GET["id"]) {
                unset($_SESSION["shopping_cart"][$keys]);
                echo '<script>window.location="products.php"</script>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Veranio</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <style>
            body{
                font-family: OCR A Std, monospace;
                background: #CFC598;
                box-shadow:
                    #000000 4px 4px 8px,
                    #4d4d4d -4px -4px 8px;
               
            }
            .table-responsive{

                color: black;
                background-color: white;
                border-radius: 3%;
                margin-bottom: 50px;
                box-shadow:
                    #000000 4px 4px 8px,
                    #4d4d4d -4px -4px 8px;
                font-size: 20px;
            }

            .col-md-4{
                margin-bottom: 30px;
                font-size:40px;

            }
            .img-responsive{
                  border-radius: 10%;
                
                
                
                
            }
            
            .tab{
                background-color: #CFC598;
                
                border-radius: 10%;
                font-family: OCR A Std, monospace;
                margin-left: 200px;
                font-size: 30px;
            }
            .tab button{
                 border-radius: 8%;
                background-color: #CFC598;
                color: black;
                margin-right: 60px;
                margin-left: 60px;
                margin-bottom: 30px;
                margin-top: 20px;    
                border: none;
                
                
                
            }
            .tab button:hover{
                background: #A49B75;
                
                
        
         
            
        </style>
        <br />
        <div class="container">
            <br />
            <br />
            <br />
            <strong><h3 style="
                       font-size: 40px;
             background-color: #FAEAA4;
             color: white;
             text-shadow: 3px 2px #000000;
                        " 
                        align="center" >Veranio Store </h3><br />
                <br /><br /></strong>
            
            <!---- tabs work ---->
            <div class ="tab">
                <button class ="tablinks" onclick="window.location.href = 'https://veranio.store';">Shopify</button>
                
                 <button class ="tablinks" onclick="window.location.href = 'register.php';"> Register</button>
                
                
                <button class ="tablinks" onclick="window.location.href = 'aboutUs.php';">About Us</button>
                
               
                
            </div>
            
<?php
$query = "SELECT * FROM products ORDER BY id ASC";
$result = mysqli_query($connect, $query);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        ?>
                    <div class="col-md-4">
                        <form method="post" action="products.php?action=add&id=<?php echo $row["id"]; ?>">
                            <div style="font-size:35px; border:3px solid black; background-color:whitesmoke; border-radius: 10%; padding:16px;" align="center">
                                <img src="<?php echo $row['img']; ?>" class="img-responsive" /><br />

                                <h4 style = "font-size: 20px;
                                    font-weight: bold;
                                    color: black;
                                    "class="text-info"><?php echo $row["name"]; ?></h4>

                                <h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>

                                <input type="text" name="quantity" value="1" class="form-control" />

                                <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />

                                <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />

                                <input type="submit" name="add_to_cart" 
                                       style=
                                       "margin-top:5px;
                                       border-radius: 10%;
                                       color: blue;
                                       border-color: black;
                                       font-size: 25px;
                                       background-color: #CFC598;
                                       color: black;
                                       margin-top: 20px;
                                                                             
                                       " class="btn btn-success" value="Add to Cart" 
                                       />

                            </div>
                        </form>
                    </div>
        <?php
    }
}
?>
            <div style="clear:both"></div>
            <br />
            <h3 style="color: white;
                text-align: center;
                font-size:35px; ">Order Details</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Item:</th>
                        <th width="10%">Quantity:</th>
                        <th width="20%">Price:</th>
                        <th width="15%">Total:</th>
                        <th width="5%">Delete:</th>
                    </tr>
                    
<?php
if (!empty($_SESSION["shopping_cart"])) {
    $total = 0;
    foreach ($_SESSION["shopping_cart"] as $keys => $values) {
        ?>
                            <tr>
                                <td><?php echo $values["item_name"]; ?></td>
                                <td><?php echo $values["item_quantity"]; ?></td>
                                <td>$ <?php echo $values["item_price"]; ?></td>
                                <td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                                <td><a href="products.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
                            </tr>
        <?php
        $total = $total + ($values["item_quantity"] * $values["item_price"]);
    }
    ?>
                        <tr>
                            <td colspan="3" align="right">Total</td>
                            <td align="right">$ <?php echo number_format($total, 2); ?></td>
                            <td></td>
                        </tr>
    <?php
}
?>

                </table>
                <button style = "border-radius: 8%;
                background-color: #CFC598;
                color: black;
                margin-right: 60px;
                margin-left: 60px;
                margin-bottom: 30px;
                margin-top: 20px;    
                border: none;"onclick="window.location.href = 'cart.php'" type="button">Review Order!</button>
            </div>
        </div>
    </div>
    <br />
</body>
</html>
