<?php
require_once('../db/dbhelper.php');
session_start();
//Session user
if (!isset($_SESSION['user'])) {
	$_SESSION['user'] = [];
}

$mess = $UserName = $Password = '';

if (!empty($_POST)) {
	if (isset($_POST['UserName'])) {
		$UserName = $_POST['UserName'];
	}
	if (isset($_POST['Password'])) {
		$Password = $_POST['Password'];
	}

	$sql = 'select * from useraccount where UserName = "' . $UserName . '" or Email = "' . $UserName . '"';
	$result = executeSingleResult($sql);
	if ($result != null) {

		$PasswordVerify = password_verify($Password, $result['Password']);

		if ($PasswordVerify) {
			$_SESSION['user'] = $result;
			if ($result['IsAdmin'] == 0) {
				header('Location: ../web/index.php');
			} else {
				header('Location: ../admin/index.php');
			}
		} else {
			$mess = "Mật khẩu không đúng";
		}
	} else {
		$mess = "Tên đăng nhập hoặc Email không đúng";
	}
}
?>

<!doctype html>
<html lang="en">

<head>
	<title>Đăng nhập</title>
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
					<h2 class="heading-section">Login</h2>
				</div>
			</div>
			<div style="text-align: center; margin: 0; color: #fff; font-size: 20px;"><?= $mess ?></div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
						<form action="" method="POST" class="signin-form">
							<div class="form-group">
								<input type="text" class="form-control" name="UserName" value="<?=$UserName?>" placeholder="Username or Email" required>
							</div>
							<div class="form-group">
								<input id="password-field" type="password" class="form-control" value="<?=$Password?>" name="Password" placeholder="Password" required>
								<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
							<div class="form-group">
								<button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50">
									<label class="checkbox-wrap checkbox-primary">Remember Me
										<input type="checkbox" checked>
										<span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
							</div>
						</form>
						<a href="register.php" style="color: #fff">Create an account</a>

						<p class="w-100 text-center">&mdash; Or Sign In With &mdash;</p>
						<div class="social d-flex text-center">
							<a href="#" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Facebook</a>
							<a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>

</body>

</html>