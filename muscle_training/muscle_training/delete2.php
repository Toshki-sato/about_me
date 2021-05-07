<?php 
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    
    //投稿を削除する
    $messages = $db->prepare('SELECT * FROM records WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();
    
    //削除する
    $del = $db->prepare('DELETE FROM records WHERE id=?');
    $del->execute(array($id));
    
}

header('Location: menu-2.php'); exit();
?>