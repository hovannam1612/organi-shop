<?php

use function PHPSTORM_META\sql_injection_subst;

require_once("../../db/dbhelper.php");
require "../Classes/PHPExcel.php";
session_start();
$user = [];
if (!empty($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$OrderId = '';
if (!empty($_GET)) {
    if (isset($_GET['id'])) {
        $OrderId = $_GET['id'];
    }
}

//lấy thông tin hóa đơn
$sqlOrder = 'select * from orderproduct where orderproduct.OrderId = "' . $OrderId . '"';
$OrderProduct = executeSingleResult($sqlOrder);
$PaymentType = 'Thanh toán khi nhận hàng';
if ($OrderProduct['OnlinePayment'] == 1) {
    $PaymentType = 'online';
}

$shipping = 30000;
$Paid = '';
if (isset($_POST['submit-save'])) {
    if (isset($_POST['Paid'])) {
        $Paid = $_POST['Paid'];
        $sql = 'update orderproduct set Paid = "' . $Paid . '" where OrderId = "' . $OrderId . '"';
        execute($sql);
        header("Refresh:0");
    }
}

//Xuất chi tiết hóa đơn
if (isset($_POST['submit-export'])) {
    //Xác nhận đã check hóa đơn
    $sql = 'update orderproduct set Checked = "1" where OrderId = "' . $OrderId . '"';
    execute($sql);
    //khởi tạo đối tượng
    $excel = new PHPExcel();

    //chọn trang sheet để ghi
    $excel->setActiveSheetIndex(0);

    //Tạo tiêu đề cho trang. (có thể không cần)
    $sheet = $excel->getActiveSheet()->setTitle('Hóa đơn - ' . $OrderId . '');

    //Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);

    //Tạo tiêu đề cho từng cột
    $sheet->setCellValue('B1', 'THÔNG TIN HÓA ĐƠN');

    $sheet->setCellValue('A2', 'Ngày mua hàng');
    $sheet->setCellValue('A3', 'Mã hóa đơn');
    $sheet->setCellValue('A4', 'Tên khách hàng');
    $sheet->setCellValue('A5', 'Số điện thoại');
    $sheet->setCellValue('A6', 'Địa chỉ');
    $sheet->setCellValue('A7', 'Email');

    $sheet->setCellValue('B2', $OrderProduct['CreatedDate']);
    $sheet->setCellValue('B3', $OrderId);
    $sheet->setCellValue('B4', $OrderProduct['FullName']);
    $sheet->setCellValue('B5', $OrderProduct['PhoneNumber']);
    $sheet->setCellValue('B6', $OrderProduct['Address']);
    $sheet->setCellValue('B7', $OrderProduct['Email']);

    $sheet->setCellValue('A9', 'CHI TIẾT HÓA ĐƠN');
    $sheet->setCellValue('A10', 'Tên sản phẩm');
    $sheet->setCellValue('B10', 'Giá');
    $sheet->setCellValue('C10', 'Số lượng');
    $sheet->setCellValue('D10', 'Tổng tiền');

    // thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
    // dòng bắt đầu = 9
    $sql = 'select orderdetail.ProductId, ProductName, UrlPicture, orderdetail.Price, orderdetail.Quantity from orderdetail inner join product on orderdetail.ProductId = product.ProductId where orderdetail.OrderId = "' . $OrderId . '"';
    $OrderDetails = executeResult($sql);
    $numRow = 11;
    foreach ($OrderDetails as $row) {
        $sheet->setCellValue('A' . $numRow, $row['ProductName']);
        $sheet->setCellValue('B' . $numRow, number_format($row['Price'], 0, ',', '.') . 'đ');
        $sheet->setCellValue('C' . $numRow, $row['Quantity']);
        $sheet->setCellValue('D' . $numRow, number_format($row['Price'] * $row['Quantity'], 0, ',', '.') . 'đ');
        $numRow++;
    }

    $sheet->setCellValue('C'.($numRow), 'Thành tiền');
    $sheet->setCellValue('D'.($numRow), number_format($OrderProduct['Total'] - $shipping, 0, ',', '.') . 'đ');
    $sheet->setCellValue('C'.($numRow+1), 'Phí ship');
    $sheet->setCellValue('D'.($numRow+1), number_format($shipping, 0, ',', '.') . 'đ');
    $sheet->setCellValue('C'.($numRow+2), 'TỔNG TIỀN');
    $sheet->setCellValue('D'.($numRow+2), number_format($OrderProduct['Total'], 0, ',', '.') . 'đ');

    $sheet->setCellValue('A'.($numRow+4), 'Người lập hóa đơn');
    $sheet->setCellValue('A'.($numRow+6), $user['FullName']);
    $sheet->setCellValue('C'.($numRow+4), 'Người mua hàng');
    $sheet->setCellValue('C'.($numRow+6), $OrderProduct['FullName']);

    //định dạng cột

    //Căn chỉnh cột
    $sheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00ffff00');
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2:D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A'.($numRow+4).':'.'D'.($numRow+6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    //in đậm chữ
    $styleArray = array(
        'font' => array(
            'bold' => true
        )
    );
    $sheet->getStyle('B1')->applyFromArray($styleArray);
    $sheet->getStyle('A9')->applyFromArray($styleArray);
    $sheet->getStyle('C'.($numRow+2).':'.'D'.($numRow+2))->applyFromArray($styleArray);
    
    //border table
    $sheet->getStyle('A10:'.'D'.($numRow+2))->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        )
    );
    //border outline
    $sheet->getStyle('A1:'.'D'.($numRow+7))->applyFromArray(
        array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )
            )
        )
    );
    // Khởi tạo đối tượng PHPExcel_write để thực hiện ghi file
    $objWrite = new PHPExcel_Writer_Excel2007($excel);
    $filename = 'order_' . $OrderId . '.xlsx';
    $objWrite->save($filename);

    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Cache-Control: max-age=0");
    readfile($filename);
    return;
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
                
                <div class="wrapper">
                    <div class="row">
                        <form method="POST" target="_self">

                            <div class="col-lg-6 col-md-8 col-sm-10 offset-lg-0 offset-md-2 offset-sm-1">
                                <div class="mobile h5">Billing Address</div>
                                <div id="details" class="bg-white rounded pb-5">
                                    <h2>Mã hóa đơn: <?= $OrderProduct['OrderId'] ?></h2>
                                    <h5>Ngày tạo: <?= $OrderProduct['CreatedDate'] ?></h5>
                                    <div class="form-group">
                                        <label class="text-muted">Họ tên</label>
                                        <input type="text" value="<?= $OrderProduct['FullName'] ?>" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-muted">Email</label>
                                        <input type="text" value="<?= $OrderProduct['Email'] ?>" class="form-control" readonly>
                                    </div>
                                    <div class="form-group"> <label class="text-muted">Số điện thoại</label> <input type="text" value="<?= $OrderProduct['PhoneNumber'] ?>" readonly class="form-control"> </div>
                                    <div class="form-group"> <label class="text-muted">Địa chỉ</label> <textarea class="form-control" readonly><?= $OrderProduct['Address'] ?></textarea></div>
                                    <div class="form-group"> <label class="text-muted">Ghi chú</label> <textarea class="form-control" readonly><?= $OrderProduct['Description'] ?></textarea></div>
                                    <p style="text-align: center; color: blue; font-size: 16px; font-weight: bold;">Hình thức thanh toán: <?= $PaymentType ?></p>
                                    <div class="form-group">
                                        <label for="paid" style="color: red; font-size: 18px; font-weight: bold;">Đã thanh toán &nbsp;</label>
                                        <?php
                                        if ($OrderProduct['Paid'] == 1) {
                                            echo '<input type="checkbox" name="Paid" id="paid" style="transform: scale(1.5);" checked onclick="return false;">';
                                        } else {
                                            echo '<input type="checkbox" name="Paid" value="1" id="paid" style="transform: scale(1.5); margin-right: 20px;">';
                                            echo '<input type="submit" name="submit-save" value="Lưu" class="btn btn-primary">';
                                        }
                                        ?>
                                    </div>

                                </div>

                            </div>
                            <div class="col-lg-6 col-md-8 col-sm-10 offset-lg-0 offset-md-2 offset-sm-1 pt-lg-0 pt-3">
                                <div id="cart" class="bg-white rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="h6">Giỏ hàng</div>
                                    </div>

                                    <!-- products -->
                                    <?php
                                    //Chi tiết hóa đơn
                                    $sqlOrderDetail = 'select orderdetail.ProductId, ProductName, UrlPicture, orderdetail.Price, orderdetail.Quantity from orderdetail inner join product on orderdetail.ProductId = product.ProductId where orderdetail.OrderId = "' . $OrderId . '"';
                                    $OrderDetails = executeResult($sqlOrderDetail);
                                    foreach ($OrderDetails as $item) {
                                        echo '<div class="d-flex jusitfy-content-between align-items-center pt-3 pb-2 border-bottom">
                                    <div class="item pr-2"> <img src="../product/uploads/' . $item['UrlPicture'] . '" alt="" width="80" height="80">
                                        <div class="number">' . $item['Quantity'] . '</div>
                                    </div>
                                    <div class=";d-flex flex-column px-3"><a href="../product/insert.php?ProductId=' . $item['ProductId'] . '" class="h5 text-primary">' . $item['ProductName'] . '</a>
                                        <div>Giá: ' . number_format($item['Price'], 0, ',', '.') . 'đ</div></div>
                                    <div class="ml-auto">' . number_format($item['Price'] * $item['Quantity'], 0, ',', '.') . 'đ</div>
                                </div>';
                                    }
                                    ?>
                                    <!-- end products -->

                                    <div class="d-flex align-items-center">
                                        <div class="display-5">Thành tiền</div>
                                        <div class="ml-auto font-weight-bold"><b class="h5"><?= number_format($OrderProduct['Total'] - $shipping, 0, ',', '.') ?>đ</b></div>
                                    </div>
                                    <div class="d-flex align-items-center py-2 border-bottom">
                                        <div class="display-5">Phí ship</div>
                                        <div class="ml-auto font-weight-bold"><b class="h5"><?= number_format($shipping, 0, ',', '.') ?>đ</b></div>
                                    </div>
                                    <div class="d-flex align-items-center py-2" style="color: red; font-weight: 900;">
                                        <div class="display-5">Tổng cộng</div>
                                        <div class="ml-auto d-flex">
                                            <div class="font-weight-bold"><b class="h5"><?= number_format($OrderProduct['Total'], 0, ',', '.') ?>đ</b></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-lg-3 pt-2 buttons mb-sm-0 mb-2">
                                    <div class="col-md-6 pt-md-0 pt-3">
                                        <a href="./index.php" class="btn btn-primary">Quản lý hóa đơn</a>
                                    </div>
                                    <div class="col-md-6 pt-md-0 pt-3">
                                        <input type="submit" name="submit-export" value="Xác nhận và xuất hóa đơn" class="btn btn-success">
                                    </div>
                                </div>
                            </div>
                        </form>
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