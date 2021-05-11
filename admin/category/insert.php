<?php
session_start();
require_once("../../db/dbhelper.php");
$user = [];
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$CategoryId = $CategoryCode = $CategoryName = $Origin = $Manufacturer = $Image = $CreatedBy = $MoifiedBy = '';

$state = "Thêm";
if (isset($_GET["CategoryId"])) {
    $CategoryId = $_GET["CategoryId"];
    //trạng thái sửa
    if ($CategoryId != '') {
        $state = "Sửa";
    }
    $sql = "select * from Category where CategoryId = '$CategoryId'";
    $result = executeSingleResult($sql);
    if ($result != null) {
        $CategoryName = $result["CategoryName"];
        $CategoryCode = $result["CategoryCode"];
        $Origin = $result["Origin"];
        $Manufacturer = $result["Manufacturer"];
        $Image = $result["Image"];
    }
}

if (!empty($_POST)) {
    if (isset($_POST["CategoryId"])) {
        $CategoryId = $_POST["CategoryId"];
    }
    if (isset($_POST["CategoryCode"])) {
        $CategoryCode = $_POST["CategoryCode"];
    }
    if (isset($_POST["CategoryName"])) {
        $CategoryName = $_POST["CategoryName"];
    }
    if (isset($_POST["Origin"])) {
        $Origin = $_POST["Origin"];
    }
    if (isset($_POST["Manufacturer"])) {
        $Manufacturer = $_POST["Manufacturer"];
    }
    if (isset($_POST["submit"])) {
        $CreatedDate = $ModifiedDate = date('Y-m-d H:s:i');
        $CreatedBy = $MoifiedBy = $user['UserName'];
        //save image
        $img_name = $_FILES['Image']['name'];
        $img_size = $_FILES['Image']['size'];
        $tmp_name = $_FILES['Image']['tmp_name'];
        $error = $_FILES['Image']['error'];
        $em = "";
        echo $img_name;
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
                    $Image = $new_img_name;
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
        if ($Image != "") {
            if ($CategoryId == '') {
                $sql = 'insert into category(CategoryName, CategoryCode, Origin, Manufacturer, Image, CreatedDate, CreatedBy) 
                values("' . $CategoryName . '", "' . $CategoryCode . '", "' . $Origin . '", "' . $Manufacturer . '", "' . $Image . '", "' . $CreatedDate . '", "'.$CreatedBy.'")';
            } else {
                $sql = 'update category set CategoryName = "' . $CategoryName . '", CategoryCode = "' . $CategoryCode . '", Origin = "' . $Origin . '", Manufacturer = "' . $Manufacturer . '", Image = "' . $Image . '", ModifiedDate = "' . $ModifiedDate . '", ModifiedBy = "'.$MoifiedBy.'" where CategoryId = "' . $CategoryId . '"';
            }
            execute($sql);
            header('Location: index.php');
            die();
        }
    }
}
?>
<!-- header -->
<?php
require_once('../layout/header.php');
?>
<!-- end header -->

<!-- page content -->
<div class="right_col" role="main">
    <div class="container">
        <?php if (isset($_GET['error'])) : ?>
            <h4 style="color: red;"><?php echo $_GET['error']; ?></h4>
        <?php endif ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="text-conter"><?= $state ?> danh mục</h2>
            </div>
            <div class="panel-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" value="<?= $CategoryId ?>" hidden="true">
                        <label for="CategoryName">Tên danh mục</label>
                        <input required="true" type="text" class="form-control" id="CategoryName" name="CategoryName" value="<?= $CategoryName ?>">
                        <label for="CategoryCode">Mã danh mục</label>
                        <input required="true" type="text" class="form-control" id="CategoryCode" name="CategoryCode" value="<?= $CategoryCode ?>">
                        <label for="UrlPicture">Hình ảnh</label>
                        </br>
                        <input class="file-input" type="file" id="Image" name="Image" value="<?= $Image ?>">
                        <?php if ($Image != '') : ?>
                            <img id="imgReview" style="max-width: 200px;" src="uploads/<?= $Image ?>">
                        <?php endif ?>
                        <img id="imgUpload" style="max-width: 200px;" src="">
                        </br>
                        <label for="Origin">Xuất xứ</label>
                        <input required="true" type="text" class="form-control" id="Origin" name="Origin" value="<?= $Origin ?>">
                        <label for="Manufacturer">Thương hiệu</label>
                        <input required="true" type="text" class="form-control" id="Manufacturer" name="Manufacturer" value="<?= $Manufacturer ?>">
                    </div>
                    <center>
                        <button class="btn btn-success" name="submit" type="submit">Lưu</button>
                    </center>
                </form>
            </div>
        </div>
        <a href="index.php"><button class="btn btn-outline-secondary">Danh sách danh mục</button></a>
    </div>

</div>
<!-- /page content -->
</div>

<!-- footer content -->
<?php
require_once('../layout/footer.php');
?>
<!-- end footer -->

<script>
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