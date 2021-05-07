<?php 
    require('dbconnect.php');
    session_start();
    if(!empty($_COOKIE['email']) && $_COOKIE['email'] != ''){
        $_POST['email'] = $_COOKIE['email'];
        $_POST['password'] = $_COOKIE['password'];
        $_POST['save'] = 'on';
    }
    if(!empty($_POST)){
        //ログインの処理
        if($_POST['email'] != '' && $_POST['password'] != ''){
            $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
            $login->execute(array($_POST['email'],sha1($_POST['password'])));
            $member = $login->fetch();
            if($member){
                //ログイン成功
                $_SESSION['id'] = $member['id'];
                $_SESSION['time'] = time();
                
                //ログイン情報を記録する
                if($_POST['save'] == 'on'){
                    setcookie('email', $_POST['email'], time()+60*60*24*14);
                    setcookie('password', $_POST['password'], time()+60*60*24*14);
                }
                
                header('Location: board-2.php'); exit();
            }else{
                $error['login'] = 'failed';
            }
        }else{
                $error['login'] = 'blank';
            } 
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>ログイン</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>
        <div class="login">
            <h2 class="login-header">ログイン</h2>
                <form action="" method="post" class="login-container">
                    <p>入会手続きがまだの方はこちらから</p>
                    <p>&raquo;<a href="entry.php">入会手続き</a></p>
                    <p><input type="email" placeholder="Email" name="email" value="<?php if(!empty($_POST['email'])) {echo htmlspecialchars($_POST['email'], ENT_QUOTES);}?>">
                    <?php if(!empty($error['login']) && $error['login'] == 'blank'): ?>
                        <p class="error">*メールアドレスとパスワードをご記入ください</p>
                    <?php endif; ?>
                    <?php if(!empty($error['login']) && $error['login'] == 'failed'): ?>
                        <p class="error">*ログインに失敗しました。正しくご記入ください。</p>
                    <?php endif; ?>
                    </p>
                    <p><input type="password" placeholder="Password" name="password" value="<?php if(!empty($error['password'])) {echo htmlspecialchars($_POST['password'], ENT_QUOTES);}?>">
                    </p>
                    <div class="checkbox">
                        <p>
                            <label for="big-check">
                                <p><input id="save" type="checkbox" name="save" value="on">次回からは自動的にログインする</p>
                            </label>
                        </p>
                    </div>
                    <p><input type="submit" value="ログイン"></p>
                </form>
        </div>
    </body>
</html>