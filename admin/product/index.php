<?php
require_once("../../db/dbhelper.php");
require_once("../../common/utility.php");
session_start();
//check nếu chưa đăng nhập chuyển đến trang login
if(empty($_SESSION['user'] && $_SESSION['user']['IsAdmin'] == 1)){
    header("Location: ../../login");
}

//Tìm kiếm theo tên
$s = '';
if (isset($_GET['s'])) {
    $s = $_GET['s'];
}
//Tìm kiếm theo danh mục 
$category = '';
if (isset($_GET['CategorySelect'])) {
    $category = $_GET['CategorySelect'];
    if ($category == -1) {
        $category = "";
    }
}

?>

<!-- header -->
<?php
require_once("../layout/header.php");

?>
<!-- end header -->

<!-- page content -->
<div class="right_col" role="main">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="text-conter">Quản lý sản phẩm</h2>
            </div>
            <div class="panel-body">
                <div class="row" style="margin: 20px 0;">
                    <div class="col-lg-4" style="padding: 0;">
                        <a href="insert.php">
                            <button class="btn btn-success">Thêm sản phẩm</button>
                        </a>
                    </div>
                    <div class="col-lg-8" style="padding: 0;">
                        <form method="get">
                            <div class="col-lg-4">
                                <select name="CategorySelect" id="CategorySelect" class="form-control" style="width: 210px; margin-right:20px;">
                                    <option value="-1">--Lựa chọn danh mục--</option>
                                    <?php
                                    $sql = 'select * from category';
                                    $result = executeResult($sql);
                                    foreach ($result as $item) {
                                        if ($item['CategoryId'] == $category) {
                                            echo '<option selected value="' . $item['CategoryId'] . '">' . $item['CategoryName'] . '</option>';
                                        } else {
                                            echo '<option value="' . $item['CategoryId'] . '">' . $item['CategoryName'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <input placeholder="Tìm kiếm theo tên..." type="text" class="form-control" id="s" name="s" value="<?=$s?>" style="width: 200px; margin: 0;">
                            </div>
                            <div class="col-lg-2" style="float: right;">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" id="dtHorizontalExample" width="100%">
                        <thead>
                            <tr>
                                <th width="60px">STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Mã sản phẩm</th>
                                <th>Tên danh mục</th>
                                <th>Hình ảnh</th>
                                <th>Đơn vị</th>
                                <th>Số lượng</th>
                                <th>Giá bán</th>
                                <th>Mô tả</th>
                                <th width="100px">Sửa</th>
                                <th width="100px">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php

                            
                            $additional = '';
                            if (!empty($s)) {
                                $additional .= ' and ProductName like "%' . $s . '%"';
                            }
                            if (!empty($category)) {
                                $additional .= ' and product.CategoryId = "' . $category . '"';
                            }

                            //số sản phẩm hiện thị trên 1 trang
                            $limit = 6;

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

                            $sql = 'select count(ProductId) total from product where 1' . $additional;
                            $result = executeSingleResult($sql);

                            //tổng số lượng sản phẩm
                            $count = $result['total'];

                            //Số lượng trang hiện thị
                            $number = ceil($count / $limit);

                            $sql = 'select product.ProductId, product.ProductName, product.ProductCode, product.UrlPicture, product.Content,
                                    product.Unit, product.Quantity, product.Price, product.Description, category.CategoryName category_name 
                                    from product left join category on product.CategoryId = category.CategoryId where 1' . $additional . ' order by product.CreatedDate DESC limit ' . $firstIndex . ', ' . $limit . '';
                            $productList = executeResult($sql);
                            if ($productList == null) {
                                echo 'Không có sản phẩm nào';
                            }
                            foreach ($productList as $row) {
                                echo '<tr>
                                        <td>' . ++$firstIndex . '</td>
                                        <td>' . $row['ProductName'] . '</td>
                                        <td>' . $row['ProductCode'] . '</td>
                                        <td>' . $row['category_name'] . '</td>
                                        <td><img src="uploads/' . $row['UrlPicture'] . '" width="200px" height="150px"></td>
                                        <td>' . $row['Unit'] . '</td>
                                        <td>' . $row['Quantity'] . '</td>
                                        <td>' . $row['Price'] . '</td>
                                        <td style="width:100%; max-height:150px; overflow:auto; display: block;">' . $row['Description'] . '</td>
                                        <td><a href="insert.php?ProductId=' . $row['ProductId'] . '"><button class="btn btn-warning">Sửa</button></a></td>
                                        <td><button class="btn btn-danger" onclick="deleteByCategoryId(' . $row['ProductId'] . ')">Xóa</button></td>
                                    </tr>';
                            }
                            ?>

                        </tbody>
                    </table>

                    <!-- Phân trang -->
                    <?= Pagination($number, $page, '&s=' . $s . '&CategorySelect=' . $category) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
</div>

<script type="text/javascript">
    function deleteByCategoryId(productId) {
        var option = confirm('Bạn có muốn xóa không?');
        if (!option) {
            return;
        }
        $.post('ajax.php', {
            'ProductId': productId,
            'action': 'delete'
        }, function(data) {
            location.reload()
        })
    }
</script>
<!-- footer -->
<?php
require_once("../layout/footer.php");
?>
<!-- end footer -->