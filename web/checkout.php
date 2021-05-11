<?php
session_start();
require_once("../db/dbhelper.php");
$totals = 0;
$shipping = 30000;
//Lấy thông tin từ session giỏ hàng
if (!empty($_SESSION['cart'])) {
    $listKey = implode(",", array_keys($_SESSION['cart']));
    $sql = "select * from product where ProductId IN(" . $listKey . ")";
    $result = executeResult($sql);
    foreach ($result as $item) {
        $totals += $_SESSION['cart'][$item['ProductId']] * $item['Price'];
    }
}

$user = [];
$FullName = $Address = $Email = $PhoneNumber = $CreatedDate = $CreatedBy = $Total = $OnlinePayment = '';
$Quantity = 0;
//Lấy thông tin từ session user
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $FullName = $user['FullName'];
    $Address = $user['Address'];
    $Email = $user['Email'];
    $PhoneNumber = $user['PhoneNumber'];
}

//Lấy dữ liệu từ form 
if (!empty($_POST)) {
    if (isset($_POST['FullName'])) {
        $FullName = $_POST['FullName'];
        $FullName = str_replace('"', '\\"', $FullName);
    }
    if (isset($_POST['Email'])) {
        $Email = $_POST['Email'];
        $Email = str_replace('"', '\\"', $Email);
    }
    if (isset($_POST['Address'])) {
        $Address = $_POST['Address'];
        $Address = str_replace('"', '\\"', $Address);
    }
    if (isset($_POST['PhoneNumber'])) {
        $PhoneNumber = $_POST['PhoneNumber'];
        $PhoneNumber = str_replace('"', '\\"', $PhoneNumber);
    }
    if (isset($_POST['Description'])) {
        $Description = $_POST['Description'];
        $Description = str_replace('"', '\\"', $Description);
    }
    if (isset($_POST['OnlinePayment'])) {
        $OnlinePayment = $_POST['OnlinePayment'];
    }

    //submit dữ liệu vào db
    if (isset($_POST['submit'])) {
        $CreatedDate = date('Y-m-d H:s:i');
        $CreatedBy = $user['UserName'];
        foreach ($result as $item) {
            $Quantity += $_SESSION['cart'][$item['ProductId']];
        }
        $Total = $totals + $shipping;

        $sql = 'insert into orderproduct(FullName, PhoneNumber, Email, Address, Quantity, Total, OnlinePayment, Description, CreatedDate, CreatedBy, Paid, Checked)
        values("' . $FullName . '","' . $PhoneNumber . '","' . $Email . '","' . $Address . '","' . $Quantity . '","' . $Total . '","' . $OnlinePayment . '","' . $Description . '","' . $CreatedDate . '","' . $CreatedBy . '", "0", "0")';
        $check = execute($sql);
        if ($check) {
            echo "<script type='text/javascript'>alert('Đặt hàng thành công');</script>";
            $OrderId = getInsertedId();
            if ($result != null) {
                $QuantityDetail = $Price = $ProductId = 0;
                $valuesInsert = '';
                foreach ($result as $key => $item) {
                    $QuantityDetail = $_SESSION['cart'][$item['ProductId']];
                    $Price = $item['Price'];
                    $ProductId = $item['ProductId'];
                    $valuesInsert .= '("' . $OrderId . '","' . $ProductId . '","' . $QuantityDetail . '","' . $Price . '")';
                    if(count($result) -1 != $key){
                        $valuesInsert .= ',';
                    }
                }
                $sql = 'insert into orderdetail(OrderId, ProductId, Quantity, Price) values '.$valuesInsert;
                execute($sql);
            }
        } else {
            echo "<script type='text/javascript'>alert('Đặt hàng thất bại');</script>";
        }
    }
}
?>
<!-- header -->
<?php
require_once("./layout/header.php");
?>
<!-- end header -->

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="./template/img/banner/banner.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Checkout</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->
<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Billing Details</h4>
            <form action="" method="POST">
                <div class="row">
                    <div class="col-lg-8 col-md-6">

                        <div class="checkout__input">
                            <p>Full Name<span>*</span></p>
                            <input type="text" required="true" name="FullName" value="<?= $FullName ?>" placeholder="Street Address">
                        </div>
                        <div class="checkout__input">
                            <p>Phone Number<span>*</span></p>
                            <input type="text" required="true" name="PhoneNumber" value="<?= $PhoneNumber ?>" placeholder="Street Address">
                        </div>

                        <div class="checkout__input">
                            <p>Address<span>*</span></p>
                            <input type="text" placeholder="Street Address" name="Address" value="<?= $Address ?>" class="checkout__input__add" required="true">
                        </div>
                        <div class="checkout__input">
                            <p>Email<span>*</span></p>
                            <input type="email" placeholder="Email" name="Email" value="<?= $Email ?>" class="checkout__input__add" required="true">
                        </div>
                        <div class="checkout__input">
                            <p>Note</p>
                            <textarea name="Description" rows="4" cols="103"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Your Order</h4>
                            <div class="checkout__order__products">Products <span>Sub Total</span></div>
                            <ul>
                                <?php
                                foreach ($result as $item) {
                                    echo '<li>' . $item['ProductName'] . ' (<b>'.$_SESSION['cart'][$item['ProductId']].'</b>)<span>' . number_format($_SESSION['cart'][$item['ProductId']] * $item['Price'], 0, ',', '.') . 'đ</span></li>';
                                }
                                ?>
                            </ul>
                            <div class="checkout__order__subtotal">Shipping <span><?= number_format($shipping, 0, ',', '.') ?>đ</span></div>
                            <div class="checkout__order__total">Total <span><?= number_format($shipping + $totals, 0, ',', '.') ?>đ</span></div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="OnlinePayment" value="1" id="flexRadioDefault1" checked>
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Online Payment
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="OnlinePayment" value="0" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Payment on delivery
                                </label>
                            </div>

                            <button type="submit" name="submit" class="site-btn">PLACE ORDER</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Checkout Section End -->
<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->