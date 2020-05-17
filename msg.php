<?php
require('function.php');
// 掲示板のidをGETパラメータから取得
$message_id = (!empty($_GET['m_id'])) ? $_GET['m_id'] : '';
// 掲示板のデータ
$bord_info = getBord($message_id);
// 商品のデータ
$product_info = getProductOne($bord_info['id']);
// 自分のアカウンデータ
$user_info = getProfInfo($_SESSION['user_id']);
//　相手のデータ
$partner_info = getProfInfo($bord_info['sale_user']);
// メッセージのデータ
$viewData = getMessage($message_id);

// メッセージの選別
if($bord_info['sale_user'] == $user_info['id']){
    $myMsg = $user_info;
    $partnerMsg = getProfInfo($bord_info['buy_user']);
}else{
    $myMsg = $user_info;
    $partnerMsg = getProfInfo($bord_info['sale_user']);
}

//POSTを変数に格納
$msg =  $_POST['msg'];
if(!empty($_POST)){
    validMaxLen($msg, 'msg');
    validRequired($msg, 'msg');
    if(empty($err_msg)) {
        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO message (bord_id, send_date, from_user, to_user, msg) VALUES(:bord_id, :send_date, :from_user, :to_user, :msg)';
            $data = array(':bord_id' => $message_id, ':send_date' => date('Y-m-d H:i:s'), ':from_user' => $_SESSION['user_id'], 'to_user' => $partner_info['id'], ':msg' => $msg);
            $stmt = queryPost($dbh, $sql, $data);
            if(!empty($stmt)){
                header("Location: " . $_SERVER['PHP_SELF'] .'?m_id='.$message_id);
            }
        } catch (Exception $e) {
            error_log('エラーが発生しました:'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
?>
<?php
$siteTitle = 'メッセージ';
require('header.php');
?>

<main>
    <div class="container">
        <?php echo $viewData[2]['from_user']; ?>
        <section class="bordTop">
            <div class="container">
                <div class="buyerInfo">
                    <p class="buyerInfoP"><?php echo $product_info['name']; ?></p>
                    <p class="buyerInfoP">¥<?php echo $product_info['price']; ?></p>
                    <img class="buyerInfoPic" src="<?php echo $product_info['pic']; ?>">
                </div>
                <div class="buyerInfo">
                    <p class="buyerInfoP"><?php echo $partner_info['name']; ?></p>
                    <p class="buyerInfoP"><?php echo $partner_info['age']; ?></p>
                    <img class="buyerInfoPic" src="<?php echo $partner_info['pic']; ?>">
                </div>
            </div>
        </section>

        <section class="msg-section">
          <div class="msg-wrapeer">
          <?php
            if(!empty($viewData)){
              foreach($viewData as $key => $val){
                  if(!empty($val['from_user']) && $val['from_user'] == $myMsg['id']){
            ?>
                    <div class="msg-container">
                    <span class="triangle-left"></span>
                      <div class="avatar">
                        <img src="<?php echo sanitize($myMsg['pic']); ?>" class="msg-container msg-left-avator">
                      </div>
                      <p class="msg-text msg-text-left">
                        
                        <?php echo sanitize($val['msg']); ?>
                      </p>
                      <div class="msg-time msg-time-left"><?php echo sanitize($val['send_date']); ?></div>
                    </div>
            <?php
                  }else{
            ?>
                    <div class="msg-container">
                    <span class="triangle-right"></span>
                      <div class="avatar">

                        <img src="<?php echo sanitize($partnerMsg['pic']); ?>" alt="" class="msg-container msg-right-avator">
                      </div>
                      <p class="msg-text msg-text-right">
                        <?php echo sanitize($val['msg']); ?>
                      </p>
                      <div class="msg-time msg-time-right"><?php echo sanitize($val['send_date']); ?></div>
                    </div>
            <?php
                  }
                }
              }else{
            ?>
                <p style="text-align:center;line-height:20;">メッセージ投稿はまだありません</p>
            <?php
              }
          ?>      
          <div class="msg-section">
          <form action="" method="post">
            <textarea name="msg" cols="30" rows="3"></textarea>
            <input type="submit" value="送信" class="msg-submit submit-right submit">
          </form>
        </div>
        </section>


    </div>
</main>
<?php
require('footer.php');
?>