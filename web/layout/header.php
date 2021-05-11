<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../db/dbhelper.php");

//get session user
$user = $profile = [];
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
if($user != null){
    $sql = 'select * from useraccount where UserId = "'.$user['UserId'].'"';
    $profile = executeSingleResult($sql);
}

$quantityItem = 0;
$totals = 0;
if (isset($_SESSION['cart'])) {
    $quantityItem = count($_SESSION['cart']);
    if (!empty($_SESSION['cart'])) {
        $listKey = implode(",", array_keys($_SESSION['cart']));
        $sql = "select * from product where ProductId IN(" . $listKey . ")";
        $result = executeResult($sql);
        foreach ($result as $item) {
            $totals += $_SESSION['cart'][$item['ProductId']] * $item['Price'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Phụ kiện mobile - Ogani</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="./template/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./template/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./template/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./template/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./template/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./template/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./template/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./template/css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder
    <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-shopping-bag"></i>HomePage</li>
                                <li>Free Shipping for all Order of $99</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <!-- Example single danger button -->
                            <div class="lt-dropdown">
                                <?php
                                if ($profile != null) {
                                    echo '<a href="#" class="lt-dropbtn">
                                        <i class="fa fa-user"></i> ' . $profile['FullName'] . '
                                        </a>
                                        <div class="lt-dropdown-content">
                                            <a href="profile.php"><i class="fa fa-user"></i> Profile</a>
                                            <a href="change-password.php"><i class="fa fa-key" aria-hidden="true"></i> Change Password</a>
                                            <a href="../login/log-out.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Log-out</a>
                                        </div>';
                                } else {
                                    echo '<a href="../login/index.php" class="lt-dropbtn">
                                        <i class="fa fa-user"></i> Login
                                        </a>';
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="../index.php"><img src="./template/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="active"><a href="../index.php">Home</a></li>
                            <?php
                                if($user != null){
                                    echo '<li><a href="profile.php">Profile</a></li>';
                                }
                            ?>
                            <li><a href="about-us.php">About Us</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="../web/shop-cart.php"><i class="fa fa-shopping-bag"></i> <span><?= $quantityItem ?></span></a></li>
                        </ul>
                        <div class="header__cart__price">total: <span><?= number_format($totals, 0, ',', '.')?>đ</span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>All Category</span>
                        </div>
                        <ul>
                            <?php
                            $sql = "select * from category";
                            $productList = executeResult($sql);
                            foreach ($productList as $row) {
                                echo '<li><a href="category.php?id=' . $row["CategoryId"] . '">' . $row['CategoryName'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="#">
                                <input type="text" placeholder="What do yo u need?">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__text">
                                <h5>HotLine: 0988.888.789</h5>
                                <span>support 24/7 time</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->