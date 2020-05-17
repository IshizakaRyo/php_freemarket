<?php
require('function.php');

//DBからプロフィール情報を取得
$dbFormData = getProfInfo($_SESSION['user_id']);

if(!empty($_POST)) {
    //POSTの値を変数に格納
    $email = $_POST['email'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $zip = (!empty($_POST['zip'])) ? $_POST['zip'] : 0;
    $addr = $_POST['addr'];
    $tel = $_POST['tel'];
    $pic = ( !empty($_FILES['pic']['name']) ) ? uploadImg($_FILES['pic'],'pic') : '';
    //画像をPOSTしてない場合はDBから取得し、それを格納
    $pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;

    //emailのバリデーション
    if($dbFormData['email'] !== $email){
        validEmail($email, 'email');
    }
    //名前のバリデーション
    if($dbFormData['name'] !== $name){
        validMaxLen($name, 'name');
    }
    //年齢のバリデーション
    if( (int)$dbFormData['age'] !== $age){
        validNumber($age, 'age');
    }
    //郵便番号のバリデーション
    if($dbFormData['zip'] !== $zip){
        validZip($zip, 'zip');
    }
    //住所のバリデーション
    if($dbFormData['addr'] !== $addr){
        validMaxLen($addr, 'addr');
    }
    //電話番号のバリデーション
    if($dbFormData['tel'] !== $tel){
        validTel($tel, 'tel');
    }

    if(empty($err_msg)){
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET name = :name, email = :email, age = :age, zip = :zip, addr = :addr, tel = :tel, pic = :pic WHERE id = :user_id';
            $data = array(':name' => $name, ':email' => $email, ':age' => $age, 'zip' => $zip, ':addr' => $addr, ':tel' => $tel, 'pic' => $pic ,':user_id' => $_SESSION['user_id']);
            $stmt = queryPost($dbh, $sql, $data);
            
            if($stmt){
                header("Location:mypage.php");
            }
        } catch (Exception $e) {
            error_log('エラー発生:'.$e->getMessage());
        }
    }
}




// emailのバリデーション
if($dbFormData['email'] !== $email){
    validEmail($email, 'email');
}
// 名前のバリデーション
if($dbFormData['name'] !== $name){
    validMaxLen($name, 'name');
}
// 年齢のバリデーション
?>

<?php
$siteTitle = 'プロフィール';
require('header.php');
?>
<section class="signForm">
  <div class="container">
      <h1 class="formTitle">プロフィール</h1>
      <div class="signFormWrapper">
          <form action="" method="post" enctype="multipart/form-data">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['common'])){ echo $err_msg['common']; }?>
              </div>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['email'])){ echo $err_msg['email']; }?>
              </div>
              <p>Email</p>
              <input class="inputText" type="text" name="email" value="<?php echo $dbFormData['email'] ?>">
          </label>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['name'])){ echo $err_msg['name']; }?>
              </div>
              <p>名前</p>
              <input class="inputText" type="text" name="name" value="<?php echo $dbFormData['name'] ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['age'])){ echo $err_msg['age']; }?>
              </div>
              <p>年齢</p>
              <input class="inputText inputAge" type="number" name="age" value="<?php echo $dbFormData['age'] ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['zip'])){ echo $err_msg['zip']; }?>
              </div>
              <p>郵便番号<span class="inputMemo">※ハイフンなし</span></p>
              <input class="inputText" type="text" name="zip" value="<?php echo $dbFormData['zip'] ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['addr'])){ echo $err_msg['addr']; }?>
              </div>
              <p>住所</p>
              <input class="inputText" type="text" name="addr" value="<?php echo $dbFormData['addr'] ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['tel'])){ echo $err_msg['tel']; }?>
              </div>
              <p>tel</p>
              <input class="inputText" type="text" name="tel" value="<?php echo $dbFormData['tel'] ?>">
          </label>

          <p>プロフィール画像</p>
          <label class="area-drop" style="height:370px;line-height:370px;">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pic'])){ echo $err_msg['pic']; }?>
              </div>
              ドラッグ&ドロップ
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="pic" class="input-file" style="height:370px;">
              <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img">
          </label>


          <div class="submitContainer"><input class="submit" type="submit" value="更新する"></div>
          </form>
      </div>
  </div>
</section>






<?php
require('footer.php');
?>