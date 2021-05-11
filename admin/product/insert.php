<?php
session_start();
require_once("../../db/dbhelper.php");
$user = [];
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$state = $CreatedBy = $ModifiedBy = $CategoryId = $ProductId = $ProductCode = $ProductName = $Content = $Price = $Quantity = $Unit = $Description = $UrlPicture = '';
$state = "Thêm";
if (isset($_GET["ProductId"])) {
    $ProductId = $_GET["ProductId"];
    if ($ProductId != '') {
        $state = "Sửa";
    }
    $sql = "select * from product where ProductId = '$ProductId'";
    $result = executeSingleResult($sql);
    if ($result != null) {
        $ProductName = $result["ProductName"];
        $ProductCode = $result["ProductCode"];
        $Content = $result["Content"];
        $Price = $result["Price"];
        $Quantity = $result["Quantity"];
        $Unit = $result["Unit"];
        $Description = $result["Description"];
        $UrlPicture = $result["UrlPicture"];
        $CategoryId = $result["CategoryId"];
    }
}

if (!empty($_POST)) {
    if (isset($_POST["ProductId"])) {
        $ProductId = $_POST["ProductId"];
        $ProductId = str_replace('"', '\\"', $ProductId);
    }
    if (isset($_POST["ProductName"])) {
        $ProductName = $_POST["ProductName"];
        $ProductName = str_replace('"', '\\"', $ProductName);
    }
    if (isset($_POST["ProductCode"])) {
        $ProductCode = $_POST["ProductCode"];
        $ProductCode = str_replace('"', '\\"', $ProductCode);
    }
    if (isset($_POST["Content"])) {
        $Content = urldecode($_POST["Content"]);
        $Content = str_replace('"', '\\"', $Content);
    }
    if (isset($_POST["Price"])) {
        $Price = $_POST["Price"];
        $Price = str_replace('"', '\\"', $Price);
    }
    if (isset($_POST["Quantity"])) {
        $Quantity = $_POST["Quantity"];
        $Quantity = str_replace('"', '\\"', $Quantity);
    }
    if (isset($_POST["Unit"])) {
        $Unit = $_POST["Unit"];
        $Unit = str_replace('"', '\\"', $Unit);
    }
    if (isset($_POST["Description"])) {
        $Description = $_POST["Description"];
        $Description = str_replace('"', '\\"', $Description);
    }

    if (isset($_POST["CategoryId"])) {
        $CategoryId = $_POST["CategoryId"];
    }

    if (isset($_POST["submit"])) {
        $CreatedDate = $ModifiedDate = date('Y-m-d G:i:s');
        $CreatedBy = $ModifiedBy = $user['UserName'];
        //save img
        $img_name = $_FILES['UrlPicture']['name'];
        $img_size = $_FILES['UrlPicture']['size'];
        $tmp_name = $_FILES['UrlPicture']['tmp_name'];
        $error = $_FILES['UrlPicture']['error'];
        $em = "";
        if ($error === 0) {
            if ($img_size > 1000000) {
                $em = "Xin lỗi, dung lượng ảnh quá lớn";
                header("Location: insert.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    //set url picture name
                    $UrlPicture = $new_img_name;
                    $img_upload_path = 'uploads/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                } else {
                    $em = "Xin lỗi, file không đúng định dạng hình ảnh";
                    header("Location: insert.php?error=$em");
                }
            }
        } else {
            $em = "Có lỗi xảy ra";
            header("Location: insert.php?error=$em");
        }

        if ($UrlPicture != '') {
            if ($ProductId == '') {
                $sql = 'insert into product(ProductName, ProductCode, CategoryId, Quantity, Unit, UrlPicture, Description, Content, Price, CreatedDate, CreatedBy) 
            values("' . $ProductName . '", "' . $ProductCode . '", "' . $CategoryId . '", "' . $Quantity . '", "' . $Unit . '", "' . $UrlPicture . '", "' . $Description . '", "' . $Content . '", "' . $Price . '", "' . $CreatedDate . '", "'.$CreatedBy.'")';
            } else {
                $sql = 'update product set ProductName = "' . $ProductName . '", ProductCode = "' . $ProductCode . '", CategoryId = "' . $CategoryId . '", Quantity = "' . $Quantity . '", Unit = "' . $Unit . '", UrlPicture = "' . $UrlPicture . '", Description = "' . $Description . '", Content = "' . $Content . '", Price = "' . $Price . '", ModifiedDate = "' . $ModifiedDate . '", ModifiedBy = "'.$ModifiedBy.'" where ProductId = "' . $ProductId . '"';
            }
            execute($sql);
            header('Location: index.php');
            die();
        }
    }
}
?>

<?php
require_once('../layout/header.php')
?>

<!-- page content -->
<div class="right_col" role="main">
    <div class="container">
        <?php if (isset($_GET['error'])) : ?>
            <h4 style="color: red;"><?php echo $_GET['error']; ?></h4>
        <?php endif ?>
        <h3></h3>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="text-conter"><?= $state ?> Sản phẩm</h2>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" value="<?= $ProductId ?>" hidden="true">
                        <label for="ProductName">Tên sản phẩm</label>
                        <input required="true" type="text" class="form-control" id="ProductName" name="ProductName" value="<?= $ProductName ?>">
                        <label for="ProductCode">Mã sản phẩm</label>
                        <input required="true" type="text" class="form-control" id="ProductCode" name="ProductCode" value="<?= $ProductCode ?>">
                        <label for="CategoryId">Chọn danh mục</label>
                        <select class="form-control" id="CategoryId" name="CategoryId" required>
                            <option value="">--Lựa chọn danh mục--</option>
                            <?php
                            $sql = 'select * from category';
                            $result = executeResult($sql);
                            foreach ($result as $item) {
                                if ($item['CategoryId'] == $CategoryId) {
                                    echo '<option selected value="' . $item['CategoryId'] . '">' . $item['CategoryName'] . '</option>';
                                } else {
                                    echo '<option value="' . $item['CategoryId'] . '">' . $item['CategoryName'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label for="UrlPicture">Hình ảnh</label>
                        </br>
                        <input class="file-input" type="file" id="UrlPicture" name="UrlPicture" value="<?= $UrlPicture ?>">
                        <?php if ($UrlPicture != '') : ?>
                            <img id="imgReview" style="max-width: 200px;" src="uploads/<?= $UrlPicture ?>">
                        <?php endif ?>
                        <img id="imgUpload" style="max-width: 200px;" src="">
                        </br>
                        <label for="Unit">Đơn vị</label>
                        <input required="true" type="text" class="form-control" id="Unit" name="Unit" value="<?= $Unit ?>">
                        <label for="Quantity">Số lượng</label>
                        <input required="true" type="text" class="form-control" id="Quantity" name="Quantity" value="<?= $Quantity ?>">
                        <label for="Price">Giá bán</label>
                        <input required="true" type="text" class="form-control" id="Price" name="Price" value="<?= $Price ?>">
                        <label for="Description">Mô tả</label>
                        <textarea style="min-height: 150px;" required="true" type="text" class="form-control" id="Description" name="Description"><?= $Description ?></textarea>
                        <label for="Content">Nội dung</label>
                        <textarea required="true" type="text" class="form-control" id="Content" name="Content">
                                        <?= $Content ?>
                                    </textarea>
                    </div>
                    <center>
                        <button class="btn btn-success" type="submit" name="submit">Lưu</button>
                    </center>
                </form>
            </div>
        </div>
        <a href="index.php"><button class="btn btn-outline-secondary">Danh sách sản phẩm</button></a>
    </div>

</div>
<!-- /page content -->
</div>

<!-- footer -->
<?php
require_once('../layout/footer.php')
?>
<!-- end footer -->

<script>
    $(function() {
        $('#Content').summernote({
            height: 300
        });
    })

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#imgUpload').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $(".file-input").change(function() {
        readURL(this);
        $('#imgReview').hide();
    });
</script>

</body>

</html>