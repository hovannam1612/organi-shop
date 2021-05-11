<?php
require_once("config.php");

$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
function getInsertedId(){
    return $GLOBALS['conn']->insert_id;
    mysqli_close($GLOBALS['conn']);
}

//hàm thực thi các câu lệnh insert, delete, update không cần trả về giá trị
function execute($sql){
    $result = mysqli_query($GLOBALS['conn'], $sql);
    return $result;
}

//hàm thực thi câu lệnh select trả về 1 mảng giá trị
function executeResult($sql){
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    $result = mysqli_query($conn, $sql);
    $data = [];
    if($result != null){
        while($row = mysqli_fetch_array($result, 1)){
            $data[] = $row;
        }
    }
    mysqli_close($conn);

    return $data;
}

//Thực thi câu lệnh select trả về duy nhất 1 bản ghi
function executeSingleResult($sql){
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result, 1);
    mysqli_close($conn);
    return $row;
}