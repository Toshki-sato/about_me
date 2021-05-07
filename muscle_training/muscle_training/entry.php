<?php
require('dbconnect.php');

session_start();
if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['name'] == '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}

	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}

	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' .$image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}
// 書き直し
if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'rewrite') {
$_POST = $_SESSION['join'];
$error['rewrite'] = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>登録</title>
        <link rel="stylesheet" href="entry.css">
    </head>
    <body>
        <div class="login">
            <p class="login-header">登録</p>
                <form action="" method="post" class="login-container" enctype="multipart/form-data">
                    <p>次のフォームに必要事項をご記入ください。</p>
                    <p>ニックネーム<span class="required">必須</span></p>
		            <p>
		                <input type="text" placeholder="Name" name="name" value="<?php if(!empty($_POST['name'])) {echo htmlspecialchars($_POST['name'], ENT_QUOTES);}?>"/>
			            <?php if(!empty($error['name']) && ($error['name'] == 'blank')): ?>
			                <p class="error">* ニックネームを入力してください</p>
			            <?php endif; ?>
		            </p>
		            <p>メールアドレス<span class="required">必須</span></p>
		            <p>
		                <input type="text" placeholder="mail" name="email" value="<?php if(!empty($_POST['email'])) {echo htmlspecialchars($_POST['email'], ENT_QUOTES);}?>"/>
                        <?php if(!empty($error['email']) && ($error['email'] == 'blank')): ?>
			                <p class="error">* メールアドレスを入力してください</p>
			            <?php endif; ?>
			            <?php if (!empty($error['email']) && $error['email'] == 'duplicate'): ?>
			            	<p class="error">* 指定されたメールアドレスはすでに登録されています</p>
			            <?php endif; ?>
		            </p>
		            <p>パスワード<span class="required">必須</span></p>
		            <p>
			            <input type="password" placeholder="Password" name="password" value="<?php if(!empty($_POST['email'])) {echo htmlspecialchars($_POST['email'], ENT_QUOTES);}?>"/>
                        <?php if (!empty($error['password']) && $error['password'] == 'blank'): ?>
				            <p class="error">* パスワードを入力してください</p>
			            <?php endif; ?>
			            <?php if (!empty($error['password']) && $error['password'] == 'length'): ?>
				            <p class="error">* パスワードは4文字以上で入力してください</p>
			            <?php endif; ?>
		            </p>
		            <p>写真など</p>
		            <p>
		                <input type="file" placeholder="Picture" name="image" size="35" />
			            <?php if (!empty($error['image']) && $error['image'] == 'type'): ?>
			                <p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
			            <?php endif; ?>
			            <?php if (!empty($error)): ?>
			                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
			            <?php endif; ?>
		            </p>
                    <p><input type="submit" value="登録"></p>
                </form>
        </div>
    </body>
</html>