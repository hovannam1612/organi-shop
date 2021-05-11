<?php
require_once("../../db/dbhelper.php");
require_once("../../common/utility.php");
session_start();

//check nếu chưa đăng nhập chuyển đến trang login
if(empty($_SESSION['user'] && $_SESSION['user']['IsAdmin'] == 1)){
    header("Location: ../../login");
}

$user = [];
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

//Tìm kiếm theo tên
$s = '';
if (isset($_GET['s'])) {
    $s = $_GET['s'];
}
//Tìm kiếm theo danh mục 
$IsAdmin = '';
if (isset($_GET['IsAdmin'])) {
    $IsAdmin = $_GET['IsAdmin'];
    if ($IsAdmin == -1) {
        $IsAdmin = "";
    }
}

//Lấy lại mật khẩu
$NewPassword = $RePassword = $UserId = $ModifiedBy = $ModifiedDate = $mess = "";
if (isset($_POST['RetrievalPass'])) {
    if (isset($_POST['NewPassword'])) {
        $NewPassword = $_POST['NewPassword'];
        $NewPassword = str_replace('"', '\\"', $NewPassword);
    }
    if (isset($_POST['RePassword'])) {
        $RePassword = $_POST['RePassword'];
        $RePassword = str_replace('"', '\\"', $RePassword);
    }
    if (isset($_POST['UserId'])) {
        $UserId = $_POST['UserId'];
        $UserId = str_replace('"', '\\"', $UserId);
    }
    if ($NewPassword != $RePassword) {
        $mess = "Nhập lại mật khẩu không đúng";
    } else {
        $PassNew = password_hash($NewPassword, PASSWORD_DEFAULT);
        if ($user != null) {
            $ModifiedBy = $user['UserName'];
        }
        $ModifiedDate = date('Y-m-d G:i:s');
        $sql = 'update useraccount set Password = "' . $PassNew . '", ModifiedDate = "' . $ModifiedDate . '", ModifiedBy = "' . $ModifiedBy . '" WHERE UserId = "' . $UserId . '"';
        execute($sql);
        $mess = 'Cập nhật thành công';
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
                <h2 class="text-conter" style="font-size: 30px; ">Quản lý tài khoản</h2>
            </div>

            <?php
            if (strpos($mess, 'Cập nhật thành công') !== false) {
                echo '<h3 class="mess" style="color: green">' . $mess . '</h3>';
            } else {
                echo '<h3 class="mess" style="color: red">' . $mess . '</h3>';
            }
            ?>
            <div class="panel-body">
                <div class="row" style="margin: 20px 0;">
                    <div class="col-lg-4" style="padding: 0;">
                        <a href="insert.php">
                            <button class="btn btn-success">Thêm tài khoản</button>
                        </a>
                    </div>
                    <div class="col-lg-8" style="padding: 0;">
                        <form method="get">
                            <div class="col-lg-4">
                                <select name="IsAdmin" id="IsAdmin" class="form-control" style="width: 250px; margin-right:200px;">
                                    <option value="-1">--Lựa chọn quyền truy cập--</option>
                                    <?php
                                    if (!empty($IsAdmin) && $IsAdmin == 1) {
                                        echo '<option value="1" selected>Admin</option>
                                        <option value="0">User</option>';
                                    } else if ($IsAdmin == 0 && $IsAdmin != '') {
                                        echo '<option value="1">Admin</option>
                                        <option value="0" selected>User</option>';
                                    } else {
                                        echo '<option value="1">Admin</option>
                                        <option value="0">User</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <input placeholder="Tìm kiếm theo tên..." type="text" class="form-control" id="s" name="s" value="<?= $s ?>" style="width: 200px; margin-left: 75px;">
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
                                <th>Họ và tên</th>
                                <th>Tên đăng nhập</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Ngày tạo</th>
                                <th>Admin</th>
                                <th width="100px">Lấy lại mật khẩu</th>
                                <th width="100px">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            $additional = '';
                            if (!empty($s)) {
                                $additional .= ' and FullName like "%' . $s . '%"';
                            }
                            if ($IsAdmin != "") {
                                $additional .= ' and IsAdmin = "' . $IsAdmin . '"';
                            }

                            //số sản phẩm hiện thị trên 1 trang
                            $limit = 7;

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

                            $sql = 'select count(UserId) total from useraccount where 1' . $additional;
                            $result = executeSingleResult($sql);

                            //tổng số lượng sản phẩm
                            $count = $result['total'];

                            //Số lượng trang hiện thị
                            $number = ceil($count / $limit);

                            $sql = 'select * from useraccount where 1' . $additional . ' order by CreatedDate DESC limit ' . $firstIndex . ', ' . $limit . '';
                            $accountList = executeResult($sql);
                            if ($accountList == null) {
                                echo 'Tài khoản trống';
                            }
                            foreach ($accountList as $row) {
                                echo '<tr>
                                        <td>' . ++$firstIndex . '</td>
                                        <td>' . $row['FullName'] . '</td>
                                        <td><b>' . $row['UserName'] . '</b></td>
                                        <td>' . $row['Address'] . '</td>
                                        <td>' . $row['PhoneNumber'] . '</td>
                                        <td>' . $row['Email'] . '</td>
                                        <td>' . $row['CreatedDate'] . '</td>
                                        <td style="text-align: center"><input class="checkbox" onclick="return false;" type="checkbox" value="' . $row['IsAdmin'] . '"></td>
                                        <td style="width: 170px"><button class="btn btn-warning btnRetrievalPass" value="' . $row['UserId'] . '" data-toggle="modal" data-target="#exampleModal">Lấy lại mật khẩu</button></td>
                                        <td><button class="btn btn-danger" onclick="deleteByUserId(' . $row['UserId'] . ')">Xóa</button></td>
                                    </tr>';
                            }
                            ?>

                        </tbody>
                    </table>

                    <!-- Phân trang -->
                    <?= Pagination($number, $page, '&s=' . $s . '&IsAdmin=' . $IsAdmin) ?>
                </div>
            </div>

            <!-- dialog retrieval password -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Lấy lại mật khẩu</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="hidden" name="UserId" class="form-control" id="UserIdHidden">
                                </div>
                                <div class="form-group">
                                    <label for="NewPassword" class="col-form-label">Mật khẩu mới</label>
                                    <input required type="password" name="NewPassword" class="form-control" id="NewPassword">
                                </div>
                                <div class="form-group">
                                    <label for="RePassword" class="col-form-label">Xác nhận mật khẩu</label>
                                    <input required type="password" name="RePassword" class="form-control" id="RePassword">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="submit" name="RetrievalPass" class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- end dialog -->
        </div>
    </div>
</div>
<!-- /page content -->
</div>
<!-- footer -->
<?php
require_once("../layout/footer.php");
?>
<!-- end footer -->
<script type="text/javascript">
    function deleteByUserId(userId) {
        var option = confirm('Bạn có muốn xóa không?');
        if (!option) {
            return;
        }
        $.post('ajax.php', {
            'UserId': userId,
            'action': 'delete'
        }, function(data) {
            location.reload()
        })
    }

    $(function() {
        $('.checkbox').each(function(e) {
            if ($(this).val() == 1) {
                console.log($(this).val());
                $(this).attr("checked", "checked");
            }
        });
        $(".btnRetrievalPass").click(function() {
            var UserId = $(this).val();
            $("#UserIdHidden").val(UserId);
        });

        setTimeout(function() {
            $(".mess").fadeOut(1500);
        }, 2000);
    });
</script>