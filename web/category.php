<?php
require_once('../db/dbhelper.php');
require_once('../common/utility.php');
$CategoryId = $CategoryName = '';
if (isset($_GET["id"])) {
    $CategoryId = $_GET["id"];
    $sql = 'select * from category where CategoryId = ' . $CategoryId;
    $result = executeSingleResult($sql);
    if ($result != null) {
        $CategoryName = $result['CategoryName'];
    }
}
//Tìm kiếm theo các tiêu chí (0: sản phẩm mới nhất, 1: Giá)
$sort = '';
if (isset($_GET['sort-by'])) {
    $sort = $_GET['sort-by'];
    if ($sort == -1) {
        $sort = "";
    }
}
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
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
                    <h2><?= $CategoryName ?></h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-5">
                <div class="sidebar">
                    <div class="sidebar__item">
                        <h4>Category</h4>
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
                    <div class="sidebar__item">
                        <h4>Price</h4>
                        <div class="price-range-wrap">
                            <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content" data-min="10" data-max="540">
                                <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            </div>
                            <div class="range-slider">
                                <div class="price-input">
                                    <input type="text" id="minamount">
                                    <input type="text" id="maxamount">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar__item sidebar__item__color--option">
                        <h4>Colors</h4>
                        <div class="sidebar__item__color sidebar__item__color--white">
                            <label for="white">
                                White
                                <input type="radio" id="white">
                            </label>
                        </div>
                        <div class="sidebar__item__color sidebar__item__color--gray">
                            <label for="gray">
                                Gray
                                <input type="radio" id="gray">
                            </label>
                        </div>
                        <div class="sidebar__item__color sidebar__item__color--red">
                            <label for="red">
                                Red
                                <input type="radio" id="red">
                            </label>
                        </div>
                        <div class="sidebar__item__color sidebar__item__color--black">
                            <label for="black">
                                Black
                                <input type="radio" id="black">
                            </label>
                        </div>
                        <div class="sidebar__item__color sidebar__item__color--blue">
                            <label for="blue">
                                Blue
                                <input type="radio" id="blue">
                            </label>
                        </div>
                        <div class="sidebar__item__color sidebar__item__color--green">
                            <label for="green">
                                Green
                                <input type="radio" id="green">
                            </label>
                        </div>
                    </div>
                    <div class="sidebar__item">
                        <h4>Popular Size</h4>
                        <div class="sidebar__item__size">
                            <label for="large">
                                Large
                                <input type="radio" id="large">
                            </label>
                        </div>
                        <div class="sidebar__item__size">
                            <label for="medium">
                                Medium
                                <input type="radio" id="medium">
                            </label>
                        </div>
                        <div class="sidebar__item__size">
                            <label for="small">
                                Small
                                <input type="radio" id="small">
                            </label>
                        </div>
                        <div class="sidebar__item__size">
                            <label for="tiny">
                                Tiny
                                <input type="radio" id="tiny">
                            </label>
                        </div>
                    </div>
                    <div class="sidebar__item">
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
                </div>
            </div>
            <div class="col-lg-9 col-md-7">
                <div class="section-title product__discount__title">
                    <h2>Product</h2>
                </div>
                <div class="filter__item">

                    <form method="get">
                        <div class="row">
                            <input type="hidden" name="id" value="<?= $CategoryId ?>">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sort By</span>
                                    <select name="sort-by" onchange="this.form.submit()">
                                        <?php
                                            if($sort == 0 && $sort != null){
                                                echo '<option value="-1">--Lựa chọn--</option>
                                                    <option value="0" selected>Mới nhất</option>
                                                    <option value="1">Giá</option>';
                                            }else if($sort == 1 && !empty($sort)){
                                                echo '<option value="-1">--Lựa chọn--</option>
                                                <option value="0">Mới nhất</option>
                                                <option value="1" selected>Giá</option>';
                                            }else{
                                                echo '<option value="-1">--Lựa chọn--</option>
                                                <option value="0">Mới nhất</option>
                                                <option value="1">Giá</option>';
                                            }
                                        ?>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <input style="width: 560px" value="<?=$search?>" placeholder="Tìm kiếm theo tên..." type="text" onchange="this.form.submit()" name="search">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <?php
                    
                    $additional = '';
                    if(!empty($search)){
                        $additional .= ' and product.ProductName like "%' . $search . '%"';
                    }
                
                    if ($sort == '0') {
                        $additional .= ' order by product.CreatedDate DESC';
                    }else if (!empty($sort) && $sort == '1') {
                        $additional .= ' order by product.Price DESC';
                    } 
                    //số sản phẩm hiện thị trên 1 trang
                    $limit = 9;

                    //trang hiện thị
                    $page = 1;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }
                    if ($page <= 0) {
                        $page = 1;
                    }
                    //vị trí đầu tiên của trang cần hiện thị
                    $firstIndex = ($page - 1) * $limit;

                    //Lấy tổng số lượng sản phẩm
                    $sql = 'select count(ProductId) total from product left join category on product.CategoryId = category.CategoryId where product.CategoryId = ' . $CategoryId;
                    $result = executeSingleResult($sql);
                    $count = $result['total'];

                    //Số lượng trang hiện thị
                    $number = ceil($count / $limit);

                    $sql = 'select product.ProductId, product.ProductName, product.ProductCode, product.UrlPicture, product.Content,
                            product.Unit, product.Quantity, product.Price, product.Description, product.CreatedDate, category.CategoryName category_name 
                            from product left join category on product.CategoryId = category.CategoryId where product.Quantity > 0 and category.CategoryId = ' . $CategoryId . '' . $additional . ' limit ' . $firstIndex . ', ' . $limit;
                   
                            $productList = executeResult($sql);
                    if ($productList == null) {
                        echo '<p>Không có sản phẩm nào</p>';
                    }
                    foreach ($productList as $row) {
                        echo '<div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="product__item">
                                                <div class="product__item__pic set-bg" style="background-size: 300px;" data-setbg="../admin/product/uploads/' . $row['UrlPicture'] . '">
                                                    <ul class="product__item__pic__hover">
                                                        <li><a href="productdetail.php?id=' . $row['ProductId'] . '"><i class="fa fa-retweet"></i></a></li>
                                                        <li><a href="add-one-item-cart.php?id=' . $row['ProductId'] . '"><i class="fa fa-shopping-cart"></i></a></li>
                                                    </ul>
                                                </div>
                                                <a href="productdetail.php?id=' . $row['ProductId'] . '">
                                                    <div class="product__item__text">
                                                        <h6>' . $row['ProductName'] . '</h6>
                                                        <h5>' . number_format($row['Price'], 0, ',', '.') . 'đ</h5>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>';
                    }
                    ?>
                </div>
                <!-- Phân trang -->
                <?php
                if ($number > 1) {
                    echo '<ul class="pagination" style="display: flex; justify-content: center;">';
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($page - 1) . '"><<</a></li>';
                    }
                    $avaiablePage = [1, $page - 1, $page, $page + 1, $number];
                    $isFirst = $isLast = false;
                    for ($i = 0; $i < $number; $i++) {
                        if (!in_array($i + 1, $avaiablePage)) {
                            if (!$isFirst && $page > 3) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($page - 2) . '">...</a></li>';
                                $isFirst = true;
                            }
                            if (!$isLast && $i > $page) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($page + 2) . '">...</a></li>';
                                $isLast = true;
                            }
                            continue;
                        }
                        if ($i == $page - 1) {
                            echo '<li class="page-item active"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
                        }
                    }
                    if ($page < $number) {
                        echo '<li class="page-item"><a class="page-link" href="?id=' . $CategoryId . '&page=' . ($page + 1) . '">>></a></li>';
                    }
                    echo '</ul>';
                }
                ?>
                <div class="product__discount">
                    <div class="section-title product__discount__title">
                        <h2>Top Selling</h2>
                    </div>
                    <div class="row">
                        <div class="product__discount__slider owl-carousel">
                            <?php
                            $sql = 'SELECT ProductName, orderdetail.ProductId, product.Price, UrlPicture, COUNT(orderdetail.ProductId) as Total FROM orderdetail inner join product on orderdetail.ProductId = product.ProductId WHERE product.Quantity > 0 AND product.CategoryId = "' . $CategoryId . '" GROUP BY(orderdetail.ProductId) ORDER BY Total DESC LIMIT 0,3';
                            $topSelling = executeResult($sql);
                            foreach ($topSelling as $item) {
                                echo '<div class="col-lg-4">
                                        <div class="product__discount__item">
                                            <div class="product__discount__item__pic set-bg" data-setbg="../admin/product/uploads/' . $item['UrlPicture'] . '">
                                                <div class="product__discount__percent">HOT</div>
                                                <ul class="product__item__pic__hover">
                                                    <li><a href="productdetail.php?id=' . $item['ProductId'] . '"><i class="fa fa-retweet"></i></a></li>
                                                    <li><a href="add-one-item-cart.php?id=' . $item['ProductId'] . '"><i class="fa fa-shopping-cart"></i></a></li>
                                                </ul>
                                            </div>
                                            <div class="product__discount__item__text">
                                                <h5><a href="productdetail.php?id=' . $item['ProductId'] . '">' . $item['ProductName'] . '</a></h5>
                                                <div class="product__item__price">' . number_format($item['Price'], 0, ',', '.') . 'đ</div>
                                            </div>
                                        </div>
                                    </div>';
                            }
                            ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Product Section End -->

<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->