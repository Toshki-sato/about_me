<?php 
session_start();
require('dbconnect.php');

if(!isset($_SESSION['join'])){
    header('Location: board.php');
    exit();
}

//echo '<pre>';
//var_dump($_POST );
//echo "</pre>";exit;

if(!empty($_POST)){
    //登録処理をする
    $statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
    echo $ret = $statement->execute(array(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        sha1($_SESSION['join']['password']),
        $_SESSION['join']['image']
        ));
        unset($_SESSION['join']);
        
        header('Location: board-2.php');
        exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>会員登録確認</title>
        <link rel="stylesheet" href="entry.css">
    </head>
    <body>
        <div class="login">
            <h2 class="login-header">会員登録確認</h2>
                <form action="" method="post" class="login-container">
                <input type="hidden" name="action" value="submit">
                    <h3>ニックネーム</h3>
		            <p>
		                <?php echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES); ?>
		            </p>
		            <h3>メールアドレス</h3>
		            <p>
		               <?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES); ?>
		            </p>
		            <h3>パスワード</h3>
		            <p>
			            【表示されません】
		            </p>
		            <h3>写真など</h3>
		            <p>
		                <img src="member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES); ?>" width="100" height="100" alt="">
		            </p>
                    <p>
                        <a href="entry.php?action=rewrite">&laquo;&nbsp;書き直す</a>|<input type="submit" value="登録する">
                    </p>
                </form>
        </div>
    </body>