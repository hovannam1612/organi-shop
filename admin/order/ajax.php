<?php
require_once('../../db/dbhelper.php');

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'delete':
                $OrderId = $_POST['OrderId'];
                $sql = 'delete from orderproduct where OrderId = "' . $OrderId . '"';
                execute($sql);
                break;
        }
    }
}


