<?php
require_once('../../db/dbhelper.php');

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'delete':
                $ProductId = $_POST['ProductId'];
                $sql = 'delete from product where ProductId = "' . $ProductId . '"';
                execute($sql);
                break;
        }
    }
}


