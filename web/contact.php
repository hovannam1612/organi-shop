<?php
require_once('../db/dbhelper.php');
session_start();
$FullName = $Email = $Content = $CreatedBy = $mess = '';
if (!empty($_POST)) {
    if (isset($_POST['FullName'])) {
        $FullName = $_POST['FullName'];
        $FullName = str_replace('"', '\\"', $FullName);
    }
    if (isset($_POST['Email'])) {
        $Email = $_POST['Email'];
        $Email = str_replace('"', '\\"', $Email);
    }
    if (isset($_POST['Content'])) {
        $Content = $_POST['Content'];
        $Content = str_replace('"', '\\"', $Content);
    }
    $CreatedDate = date('Y-m-d H:s:i');
  
    if(isset($_SESSION['user'])){
        $CreatedBy = $_SESSION['user']['UserName'];
    }else{
        $CreatedBy = 'anonymous';
    }
    $sql = 'INSERT INTO contact(FullName,Email, Content, CreatedDate, CreatedBy) VALUES("'.$FullName.'", "'.$Email.'", "'.$Content.'", "'.$CreatedDate.'", "'.$CreatedBy.'")';
    $result = execute($sql);
    if($result != null){
        $mess = "Gửi lời nhắn thành công";
    }
}
   
?>
<!-- header -->
<?php
require_once("./layout/header.php");
?>
<!-- end header -->

<!-- Contact Form Begin -->
<div class="contact-form spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact__form__title">
                    <div style="text-align: center; margin: 20px; color: green; font-size: 25px;"><?=$mess?></div>
                    <h2>Leave Message</h2>
                </div>
            </div>
        </div>
        <form method="post">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <input type="text" placeholder="Full Name" name="FullName" value="<?php if(!empty($FullName)) echo $FullName?>" required >
                </div>
                <div class="col-lg-6 col-md-6">
                    <input type="email" placeholder="Your Email" name="Email" value="<?php if(!empty($Email)) echo $Email?>" required>
                </div>
                <div class="col-lg-12 text-center">
                    <textarea placeholder="Your message" required name="Content" value="<?php if(!empty($Content)) echo $Content?>"></textarea>
                    <button type="submit" class="site-btn">SEND MESSAGE</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Contact Form End -->
<!-- footer -->
<?php
require_once("./layout/footer.php");
?>
<!-- end footer -->