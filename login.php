<?php
require('function.php');

if(!empty($_POST)){
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_save = (!empty($_POST['pass_save'])) ? true : false;
  //未入力チェック
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validEmail($email, 'email');
  validMinLen($pass, 'pass');
  validMaxLen($pass, 'pass');

  if(empty($err_msg)){
    try {
      $dbh = dbConnect();
      $sql = 'SELECT password,id  FROM users WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);
      //クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      // パスワード照合
      if(!empty($result) && password_verify($pass, array_shift($result))){

        $sesLimit = 60*60;
        $_SESSION['login_date'] = time();
        if($pass_save){
            $_SESSION['login_limit'] = $sesLimit*24*3;  //ログインリミットは3日間 
        }else{
            $_SESSION['login_limit'] = $sesLimit;  //そうでなければ通常通り１時間           
        }
        $_SESSION['login_limit'] = $sesLimit;
        $_SESSION['user_id'] = $result['id'];
        header("location:mypage.php");
      }else{
        $err_msg['common'] = MSG09;
      }
    } catch (Exception $e) {
        $err_msg = MSG07;
    }
  }
 
}
?>

<?php
$siteTitle = 'ログイン';
require('./header.php');
?>

<section class="signForm">
  <div class="container">
      <h1 class="formTitle">ログイン</h1>
      <div class="signFormWrapper">
          <form action="" method="post">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['common'])){ echo $err_msg['common']; }?>
              </div>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['email'])){ echo $err_msg['email']; }?>
              </div>
              <p>Email</p>
              <input class="inputText" type="text" name="email">
          </label>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pass'])){ echo $err_msg['pass']; }?>
              </div>
              <p>Pass</p>
              <input class="inputText" type="password" name="pass">
          </label>
          <input type="checkbox"　name="pass_save"><span class="checkboxText">ログイン状態を保持する</span>
          <div class="submitContainer"><input class="submit" type="submit" value="Login"></div>
          </form>
      </div>
  </div>
</section>


<?php
require('./footer.php');
?>