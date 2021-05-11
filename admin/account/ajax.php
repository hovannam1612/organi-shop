<?php
require_once('../../db/dbhelper.php');

if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'delete':
                $UserId = $_POST['UserId'];
                $sql = 'delete from useraccount where UserId = "' . $UserId . '"';
                execute($sql);
                break;
        }
    }
}


