<?php
//==================== ログ関連 ====================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}




//==================== セッション関連 ====================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
// セッションの有効期限
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();


//==================== エラーメッセージを定義 ====================
$err_msg = array();
define('MSG01', '未入力です。');
define('MSG02', 'Emailの形式でご入力ください。');
define('MSG03', 'パスワードが一致していません。');
define('MSG05', '文字数が足りていません。');
define('MSG06', '最大文字数を超えています。255文字以下でご入力ください。');
define('MSG07', 'エラーが発生しました。しばらく経ってからお試しください。');
define('MSG09', 'emailかpasswordに問題があります。');
define('MSG10', '電話番号の形式ではありません。');
define('MSG11', '郵便番号の形式ではありません。');
define('MSG15', '正しくありません。');
define('MSG17', '半角英数字でご入力ください。');
define('SUC05', '商品を購入しました。');


//==================== DB接続関数 ====================
function dbConnect(){
    $dsn = 'mysql:dbname=gadget;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        // SQL実行失敗時にはエラーコードのみ設定
        PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
        // デフォルトフェッチモードを連想配列形式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う（一度に結果セットを全て取得し、サーバー負荷を軽減）
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // PDOオブジェクト生成（DB接続）
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}
function queryPost($dbh, $sql, $data){
    // クエリー作成
    $stmt = $dbh->prepare($sql);
    // プレースホルダに値をセットし、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL：'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }
    return $stmt;
}


//==================== バリデーション関数一覧 ====================
// バリデーション関数(未入力チェック)
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}
// バリデーション関数(Email形式チェック)
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
// バリデーション関数（同値チェック）
function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}
// バリデーションチェック（最大文字チェック）
function validMaxLen($str, $key, $max = 255){
    if(mb_strlen($str) > $max){
        global $err_msg;
        $err_msg[$key] = MSG06;
    }
}
// バリデーションチェック（最小文字チェック）
function validMinLen($str, $key, $min = 6){
    if(mb_strlen($str) < $min){
        global $err_msg;
        $err_msg[$key] = MSG05;
    }
}
// selectBoxチェック
function validSelect($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG15;
    }
}
//半角数字チェック
function validNumber($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
      global $err_msg;
      $err_msg[$key] = MSG17;
    }
}
//郵便番号形式チェック
function validZip($str, $key){
    if(!preg_match("/^\d{7}$/", $str)){
      global $err_msg;
      $err_msg[$key] = MSG11;
    }
}
//電話番号形式チェック
function validTel($str, $key){
    if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
      global $err_msg;
      $err_msg[$key] = MSG10;
    }
}

//==================== データ取得関連 ====================
//指定のプロフィールを取得
function getProfInfo($user_id){
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :user_id AND delete_flg = 0';
        $data = array('user_id' => $user_id);
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
//カテゴリー一覧取得
function getCategory(){
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM category WHERE delete_flg = 0';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt) {
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
// 出品商品をを全て取得
function getSellProducts($buyUser) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM product WHERE buyer_id = :user AND delete_flg = 0';
        $data = array(':user' => $buyUser);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt) {
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
// 購入商品のIDを取得
function getBuyProductId($buyUser) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT product_id FROM bord WHERE buy_user = :user AND delete_flg = 0';
        $data = array(':user' => $buyUser);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt) {
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
// 購入商品をIDから取得
function getBuyProducts($p_id) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM product WHERE id = :p_id AND delete_flg = 0';
        $data = array(':p_id' => $p_id);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt) {
            return $stmt->fetchAll();
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
//一つだけ商品を取り出す
function getProductOne($p_id) {
    try {
        $dbh = dbConnect();
        $sql =  'SELECT p.id , p.name , p.comment, p.price, p.category_id ,p.pic ,p.buyer_id, p.create_date, p.update_date, c.name AS category 
        FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
        $data = array(':p_id' => $p_id);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            return false;
        }
    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }
}
// 商品情報の数を全て取得
function getProNumAll($currentMinNum = 1 , $span = 20, $category, $sort) {
    try {
        $dbh = dbConnect();
        $sql = 'SELECT id FROM product WHERE delete_flg = 0';
        if(!empty($category)) $sql .= ' AND category_id = '.$category;
        
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $rst['total'] = $stmt->rowCount(); //総商品数
        $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
        if(!$stmt) {
            return false;
        }

        // １ページに必要な文だけ取得
        $sql = 'SELECT * FROM product';
        if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
        if(!empty($sort)){
          switch($sort){
            case 1:
              $sql .= ' ORDER BY price ASC';
              break;
            case 2:
              $sql .= ' ORDER BY price DESC';
              break;
          }
        } 
        $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt) {
            $rst['data'] = $stmt->fetchAll();
            return $rst;
        }

    } catch (Exception $e) {
        error_log('エラ-発生:'.$e->getMessage());
    }   
}
function getBord($id){
    debug('msg情報を取得します。');
    debug('掲示板ID：'.$id);
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT * FROM bord WHERE id = :bord_id AND delete_flg = 0';
      $data = array(':bord_id' => $id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
  
      if($stmt){
        // クエリ結果の全データを返却
        return $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
}
// メッセージ情報の取得
function getMessage($msg_id){
    try {
        $dbh = dbConnect();
        $sql = 'SELECT * FROM message WHERE bord_id = :bord_id';
        $data = array(':bord_id' => $msg_id);
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            return $stmt->fetchAll();
          }else{
            return false;
          }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
}

  
//==================== その他 ====================
//画像アップロード関数
function uploadImg($file, $key){
    if (isset($file['error']) && is_int($file['error'])) {
        try {

            // ファイルのバリデーション
            switch ($file['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
                    throw new RuntimeException('ファイルが選択されていません');
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
                case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default: // その他の場合
                    throw new RuntimeException('その他のエラーが発生しました');
            }
            //マイムタイプのチェック
            $type = @exif_imagetype($file['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { 
                throw new RuntimeException('画像形式が未対応です');
            }
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
                if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
                    throw new RuntimeException('ファイル保存時にエラーが発生しました');
                }
            // 保存したファイルパスのパーミッション（権限）を変更する
            chmod($path, 0644);
            return $path;

        } catch (RuntimeException $e) {
            global $err_msg;
            $err_msg[$key] = $e->getMessage();

        }
    }
}
// フォーム入力保持
function getFormData($str, $flg = false){
    if($flg){
        $method = $_GET;
    }else{
        $method = $_POST;
    }
    global $dbFormData;
    global $err_msg;
    // ユーザデータがある場合
    if(!empty($dbFormData)){
        // フォームのエラーがある場合
        if(!empty($err_msg[$str])){
            // POSTにデータがある場合
            if(isset($method[$str])){
                return sanitize($method[$str]);
            }else{
                // ない場合（基本ありえない）はDBの情報を表示
                return sanitize($dbFormData[$str]);
            }
        }else{
            // POSTにデータがあり、DBの情報と違う場合
            if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);
            }
        }
    }else{
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}

// サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}  
// ページネーション
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
    // 現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク４個出す
    if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
      $minPageNum = $currentPageNum - 4;
      $maxPageNum = $currentPageNum;
    // 現在のページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
    }elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum > $pageColNum){
      $minPageNum = $currentPageNum - 3;
      $maxPageNum = $currentPageNum + 1;
    // 現ページが2の場合は左にリンク１個、右にリンク３個だす。
    }elseif( $currentPageNum == 2 && $totalPageNum > $pageColNum){
      $minPageNum = $currentPageNum - 1;
      $maxPageNum = $currentPageNum + 3;
    // 現ページが1の場合は左に何も出さない。右に５個出す。
    }elseif( $currentPageNum == 1 && $totalPageNum > $pageColNum){
      $minPageNum = $currentPageNum;
      $maxPageNum = 5;
    // 総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
    }elseif($totalPageNum < $pageColNum){
      $minPageNum = 1;
      $maxPageNum = $totalPageNum;
    // それ以外は左に２個出す。
    }else{
      $minPageNum = $currentPageNum - 2;
      $maxPageNum = $currentPageNum + 2;
    }
    
    echo '<div class="pagination">';
      echo '<ul>';
        if($currentPageNum != 1){
          echo '<li><a href="?p=1'.$link.'">&lt;</a></li>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
          echo '<li class="list-item ';
          if($currentPageNum == $i ){ echo 'active'; }
          echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
        }
        if($currentPageNum != $maxPageNum && $maxPageNum > 1){
          echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
        }
      echo '</ul>';
    echo '</div>';
  }


  function appendGetParam($arr_del_key = array()){
    if(!empty($_GET)){
      $str = '?';
      foreach($_GET as $key => $val){
        if(!in_array($key,$arr_del_key,true)){ //取り除きたいパラメータじゃない場合にurlにくっつけるパラメータを生成
          $str .= $key.'='.$val.'&';
        }
      }
      $str = mb_substr($str, 0, -1, "UTF-8");
      return $str;
    }
  }