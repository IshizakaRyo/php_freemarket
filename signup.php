<?php
require('function.php');

if(!empty($_POST)){
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];

    //未入力チェック
    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');

    if(empty($err_msg)){
        // $emailのバリデーション
        validEmail($email, 'email');
        // $passのバリデーション
        validMinLen($pass, 'pass');
        validMaxLen($pass, 'pass');
        // $pass_reの同値チェック
        validMatch($pass, $pass_re, 'pass');
        if(empty($err_msg)){
            try {
              $dbh = dbConnect();
              $sql = 'INSERT INTO users (email, password, login_time, create_date) VALUES( :email, :pass, :login_time, :create_date)';
              $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT), ':login_time' => date('Y-m-d H:i:s'), ':create_date' => date('Y-m-d H:i:s'));
              $stmt = queryPost($dbh, $sql, $data);
              if(!empty($stmt)){
                  $sesLimit = 60*60;
                  $_SESSION['login_date'] = time();
                  $_SESSION['login_limit'] = $sesLimit;
                  // ユーザIDを格納
                  $_SESSION['user_id'] = $dbh->lastInsertId();

                  //マイページへ遷移
                  header("Location:mypage.php");
              }
            } catch (Exception $e) {
                $err_msg['common'] = MSG07;
            }
            
        }
    }
}
?>



<?php
$siteTitle = '新規登録';
require('header.php');
?>

<section class="signForm">
  <div class="container">
      <h1 class="formTitle">新規登録</h1>
      <div class="signFormWrapper">
          <form action="" method="post">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['common'])){ echo $err_msg[''];} ?>
              </div>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['email'])){ echo $err_msg['email'];} ?>
              </div>
              <p>Email</p>
              <input class="inputText" type="text" name="email">
          </label>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pass'])){ echo $err_msg['pass'];} ?>
              </div>
              <p>Pass<span class="inputNotice">※6文字以上</span></p>
              <input class="inputText" type="password" name="pass">
          </label>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pass_re'])){ echo $err_msg['pass_re'];} ?>
              </div>
              <p>Pass-re</p>
              <input class="inputText" type="password" name="pass_re">
          </label>
          
          <div class="submitContainer"><input class="submit" type="submit" value="Sign up"></div>
          </form>
      </div>
  </div>
</section>


<?php
require('footer.php');
