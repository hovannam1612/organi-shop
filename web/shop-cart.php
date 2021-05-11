<?php
session_start();
require_once("../db/dbhelper.php");

//Session giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_GET['action'])) {
    function update_cart($add = false)
    {
        foreach ($_POST['quantity'] as $id => $quantity) {
            if ($add) {
                $_SESSION['cart'][$id] += $quantity;
            } else {
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    switch ($_GET['action']) {
        case 'add':
            if (isset($_POST['quantity'])) {
                update_cart(true);
            }
            header('Location: ./shop-cart.php');
            break;
        case 'delete':
            //delete item of cart
            $id = '';
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                unset($_SESSION['cart'][$id]);
                header('Location: ./shop-cart.php');
            }
            break;
        case 'submit':
            if (isset($_POST['quantity'])) {
                update_cart();
            }
            header('Location: ./shop-cart.php');
            break;
    }
}
$result = null;
if (!empty($_SESSION['cart'])) {
    $listKey = implode(",", array_keys($_SESSION['cart']));
    $sql = "select * from product where ProductId IN(" . $listKey . ")";
    $result = executeResult($sql);
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
                    <h2>Shopping Cart</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad">
    <div class="container">
        <form method="POST" id="myform" action="shop-cart.php?action=submit">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 100px;">No</th>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $index = 1;
                                $total = 0;
                                if ($result != null) {
                                    foreach ($result as $itechỏm) {
                                        echo '<tr>
                                        <td style="font-weight: bold;">' . $index++ . '</td>
                                        <td class="shoping__cart__item">
                                            <a href="productdetail.php?id='.$item['ProductId'].'"><img src="../admin/product/uploads/' . $item['UrlPicture'] . '" alt="' . $item['ProductName'] . '"></a>
                                            <a href="productdetail.php?id='.$item['ProductId'].'"><h5>' . $item['ProductName'] . '</h5></a>
                                        </td>
                                        <td class="shoping__cart__price">
                                            ' .number_format($item['Price'], 0, ',', '.') . 'đ
                                        </td>
                                        <td class="shoping__cart__quantity">
                                            <div class="quantity">
                                                <div class="pro-qty">
                                                    <input type="text" name="quantity[' . $item['ProductId'] . ']" value="' . $_SESSION['cart'][$item['ProductId']] . '">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="shoping__cart__total">
                                            ' .number_format($_SESSION['cart'][$item['ProductId']] * $item['Price'], 0, ',', '.')  . 'đ
                                        </td>
                                        <td class="shoping__cart__item__close">
                                            <a href="shop-cart.php?action=delete&id=' . $item['ProductId'] . '" onclick="return confirm(`Bạn có muốn xóa sản phẩm ra khỏi giỏ hàng?`);"><span class="icon_close"></span></a>
                                        </td>
                                    </tr>';
                                        $total += $_SESSION['cart'][$item['ProductId']] * $item['Price'];
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="index.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                        <a href="#" class="primary-btn cart-btn cart-btn-right" onclick="document.getElementById('myform').submit()"><span class="icon_loading"></span>
                            Upadate Cart</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Total <span><?= number_format($total, 0, ',', '.')?>đ</span></li>
                        </ul>

                        <?php
                        if (!empty($_SESSION['cart'])) {
                            if (!empty($_SESSION['user'])) {
                                echo '<a href="checkout.php" class="primary-btn" onclick="checkLogin()">CHECKOUT</a>';
                            } else {
                                echo '<a href="../login/" class="primary-btn" onclick="checkLogin()">CHECKOUT</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<!-- Shoping Cart Section End -->

<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->