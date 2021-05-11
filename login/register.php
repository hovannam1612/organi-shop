<?php
require_once("../db/dbhelper.php");
$FullName = $Email = $Address = $PhoneNumber = $UserName = $Password = '';
$mess = '';

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
    $IsAdmin = 0;
    $CreatedDate = date('Y-m-d H:s:i');
    $CreatedBy = $_POST['UserName'];

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
<!doctype html>
<html lang="en">

<head>
    <title>Đăng ký</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="img js-fullheight" style="background-image: url(images/bg.jpg);">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Register</h2>
                </div>
            </div>
            <div style="text-align: center; margin: 0; color: #fff; font-size: 20px;" ><?= $mess ?></div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <form id="formLogin" class="signin-form" method="POST">
                            <div class="form-group">
                                <input type="text" class="form-control" name="FullName" placeholder="Fullname" value="<?php if(!empty($FullName)) echo $FullName?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="Email" placeholder="Email" value="<?php if(!empty($Email)) echo $Email?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="Address" placeholder="Address" value="<?php if(!empty($Address)) echo $Address?>" required>
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="PhoneNumber" placeholder="PhoneNumber" value="<?php if(!empty($PhoneNumber)) echo $PhoneNumber?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="UserName" placeholder="Username" value="<?php if(!empty($UserName)) echo $UserName?>" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" class="form-control" name="Password" value="<?php if(!empty($Password)) echo $Password?>" placeholder="Password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Create account</button>
                            </div>
                        </form>
                        <span style="color: #b1b1b3;">Already have an account?&nbsp;</span><a href="index.php" style="color: #fff">Sign-In</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        
    </script>
</body>

</html>