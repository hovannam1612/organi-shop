<?php
session_start();

//Khởi tạo Session giỏ hàng nếu chưa có giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
//lấy id từ product từ query string
$id = '';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    function update_cart($add = false, $id)
    {
        if ($add) {
            $_SESSION['cart'][$id] += 1;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    }
    update_cart(true, $id);
    header('Location: ./shop-cart.php');
}
