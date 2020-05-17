<?php
require('function.php');
// 商品のidをGETパラメータから取得
$product_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
// 商品のデータ
$product_info = getProductOne($product_id);
// 自分のアカウンデータ
$dbFormData = getProfInfo($_SESSION['user_id']);

// パラメータに不正な値が入っているかチェック
if(empty($product_id)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:top.php"); 
  }

if($_POST['submit']){
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO bord (product_id, sale_user, buy_user, create_date) VALUES (:p_id, :s_uid, :b_uid, :date)';
        $data = array(':p_id' => $product_id, ':s_uid' => $product_info['buyer_id'], ':b_uid' => $dbFormData['id'], ':date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            $_SESSION['msg_success'] = SUC05;
            header("Location:msg.php?m_id=".$dbh->lastInsertID()); //連絡掲示板へ
        }   
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}
?>
<?php
$siteTitle = '商品詳細';
require('header.php');
?>
<main>
    <div class="container">
        <h1 class="productName"><?php echo $product_info['name']; ?><span><?php echo $product_info['category']; ?></span></h1>
        
        <p class="productPrice">¥<?php echo $product_info['price']; ?></p>
        <div class="imgWrapper">
            <img class="productImgMain" id="js-switch-img-main" src="<?php echo $product_info['pic']; ?>">
            <div class="subImgWrapper">
                <img class="productImgSub js-switch-img-sub"  src="<?php echo $product_info['pic']; ?>">     
                <img class="productImgSub js-switch-img-sub"  src="<?php echo $product_info['pic']; ?>">   
                <img class="productImgSub js-switch-img-sub"  src="<?php echo $product_info['pic']; ?>">     
            </div>
        </div>
        <section class="comment"> 
            <div class="container"><?php echo $product_info['comment']; ?></div>
        </section>
        <section class="productButton"> 
            <div class="container">
                <a href="top.php<?php echo appendGetParam(array('p_id')); ?>" class="buttonBack">&lt;一覧に戻る</a>
                <form method="post" action="">
                    <input type="submit" class="buttonBuy" name="submit" value="買う">
                </form>
            </div>
        </section>
    </div>
</main>





<?php
require('footer.php');
?>