<?php
session_start();
if(!empty($_SESSION['user']) && $_SESSION['user']['IsAdmin'] == 1){
    header('Location: product');
}else{
    header('Location: ../login/');
}