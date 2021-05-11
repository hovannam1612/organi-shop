<?php
require_once("../../db/dbhelper.php");
session_start();
$user = [];
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$FullName = $Email = $Address = $PhoneNumber = $UserName = $Password = $IsAdmin = $mess = '';

function checkUserNameAndEmail($userName, $email)
{
    if (isset($email)) {
        $sql = 'select UserName from useraccount where Email = "' . $email . '"';
        $result = executeResult($sql);
        if ($result != null)
            return 2;
    }

    if (isset($userName)) {
        $sql = 'select UserName from useraccount where UserName = "' . $userName . '"';
        $result = executeResult($sql);
        if ($result != null)
            return 1;
    }
    return 3;
}

if (!empty($_POST)) {

    $CreatedDate = date('Y-m-d H:s:i');
    if($user != null){
        $CreatedBy = $user['UserName'];
    }

    if (isset($_POST['IsAdmin'])) {
        $IsAdmin = $_POST['IsAdmin'];
        $IsAdmin = str_replace('"', '\\"', $IsAdmin);
    }
    if (isset($_POST['FullName'])) {
        $FullName = $_POST['FullName'];
        $FullName = str_replace('"', '\\"', $FullName);
    }
    if (isset($_POST['Email'])) {
        $Email = $_POST['Email'];
        $Email = str_replace('"', '\\"', $Email);
    }
    if (isset($_POST['Address'])) {
        $Address = $_POST['Address'];
        $Address = str_replace('"', '\\"', $Address);
    }
    if (isset($_POST['PhoneNumber'])) {
        $PhoneNumber = $_POST['PhoneNumber'];
        $PhoneNumber = str_replace('"', '\\"', $PhoneNumber);
    }
    if (isset($_POST['UserName'])) {
        $UserName = $_POST['UserName'];
        $UserName = str_replace('"', '\\"', $UserName);
    }
    if (isset($_POST['Password'])) {
        $Password = $_POST['Password'];
        $Password = str_replace('"', '\\"', $Password);
        $PasswordHash = password_hash($Password, PASSWORD_DEFAULT);
    }

    if (checkUserNameAndEmail($UserName, $Email) == 3) {
        $sql = 'insert into useraccount(UserName, FullName, Password, Address, PhoneNumber, Email, IsAdmin, CreatedDate, CreatedBy)
        values("' . $UserName . '","' . $FullName . '","' . $PasswordHash . '","' . $Address . '","' . $PhoneNumber . '","' . $Email . '","' . $IsAdmin . '","' . $CreatedDate . '","' . $CreatedBy . '")';
        $result = execute($sql);
        if ($result) {
            $mess = "Tài khoản đã được tạo";
        }
    } else if (checkUserNameAndEmail($UserName, $Email) == 1) {
        $mess = "Tên đăng nhập đã tồn tại";
    } else {
        $mess = "Email đã tồn tại";
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
        <div style="text-align: center; margin: 0; color: #000; font-size: 20px;"><?= $mess ?></div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="text-conter">Thêm tài khoản</h2>
            </div>
            <div class="panel-body">
                <form class="signin-form" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="FullName" placeholder="Fullname" value="<?php if (!empty($FullName)) echo $FullName ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="Email" placeholder="Email" value="<?php if (!empty($Email)) echo $Email ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="Address" placeholder="Address" value="<?php if (!empty($Address)) echo $Address ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="PhoneNumber" placeholder="PhoneNumber" value="<?php if (!empty($PhoneNumber)) echo $PhoneNumber ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="UserName" placeholder="Username" value="<?php if (!empty($UserName)) echo $UserName ?>" required>
                    </div>
                    <div class="form-group">
                        <input id="password-field" type="password" class="form-control" name="Password" value="<?php if (!empty($Password)) echo $Password ?>" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" id="IsAdmin" name="IsAdmin" required>
                            <option value="">--Lựa chọn quyền--</option>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control btn btn-primary submit px-3">Create account</button>
                    </div>
                </form>
            </div>
        </div>
        <a href="index.php"><button class="btn btn-outline-secondary">Danh sách tài khoản</button></a>
    </div>

</div>
<!-- /page content -->
</div>

<!-- footer content -->
<?php
require_once('../layout/footer.php');
?>
<!-- end footer -->

</body>

</html>