<?php 
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    
    //投稿を削除する
    $messages = $db->prepare('SELECT * FROM weights WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();
    
    //削除する
    $del = $db->prepare('DELETE FROM weights WHERE id=?');
    $del->execute(array($id));
    
}

header('Location: weight-3.php'); exit();
?>