<?php
require_once("../../db/dbhelper.php");
session_start();
//check nếu chưa đăng nhập chuyển đến trang login
if (empty($_SESSION['user'] && $_SESSION['user']['IsAdmin'] == 1)) {
    header("Location: ../../login");
}

$Paid = $CreatedDate = '';
if (isset($_GET['CreatedDate'])) {
    $CreatedDate = $_GET['CreatedDate'];
}
if (isset($_GET['Paid'])) {
    $Paid = $_GET['Paid'];

    if ($Paid == -1) {
        $Paid = '';
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
                <h2 class="text-conter">Quản lý đơn hàng</h2>
            </div>
            <div class="panel-body">
                <form method="GET">
                    <div class="form-group" style="display: flex;">
                        <input type="date" name="CreatedDate" style="width:200px; margin-right: 20px;" class="form-control" value="<?php if (!empty($CreatedDate)) echo $CreatedDate ?>">
                        <?php
                        if (!empty($Paid) && $Paid == 1) {
                            echo '<select name="Paid" id="cb-paid" class="form-control" style="width: 200px; margin-right: 20px;">
                                <option value="-1">--Lựa chọn trạng thái--</option>
                                <option value="1" selected>Đã thanh toán</option>
                                <option value="0">Chưa thanh toán</option>
                            </select>';
                        } else if ($Paid == 0 && $Paid != '') {
                            echo '<select name="Paid" id="cb-paid" class="form-control" style="width: 200px; margin-right: 20px;">
                                    <option value="-1">--Lựa chọn trạng thái--</option>
                                    <option value="1">Đã thanh toán</option>
                                    <option value="0" selected>Chưa thanh toán</option>
                                </select>';
                        } else {
                            echo '<select name="Paid" id="cb-paid" class="form-control" style="width: 200px; margin-right: 20px;">
                                <option value="-1">--Lựa chọn trạng thái--</option>
                                <option value="1">Đã thanh toán</option>
                                <option value="0">Chưa thanh toán</option>
                            </select>';
                        }
                        ?>

                        <input type="submit" style="width:200px;" value="Tìm kiếm" class="btn btn-primary">
                    </div>
                </form>

                <div class="table-wrapper-scroll-y my-custom-scrollbar" style="position: relative; height: 400px; overflow: auto; display: block;">
                    <table class="table table-dark" style="width: 100%;">
                        <thead class="thead-dark">
                            <tr >
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap" width="60px">STT</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Ngày đặt hàng</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Tên khách hàng</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Số điện thoại</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap" width="300px">Địa chỉ</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Thanh toán online</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Đã thanh toán</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap">Tổng tiền</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap" width="100px">Xem chi tiết</th>
                                <th style="position: sticky; top: 0; z-index: 10;" class="text-nowrap" width="100px">Xóa</th>
                            </tr>
                        </thead>
                        <tbody style="color:black;">
                            <?php
                            $additional = '';
                            if (!empty($CreatedDate)) {
                                $additional .= ' and CreatedDate like "%' . $CreatedDate . '%"';
                            }
                            if ($Paid != '') {
                                $additional .= ' and Paid = "' . $Paid . '"';
                            }
                            $sql = 'select * from orderproduct where 1' . $additional . ' order by CreatedDate DESC';
                            $orderList = executeResult($sql);
                            $index = 1;
                            $sumTotal = 0;
                            foreach ($orderList as $row) {
                                if ($row['Checked'] == 1) {
                                    echo '<tr style="background-color: #eff0e4;">
                                        <td>' . $index++ . '</td>
                                        <td>' . $row['FullName'] . '</td>
                                        <td>' . $row['CreatedDate'] . '</td>
                                        <td>' . $row['PhoneNumber'] . '</td>
                                        <td class="text-nowrap">' . $row['Address'] . '</td>
                                        <td style="text-align: center"><input class="checkbox" onclick="return false;" type="checkbox" value="' . $row['OnlinePayment'] . '"></td>
                                        <td style="text-align: center"><input class="checkbox" onclick="return false;" type="checkbox" value="' . $row['Paid'] . '"></td>
                                        <td style="color: red; font-weight: bold;">' . number_format($row['Total'], 0, ',', '.') . 'đ</td>
                                        <td class="text-nowrap"><a href="order-detail.php?id=' . $row['OrderId'] . '"><button class="btn btn-primary">Xem chi tiết</button></a></td>
                                        <td><button class="btn btn-danger" onclick="deleteByOrderId(' . $row['OrderId'] . ')">Xóa</button></td>

                                    </tr>';
                                } else {
                                    echo '<tr style="background-color: #eff28a;">
                                        <td>' . $index++ . '</td>
                                        <td>' . $row['FullName'] . '</td>
                                        <td>' . $row['CreatedDate'] . '</td>
                                        <td>' . $row['PhoneNumber'] . '</td>
                                        <td class="text-nowrap">' . $row['Address'] . '</td>
                                        <td style="text-align: center"><input class="checkbox" onclick="return false;" type="checkbox" value="' . $row['OnlinePayment'] . '"></td>
                                        <td style="text-align: center"><input class="checkbox" onclick="return false;" type="checkbox" value="' . $row['Paid'] . '"></td>
                                        <td style="color: red; font-weight: bold;">' . number_format($row['Total'], 0, ',', '.') . 'đ</td>
                                        <td class="text-nowrap"><a href="order-detail.php?id=' . $row['OrderId'] . '"><button class="btn btn-primary">Xem chi tiết</button></a></td>
                                        <td><button class="btn btn-danger" onclick="deleteByOrderId(' . $row['OrderId'] . ')">Xóa</button></td>
                                    </tr>';
                                }
                                $sumTotal += $row['Total'];
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
                <div style="font-weight: bold; font-size: 16px;">
                    <div>
                        Số lượng hóa đơn: <?= $index - 1 ?>
                    </div>
                    <div>
                        Tổng doanh thu: <?= number_format($sumTotal, 0, ',', '.') ?>đ
                    </div>
                </div>
            </div>
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
<script>
    $(function() {
        $('.checkbox').each(function(e) {
            if ($(this).val() == 1) {
                console.log($(this).val());
                $(this).attr("checked", "checked");
            }
        });
    });

    function deleteByOrderId(OrderId) {
        var option = confirm('Bạn có muốn xóa không?');
        if (!option) {
            return;
        }
        $.post('ajax.php', {
            'OrderId': OrderId,
            'action': 'delete'
        }, function(data) {
            location.reload()
        })
    }
</script>