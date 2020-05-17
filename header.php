<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Gadget Shop |　<?php echo $siteTitle; ?></title>
</head>
<body>
    <header>
        <div class="container">
          <a class="headerLogo" href="top.php">Gadget Shop</a>
          <div class="headerLeft">
            <?php if(empty($_SESSION['user_id'])){ ?>
            <a href="login.php">ログイン</a>
            <a href="mypage.php">マイページ</a>
            <?php }else{ ?>
              <a href="index.html">ログアウト</a>
            <a href="mypage.php">マイページ</a>   
            <?php } ?>
          </div>
        </div>
    </header>