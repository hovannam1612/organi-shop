<?php
require_once('../db/dbhelper.php');
session_start();

$UserId = '';
if(isset($_SESSION['user'])){
    $UserId = $_SESSION['user']['UserId'];
}

$sql = 'select * from useraccount where UserId = "'.$UserId.'"';
$profile = executeSingleResult($sql);

$FullName = $Address = $Email = $PhoneNumber = '';
if(!empty($_POST)){
    $FullName = $_POST['FullName'];
    $Address = $_POST['Address'];
    $Email = $_POST['Email'];
    $PhoneNumber = $_POST['PhoneNumber'];
    //cập nhập thông tin 
    $sql = 'UPDATE useraccount SET Email = "'.$Email.'",FullName = "'.$FullName.'",PhoneNumber = "'.$PhoneNumber.'",Address = "'.$Address.'" WHERE useraccount.UserId = "'.$UserId.'"';
    //Thực thi truy vấn
    execute($sql);
    header("Location: profile.php");
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
                    <h2>Thông tin cá nhân</h2>
                </div>
            </div>
        </div>
        <form method="post">
            <div class="row">
                <div class="form-group col-lg-6 col-md-6">
                    <label for="FullName">Họ và tên</label>
                    <input required="true" type="text" class="form-control" placeholder="Nhập Họ và tên" id="FullName" name="FullName" value="<?=$profile['FullName']?>">
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <label for="Address">Địa chỉ</label>
                    <input required="true" type="text" class="form-control" placeholder="Nhập Địa chỉ" id="Address" name="Address" value="<?=$profile['Address']?>">
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <label for="PhoneNumber">Số điện thoại</label>
                    <input required="true" type="text" class="form-control" placeholder="Nhập Số điện thoại" id="PhoneNumber" name="PhoneNumber" value="<?=$profile['PhoneNumber']?>">
                </div>
                <div class="form-group col-lg-6 col-md-6">
                    <label for="Email">Email</label>
                    <input required="true" type="email" class="form-control" placeholder="Nhập Email" id="Email" name="Email" value="<?=$profile['Email']?>">
                </div>
                
                <div class="col-lg-12 text-center">
                    <button type="submit" class="site-btn">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Profile -->
<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->