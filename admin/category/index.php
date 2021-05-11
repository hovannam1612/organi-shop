<?php
require_once("../../db/dbhelper.php");
session_start();
//check nếu chưa đăng nhập chuyển đến trang login
if(empty($_SESSION['user'] && $_SESSION['user']['IsAdmin'] == 1)){
    header("Location: ../../login");
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
                <h2 class="text-conter">Quản lý danh mục sản phẩm</h2>
            </div>
            <div class="panel-body">
                <a href="insert.php">
                    <button class="btn btn-success" style="margin: 20px 0;">Thêm danh mục sản phẩm</button>
                </a>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="60px">STT</th>
                            <th>Hình ảnh</th>
                            <th>Tên danh mục sản phẩm</th>
                            <th>Mã danh mục</th>
                            <th>Nguồn gốc</th>
                            <th>Xuất xứ</th>
                            <th width="100px">Sửa</th>
                            <th width="100px">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "select * from category order by CreatedDate DESC";
                        $categoryList = executeResult($sql);
                        $index = 1;
                        foreach ($categoryList as $row) {
                            echo '<tr>
                                        <td>' . $index++ . '</td>
                                        <td><img src="uploads/' . $row['Image'] . '" width="200px" height="150px"></td>
                                        <td>' . $row['CategoryName'] . '</td>
                                        <td>' . $row['CategoryCode'] . '</td>
                                        <td>' . $row['Origin'] . '</td>
                                        <td>' . $row['Manufacturer'] . '</td>
                                        <td><a href="insert.php?CategoryId=' . $row['CategoryId'] . '"><button class="btn btn-warning">Sửa</button></a></td>
                                        <td><button class="btn btn-danger" onclick="deleteByCategoryId(' . $row['CategoryId'] . ')">Xóa</button></td>
                                    </tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
</div>
<script type="text/javascript">
    function deleteByCategoryId(categoryId) {
        var option = confirm('Bạn có muốn xóa không?');
        if (!option) {
            return;
        }
        $.post('ajax.php', {
            'CategoryId': categoryId,
            'action': 'delete'
        }, function(data) {
            console.log(data);
            location.reload()
        })
    }
</script>
<!-- footer -->
<?php
require_once("../layout/footer.php");
?>
<!-- end footer -->