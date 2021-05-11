<?php
require_once('../db/dbhelper.php');
session_start();

$UserId = '';
if (isset($_SESSION['user'])) {
    $UserId = $_SESSION['user']['UserId'];
}

$sql = 'select Password from useraccount where UserId = "' . $UserId . '"';
$profile = executeSingleResult($sql);
$Password = $mess = '';
if ($profile != null) {
    $Password = $profile['Password'];
}
$OldPassword = $NewPassword = $RePassword = '';
if (!empty($_POST)) {
    $OldPassword = $_POST['OldPassword'];
    $NewPassword = $_POST['NewPassword'];
    $RePassword = $_POST['RePassword'];

    $PasswordVerify = password_verify($OldPassword, $Password);
    if (!$PasswordVerify) {
        $mess = 'Mật khẩu cũ không đúng';
    } else {
        if ($NewPassword != $RePassword) {
            $mess = 'Nhập lại mật khẩu không đúng';
        } else {
            $PassNew = password_hash($NewPassword, PASSWORD_DEFAULT);
            $sql = 'UPDATE useraccount SET Password = "' . $PassNew . '" WHERE UserId = "' . $UserId . '"';
            execute($sql);
            $mess = 'Cập nhật thành công';
        }
    }
}
?>
<!-- header -->
<?php
require_once("./layout/header.php");
?>
<!-- end header -->

<!-- Profile -->
<div class="contact-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__form__title">
                    <h2>Thay đổi mật khẩu</h2>
                    <?php
                        if(strpos($mess, 'Cập nhật thành công') !== false){
                            echo '<div style="text-align: center; margin: 20px; color: green; font-size: 20px;">'.$mess.'</div>';
                        }else{
                            echo '<div style="text-align: center; margin: 20px; color: red; font-size: 20px;">'.$mess.'</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div style="margin: auto; max-width: 500px;">
            <form method="post">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <input required="true" type="password" class="form-control" placeholder="Nhập mật khẩu cũ" id="OldPassword" name="OldPassword" value="<?php if(!empty($OldPassword)) echo $OldPassword?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <input required="true" type="password" class="form-control" placeholder="Nhập mật khẩu mới" id="NewPassword" name="NewPassword" value="<?php if(!empty($NewPassword)) echo $NewPassword?>">
                    </div>
                    <div class="form-group col-lg-12">
                        <input required="true" type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="RePassword" name="RePassword" value="<?php if(!empty($RePassword)) echo $RePassword?>">
                    </div>
                    <div class="col-lg-12 text-center">
                        <button type="submit" class="site-btn">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Profile -->
<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->