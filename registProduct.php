<?php
require('function.php');
$profInfo = getProfInfo($_SESSION['user_id']);
$dbCategory = getCategory();

if(!empty($_POST)){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category_id'];
    $comment = $_POST['comment'];
    $pic = ( !empty($_FILES['pic']['name']) ) ? uploadImg($_FILES['pic'],'pic') : '';
    //画像をPOSTしてない場合はDBから取得し、それを格納
    $pic = ( empty($pic) && !empty($profInfo['pic']) ) ? $profInfo['pic'] : $pic;

    validMaxLen($name, 'name');
    validRequired($name, 'name');
    validNumber($price, 'price');
    validRequired($price, 'price');
    validSelect($category, 'category');
    validMaxLen($comment, 'comment');

    if(empty($err_msg)){
        $dbh = dbConnect();
        $sql = 'INSERT INTO product (name, price, category_id, comment, pic, buyer_id) VALUES(:name, :price, :category_id, :comment, :pic, :buyer_id)';
        $data = array(':name' => $name, ':price' => $price, ':category_id' => $category, ':comment' => $comment, ':pic' => $pic, ':buyer_id' => $profInfo['id']);
        $stmt = queryPost($dbh, $sql, $data);
        if(!empty($stmt)){
            header("Location:mypage.php");
        }
    }

}
?>

<?php
$siteTitle = '商品登録';
require('header.php');
?>
<section class="signForm">
  <div class="container">
      <h1 class="formTitle">商品登録</h1>
      <div class="signFormWrapper">
          <form action="" method="post" enctype="multipart/form-data">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['common'])){ echo $err_msg['common']; }?>
              </div>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['name'])){ echo $err_msg['name']; }?>
              </div>
              <p>商品名</p>
              <input class="inputText" type="text" name="name"  value="<?php echo getFormData('name'); ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['price'])){ echo $err_msg['price']; }?>
              </div>
              <p>金額</p>
              <input class="inputText" type="number" name="price"  value="<?php echo getFormData('price'); ?>">
          </label>

          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['category'])){ echo $err_msg['category']; }?>
              </div>
              <p>カテゴリ</p>
              <select name="category_id" id="">
                  <option value="0">選択してください</option>
                  <?php foreach ($dbCategory as $key => $val) {  ?>
                    <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                  <?php } ?>
              </select>
          </label>


          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['comment'])){ echo $err_msg['comment']; }?>
              </div>
              <p>詳細</p>
              <textarea name="comment" class="inputMemo" id="js-count" cols="30" rows="10"></textarea>
          </label>

          <p>プロフィール画像</p>
          <label class="area-drop" style="height:370px;line-height:370px;">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pic'])){ echo $err_msg['pic']; }?>
              </div>
              ドラッグ&ドロップ
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="pic" class="input-file" style="height:370px;">
              <img src="<?php  echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(getFormData('pic')) echo 'display:none;' ?>">
          </label>


          <div class="submitContainer"><input class="submit" type="submit" value="出品する"></div>
          </form>
      </div>
  </div>
</section>




<?php
require('footer.php');
?>