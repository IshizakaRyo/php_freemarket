<?php

require('function.php');
$profInfo = getProfInfo($_SESSION['user_id']);

if(!empty($_POST)) {
    $dbh = dbConnect();
    $sql = 'UPDATE users SET  delete_flg = 1 WHERE id = :us_id';
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    if(!empty($stmt)){
        header("Location:index.html");
    }
}

?>
<?php
$siteTitle = '退会';
require('header.php');
?>

<section class="signForm">
  <div class="container">
      <h1 class="formTitle">退会</h1>
      <div class="signFormWrapper">
          <h1 class="withdNote">本当に退会しますか？</h1>
          <form action="" method="post">
          <div class="withdlButtonCon"><input class="withdrawlButton" type="submit" value="退会する"></div>
          </form>
      </div>
  </div>
</section>
<?php
require('footer.php');
?>
