<?php
require_once('../db/dbhelper.php');
?>
<!-- header -->
<?php
require_once("./layout/header.php");
?>
<!-- end header -->


<!-- Categories Section Begin -->
<section class="categories">
    <div class="container">
        <div class="section-title" style="display: flex; align-items: center; justify-content: center;">
            <h2>Category</h2>
        </div>
        <div class="row">
            <div class="categories__slider owl-carousel">
                <?php
                $sql = 'select CategoryId, CategoryCode, CategoryName, Image from category';
                $result = executeResult($sql);
                foreach ($result as $item) {
                    echo '<div class="col-lg-3">
                                    <div class="categories__item set-bg" data-setbg="../admin/category/uploads/' . $item['Image'] . '">
                                        <h5><a href="category.php?id=' . $item['CategoryId'] . '">' . $item['CategoryName'] . '</a></h5>
                                    </div>
                            </div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->

<!-- Featured Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Featured Product</h2>
                </div>
                <div class="featured__controls">
                    <ul>
                        <li class="active mixitup-control-active" data-filter="*">All</li>
                        <?php
                        foreach ($result as $item) {
                            echo '<li data-filter=".' . $item['CategoryCode'] . '">' . $item['CategoryName'] . '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <!-- List item -->
            <?php
            $sql = 'SELECT ProductId, UrlPicture, CategoryCode, Price, ProductName, product.CategoryId From product inner join category on product.CategoryId = category.CategoryId WHERE product.Quantity > 0 ORDER BY Price DESC LIMIT 16';
            $products = executeResult($sql);
            foreach ($products as $item) {
                echo '<div class="col-lg-3 col-md-4 col-sm-6 mix ' . $item['CategoryCode'] . '">
                <div class="featured__item">
                    <div class="featured__item__pic set-bg" data-setbg="../admin/product/uploads/' . $item['UrlPicture'] . '">
                        <ul class="featured__item__pic__hover">
                            <li><a href="productdetail.php?id=' . $item['ProductId'] . '"><i class="fa fa-retweet"></i></a></li>
                            <li><a href="add-one-item-cart.php?id=' . $item['ProductId'] . '"><i class="fa fa-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                    <div class="featured__item__text">
                        <h6><a href="productdetail.php?id=' . $item['ProductId'] . '">' . $item['ProductName'] . '</a></h6>
                        <h5>' . number_format($item['Price'], 0, ',', '.') . 'đ</h5>
                    </div>
                </div>
            </div>';
            }
            ?>


        </div>
    </div>
</section>
<!-- Featured Section End -->

<!-- Latest Product Section Begin -->
<section class="latest-product spad">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="latest-product__text">
                    <h4>Latest Products</h4>
                    <div class="latest-product__slider owl-carousel">

                        <div class="latest-prdouct__slider__item">
                            <?php
                            $sql = 'SELECT ProductId, ProductName, Price, UrlPicture FROM product WHERE product.Quantity > 0 ORDER BY CreatedDate DESC LIMIT 0,3';
                            $latestProducts = executeResult($sql);
                            foreach ($latestProducts as $item) {
                                echo '<a href="productdetail.php?id=' . $item['ProductId'] . '" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="../admin/product/uploads/' . $item['UrlPicture'] . '" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>' . $item['ProductName'] . '</h6>
                                        <span>' . number_format($item['Price'], 0, ',', '.') . 'đ</span>
                                    </div>
                                </a>';
                            }
                            ?>
                        </div>
                        <div class="latest-prdouct__slider__item">
                            <?php
                            $sql = 'SELECT ProductId, ProductName, Price, UrlPicture FROM product WHERE product.Quantity > 0 ORDER BY CreatedDate DESC LIMIT 3,3';
                            $latestProducts = executeResult($sql);
                            foreach ($latestProducts as $item) {
                                echo '<a href="productdetail.php?id=' . $item['ProductId'] . '" class="latest-product__item">
                                    <div class="latest-product__item__pic">
                                        <img src="../admin/product/uploads/' . $item['UrlPicture'] . '" alt="">
                                    </div>
                                    <div class="latest-product__item__text">
                                        <h6>' . $item['ProductName'] . '</h6>
                                        <span>' . number_format($item['Price'], 0, ',', '.') . 'đ</span>
                                    </div>
                                </a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="latest-product__text">
                    <h4>Top Selling Products</h4>
                    <div class="latest-product__slider owl-carousel">
                        <div class="latest-prdouct__slider__item">
                            <?php
                            $sql = 'SELECT ProductName, orderdetail.ProductId, product.Price, UrlPicture, COUNT(orderdetail.ProductId) as Total FROM orderdetail inner join product on orderdetail.ProductId = product.ProductId WHERE product.Quantity > 0 GROUP BY(orderdetail.ProductId) ORDER BY Total DESC LIMIT 0,3';
                            $latestProducts = executeResult($sql);
                            foreach ($latestProducts as $item) {
                                echo '<a href="productdetail.php?id=' . $item['ProductId'] . '" class="latest-product__item">
                                <div class="latest-product__item__pic">
                                    <img src="../admin/product/uploads/' . $item['UrlPicture'] . '" alt="">
                                </div>
                                <div class="latest-product__item__text">
                                    <h6>' . $item['ProductName'] . '</h6>
                                    <span>' . number_format($item['Price'], 0, ',', '.') . 'đ</span>
                                </div>
                            </a>';
                            }
                            ?>
                        </div>
                        <div class="latest-prdouct__slider__item">
                            <?php
                            $sql = 'SELECT ProductName, orderdetail.ProductId, product.Price, UrlPicture, COUNT(orderdetail.ProductId) as Total FROM orderdetail inner join product on orderdetail.ProductId = product.ProductId WHERE product.Quantity > 0 GROUP BY(orderdetail.ProductId) ORDER BY Total DESC LIMIT 3,3';
                            $latestProducts = executeResult($sql);
                            foreach ($latestProducts as $item) {
                                echo '<a href="productdetail.php?id=' . $item['ProductId'] . '" class="latest-product__item">
                                <div class="latest-product__item__pic">
                                    <img src="../admin/product/uploads/' . $item['UrlPicture'] . '" alt="">
                                </div>
                                <div class="latest-product__item__text">
                                    <h6>' . $item['ProductName'] . '</h6>
                                    <span>' . number_format($item['Price'], 0, ',', '.') . 'đ</span>
                                </div>
                            </a>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Product Section End -->

<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->