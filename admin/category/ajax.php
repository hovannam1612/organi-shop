<?php
require_once('../../db/dbhelper.php');

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'delete':
                $CategoryId = $_POST['CategoryId'];
                $sql = 'delete from category where CategoryId = "' . $CategoryId . '"';
                execute($sql);
                break;
        }
    }
}
