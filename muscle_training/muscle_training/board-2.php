<?php
            session_start();
            require('dbconnect.php');
            
            if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
                //ログインしている
                $_SESSION['time'] = time();
                 $members = $db->prepare('SELECT * FROM members WHERE id=?');
                 $members->execute(array($_SESSION['id']));
                 $member = $members->fetch();
            } else{
                //ログインしていない
                header('Location: login.php'); 
               exit();
            }
            
            //投稿を記録する
            if(!empty($_POST)) {
                if($_POST['message'] != ''){
                    $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=? ,created=NOW()');
                    $message->execute(array(
                        $member['id'],
                        $_POST['message'],
                        $_POST['reply_post_id']
                    ));
                   header('Location: board-2.php'); exit();
                } 
            
            }
            
            //投稿を取得する
            $page = '';
            if( isset($_REQUEST['page']) ){
                $page = $_REQUEST['page'];
            }
            
            if($page == ''){
                $page = 1;
            }
            $page = max($page,1);
            
            //最終ページを取得する
            $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
            $cnt = $counts->fetch();
            $maxPage = ceil($cnt['cnt']/5);
            $page = min($page,$maxPage);
            
            $start = ($page-1)*5;
            
            $posts = $db->prepare('SELECT m.name,m.picture ,p.* FROM members m,posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
                                // SELECT 
                                    //members.name,
                                    //members.picture,
                                    //posts.*
                                // FROM
                                    //members,
                                    //posts
                                // WHERE 
                                    //members.id=posts.member_id 
                                // ORDER BY 
                                    //posts.created DESC
                                // LIMIT ?,5
            $posts->bindParam(1,$start,PDO::PARAM_INT);
            $posts->execute();
            
            //htmlspecialcharsのショートカット
            function h($value){
                return htmlspecialchars($value, ENT_QUOTES);
            }
            //本文内のURLにリンクを設定します
            function makeLink($value){
                return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>',$value);
            }
            //返信の場合
            if(isset($_REQUEST['res'])){
                $response = $db->prepare('SELECT m.name, m.picture, p.*FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
                $response->execute(array($_REQUEST['res']));
                
                $table = $response->fetch();
                $message = '@'.$table['name']. ''.$table['message'];
            }
         ?>
<head>
        <meta charset="utf-8">
        <title>筋力トレーニング</title>
        <link rel="stylesheet" href="board-2.css">
    </head>
<header role="banner">
    <div class="logo">
        <a href="">
            <img class="logo" src="picture/Muscle_training-logo-white-2.png">
        </a>
    </div>
    <div class="logout">
        <a href="logout.php">ログアウト</a>
    </div>
</header>

<div class="flex-container">

<nav role="navigation">
        <ul style="list-style-type: none">
            <h2>メニュー</h2>
            <li　class="tag">
                <a href="board-2.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                    </svg>
                    <h4>ホーム</h4>
                </a>
            </li>
            <li class="tag">
                <a href="menu-2.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                        <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                    </svg>
                    <h4>トレーニング<br>メニュー</h4>
                </a>
            </li>
            <li class="tag">
                <a href="calendar-6.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-calendar-day" viewBox="0 0 16 16">
                        <path d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332h.684zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a1.806 1.806 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98v4.105zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43zm.094 5.093h.672V7.418h-.672v4.105z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                    <h4>カレンダー</h4>
                </a>
            </li>
            <li class="tag">
                <a href="weight-4.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-bar-chart-line" viewBox="0 0 16 16">
                        <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2zm1 12h2V2h-2v12zm-3 0V7H7v7h2zm-5 0v-3H2v3h2z"/>
                    </svg>
                    <h4>体重</h4>
                </a>
            </li>
        </ul>
</nav>

<main role="main">
<h1>ホーム</h1>
<form action="" method="post" class="form">
            <dl class="a1">
                <img src="member_picture/<?php echo h($member['picture']); ?>" width="48" height="48" alt="<?php echo h($member['name'], ENT_QUOTES); ?>" >
                <textarea name="message" class="textarea" placeholder="<?php echo h($member['name']); ?>さん、今日のトレーにニングを共有しましょう！（例：今日は胸トレをやったよ！！）" ><?php if(isset($message)){echo h ($message);} ?></textarea><input type="hidden" name="reply_post_id" value="<?php if(isset($_REQUEST['res'])){ echo h($_REQUEST['res']);}else{ echo '0'; }?>">
            </dl>
            <div>
                <input type="submit" class="submit" value="ツイート">
            </div>
</form>
        
        <?php foreach ($posts as $post): ?>
            <div class="msg">
                <div class="flex-container">
                    <img src="member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name'], ENT_QUOTES); ?>" />
                    <h5 class="name"><?php echo h($post['name']); ?></h5>
                </div>
                <div class="msg-min1">
                    <p><?php echo makeLink(h($post['message'])); ?></p>
                        
                </div>
                <div class="msg-min2">
                    <p class="day"><?php echo h(date('Y/n/d-G:i',strtotime($post['created']))); ?></p>
                        <?php if($post['reply_post_id'] > 0): ?>
                               <a href="view.php?id=<?php echo h($post['reply_post_id']); ?>">返信元のメッセージ</a>
                        <?php endif; ?>
                        [<a href="board.php?res=<?php echo h($post['id']); ?>">Re</a>]
                        <?php if($_SESSION['id']  == $post['member_id']): ?>
                               [<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">delete</a>]
                        <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
</main>

<!-- /.flex-container --></div>

<footer role="contentinfo">
<div class="">
            <ul style="list-style-type: none" class="paging">
                <?php  if($page>1): ?>
                    <li><a href="board-2.php?page=<?php print($page-1); ?>">前のページ</a></li>
                <?php else: ?>
                    <li>前のページ</li>
                <?php endif; ?>
                <?php if($page<$maxPage): ?>
                    <li><a href="board-2.php?page=<?php print($page+1); ?>">次のページ</a></li>
                <?php else: ?>
                    <li>次のページ</li>
                <?php endif; ?>
            </ul>
        </div>
</footer>