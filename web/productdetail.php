<?php
require_once('../db/dbhelper.php');
$ProductId = '';
if (isset($_GET["id"])) {
    $ProductId = $_GET["id"];
    $sql = 'select product.ProductId,  product.CategoryId CategoryId, product.ProductName, product.ProductCode, product.UrlPicture, product.Content,
        product.Unit, product.Quantity, product.Price, product.Description, product.CreatedDate, category.CategoryName category_name, Manufacturer, Origin 
        from product left join category on product.CategoryId = category.CategoryId where product.ProductId = ' . $ProductId;
    $result = executeSingleResult($sql);
    if ($result != null) {
        $ProductName = $result['ProductName'];
        $Price = $result['Price'];
        $Content = $result['Content'];
        $ProductCode = $result['ProductCode'];
        $UrlPicture = $result['UrlPicture'];
        $Unit = $result['Unit'];
        $Description = $result['Description'];
        $CreatedDate = $result['CreatedDate'];
        $Manufacturer = $result['Manufacturer'];
        $Origin = $result['Origin'];
        $CategoryId = $result['CategoryId'];
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
                    <h2>Product Detail</h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Details Section Begin -->
<section class="product-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product__details__pic">
                    <div class="product__details__pic__item">
                        <img class="product__details__pic__item--large" src="../admin/product/uploads/<?= $UrlPicture ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product__details__text">
                    <h3><?= $ProductName ?></h3>
                    <div class="product__details__price"><?= number_format($Price, 0, ',', '.') ?> đ</div>
                    <p><?= $Description ?></p>
                    <form method="POST" action="shop-cart.php?action=add">
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="text" value="1" name="quantity[<?= $ProductId ?>]">
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn primary-btn" value="ADD TO CARD">
                    </form>
                    <ul>
                        <li><b>Đơn vị</b> <span>In Stock</span></li>
                        <li><b>Shipping</b> <span>1-3 day shipping.</span></li>
                        <li><b>Xuất xứ</b> <span><?= $Origin ?></span></li>
                        <li><b>Thương hiệu</b> <span><?= $Manufacturer ?></span></li>
                        <li><b>Share on</b>
                            <div class="share">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-pinterest"></i></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab" aria-selected="false">Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab" aria-selected="false">Reviews</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Products Description</h6>
                                <p><?= $Description ?></p>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Products Infomation</h6>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?= $Content ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="product__details__tab__desc">
                                <h6>Products Review</h6>
                                <p>Chưa có đánh giá về sản phẩm</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Details Section End -->

<!-- Related Product Section Begin -->
<section class="related-product">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related__product__title">
                    <h2>Related Product</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $sql = 'SELECT ProductId, UrlPicture, ProductName, Price FROM product WHERE product.Quantity > 0 AND CategoryId = "' . $CategoryId . '" AND ProductId <> "' . $ProductId . '" ORDER BY RAND() LIMIT 4';
            $result = executeResult($sql);
            foreach ($result as $item) {
                echo '<div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="../admin/product/uploads/' . $item['UrlPicture'] . '">
                            <ul class="product__item__pic__hover">
                                <li><a href="productdetail.php?id=' . $item['ProductId'] . '"><i class="fa fa-retweet"></i></a></li>
                                <li><a href="add-one-item-cart.php?id=' . $item['ProductId'] . '"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6><a href="productdetail.php?id=' . $item['ProductId'] . '">' . $item['ProductName'] . '</a></h6>
                            <h5>' . number_format($item['Price'], 0, ',', '.') . '</h5>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</section>
<!-- Related Product Section End -->

<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->