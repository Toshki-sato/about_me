<?php
            session_start();
            require('dbconnect.php');
            
            if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
                //ログインしている
                $_SESSION['time'] = time();
                 $members = $db->prepare('SELECT * FROM members WHERE id=?');
                 $members->execute(array($_SESSION['id']));
                 $member = $members->fetch();
                 // 1. menuesテーブルの全データを取得
                 $menus = $db->query('SELECT * FROM menus');
                 $menus = $db->query('SELECT * FROM menus')->fetchAll(PDO::FETCH_ASSOC);
            } else{
                //ログインしていない
                header('Location: login.php'); 
               exit();
            }
            
            
            //投稿を記録する
               if(!empty($_POST)) {
                  if($_POST['trainings'] != ''){
                      // $_POST["trainings"]の中身はユーザーが選択した項目のvalueが入った配列になっている
                      // array("1","3", "4"); ←こんな感じ
                      // 方法1. これをforeachでループさせてmenu_idにデータを入れながら1件ずつレコード作成
                        $trainings=$_POST['trainings'];
                        foreach ($trainings as $training){
                            $t = $training;
                            $message = $db->prepare('INSERT INTO records SET user_id=?,menu_id=?,created_at=NOW()');
                            $message->execute(array(
                                   $_SESSION['id'],
                                   $t['menu_id']
                            ))
                            ;
                        }
                        header('Location: menu-2.php'); exit();
                        } 
                
               }    
            //投稿を取得する
            //$posts = $db->prepare('SELECT m.name, m.picture, r.*FROM members m,records r WHERE m.id=r.user_id AND r.user_id=? ORDER BY r.created_at DESC');
            //menuテーブルもつなげる
            $posts = $db->prepare('SELECT m.name, m.picture, r.*, me.name AS menu_name FROM members m,records r, menus me WHERE m.id=r.user_id AND r.user_id=? AND me.id = r.menu_id ORDER BY r.created_at DESC');
            $posts->execute(array($_SESSION['id']));
            
            
            //htmlspecialcharsのショートカット
            function h($value){
                return htmlspecialchars($value, ENT_QUOTES);
            }
         ?>
<head>
        <meta charset="utf-8">
        <title>筋力トレーニング</title>
        <link rel="stylesheet" href="menu-2.css">
    </head>
<header role="banner">
    <div class="logo">
        <a href="board-2.php">
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
            <li>
                <a href="board-2.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"/>
                    </svg>
                    <h4>ホーム</h4>
                </a>
            </li>
            <li>
                <a href="menu-2.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                        <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                    </svg>
                    <h4>トレーニング<br>メニュー</h4>
                </a>
            </li>
            <li>
                <a href="calendar-6.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-calendar-day" viewBox="0 0 16 16">
                        <path d="M4.684 11.523v-2.3h2.261v-.61H4.684V6.801h2.464v-.61H4v5.332h.684zm3.296 0h.676V8.98c0-.554.227-1.007.953-1.007.125 0 .258.004.329.015v-.613a1.806 1.806 0 0 0-.254-.02c-.582 0-.891.32-1.012.567h-.02v-.504H7.98v4.105zm2.805-5.093c0 .238.192.425.43.425a.428.428 0 1 0 0-.855.426.426 0 0 0-.43.43zm.094 5.093h.672V7.418h-.672v4.105z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg>
                    <h4>カレンダー</h4>
                </a>
            </li>
            <li>
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
<h1>トレーニングメニュー</h1>
<div>
              <?php echo h($member['name']); ?>さん、今日の筋トレメニューを選んでください
        </div>
        <form method="post" action="">
            <!-- menusテーブルの中に入っている項目を全てに対してinputの要素を作りたい -->
            <?php foreach($menus as $value): ?>
              <label><input type="checkbox" name="trainings[]" value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></label>
            <?php endforeach; ?>
            <input type="submit" class="submit" value="記録">
        </form>
        
        <?php foreach ($posts as $post): ?>
            <div class="msg">
                <!--ログインしているユーザーが今まで選択してきたメニューのみを全て表示したく、
                以下のように1,2,3のコードを書いてみた。
                1だと最後の６番目のメニューである「体幹トレ」しか表示されず、
                2だと、「体幹トレ」のmenu_idである「６」しか表示されず、
                3の表記だと数字は表示されるが、本当はデータベースにあるmenusのnameを表示したい...-->
                <p>

                    <!--1-->
                    <!--<?php echo h($value['name']); ?>-->
                    <!--2-->
                    <!--<?php echo h($value['id']); ?>-->
                    <!--3-->
                    <!--<?php echo h($post['menu_id']); ?>-->
                    <!--A-->
                    <?php echo h($post['menu_name']); ?>
                </p>
                <p class="day"><?php echo h(date('Y/n/d-G:i',strtotime($post['created_at']))); ?>
                    <?php if($_SESSION['id']  == $post['user_id']): ?>
                           [<a href="delete2.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">delete</a>]
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
</main>
<!-- /.flex-container --></div>