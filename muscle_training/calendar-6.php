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
    
            //投稿を取得する
            $posts_data = $db->prepare(
                    "SELECT r.*, me.name AS menu_name
                     FROM members m,records r, menus me
                     WHERE m.id=r.user_id AND r.user_id=?
                     AND me.id = r.menu_id
                     AND r.created_at >= date_format(now(), '%Y-%m-01 00:00:00')
                     AND r.created_at < adddate(date_format(now(), '%Y-%m-01 00:00:00'), interval 1 month) 
                     ORDER BY r.created_at DESC;"
                 );
            $posts_data->execute(array($_SESSION['id']));
            $posts =$posts_data->fetchAll(PDO::FETCH_ASSOC);
            $formatted_posts = [];
            // 指定したフォーマットで出力
                foreach ($posts as $post){
                    $date = date('j',strtotime($post['created_at']));
                    $formatted_posts[$date][] = $post['menu_name'];
                }
                //echo '<pre>';
                //var_dump($formatted_posts);
                //echo "</pre>";exit();
            
            
            function h($value){
                return htmlspecialchars($value, ENT_QUOTES);
            }
            
         ?>
         <?php
     
    // 現在の年月を取得
    $year = date('Y');
    $month = date('n');
     
    // 月末日を取得
    $last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
     
    $calendar = array();
    $j = 0;
     
    // 月末日までループ
    for ($i = 1; $i < $last_day + 1; $i++) {
     
    // 曜日を取得
    $week = date('w', mktime(0, 0, 0, $month, $i, $year));
     
    // 1日の場合
    if ($i == 1) {
     
        // 1日目の曜日までをループ
        for ($s = 1; $s <= $week; $s++) {
     
            // 前半に空文字をセット
            $calendar[$j]['day'] = '';
            $j++;
     
        }
     
    }
     
    // 配列に日付をセット
    $calendar[$j]['day'] = $i;
    $j++;
     
    // 月末日の場合
    if ($i == $last_day) {
     
        // 月末日から残りをループ
        for ($e = 1; $e <= 6 - $week; $e++) {
     
            // 後半に空文字をセット
            $calendar[$j]['day'] = '';
            $j++;
     
        }
     
    }
     
    }
     
    ?>
<head>
        <meta charset="utf-8">
        <title>筋力トレーニング</title>
        <link rel="stylesheet" href="calendar-2.css">
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
<h1>カレンダー</h1>
<?php echo $year; ?>年<?php echo $month; ?>月のカレンダー
    <table>
    <tr>
        <th>日</th>
        <th>月</th>
        <th>火</th>
        <th>水</th>
        <th>木</th>
        <th>金</th>
        <th>土</th>
    </tr>
     
    <tr>
    <?php $cnt = 0; ?>
    <?php //echo '<pre>'; print_r($calendar); echo '</pre>';  ?>
    <?php //$array = array_merge_recursive($formatted_posts,$calendar);?>
    <?php //echo '<pre>'; print_r($array); echo '</pre>';  ?>
    <?php foreach ($calendar as $key => $value): ?>
    
        <td>
        <?php $cnt++; ?>
        <?php echo $value['day']; ?>
        
        <?php
            if(false && $value['day'] != 0 && $formatted_posts[$value['day']] != null){
                echo '<div style="text-align:left;">';
                echo '<pre>';
                echo '----------$formatted_posts----------<br>';
                var_dump($formatted_posts );
                echo '<hr>';
                echo '----------$value["day"]----------<br>';
                var_dump($value['day'] );
                echo '<hr>';
                echo '----------$formatted_posts[$value["day"]]----------<br>';
                var_dump($formatted_posts[$value['day']] );
                echo '<hr>';
                echo "</pre>";
                echo '</div>';exit;
            }
        ?>
        <?php foreach($formatted_posts[$value['day']] as $menu): ?>
            <br>
            <span><?= $menu ?></span>
        <?php endforeach;?>
        
        
        <?php if($value['day'] == key($formatted_posts)): ?>
        <?php //var_dump($formatted_posts, $value['day']);?>
        <?php endif; ?>
        </td>
     
    <?php if ($cnt == 7): ?>
    </tr>
    <tr>
    <?php $cnt = 0; ?>
    <?php endif; ?>
     
    <?php endforeach; ?>
    </tr>
    
    </table>
</main>