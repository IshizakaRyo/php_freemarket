<?php
require('function.php');
$product_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$dbFormData = getProductOne($product_id);
$dbCategory = getCategory();
?>


<?php
$siteTitle = '商品編集';
require('header.php');
?>
<section class="signForm">
  <div class="container">
      <h1 class="formTitle">商品編集</h1>
      <div class="signFormWrapper">
          <form action="" method="post" enctype="multipart/form-data">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['common'])){ echo $err_msg['common']; }?>
              </div>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['name'])){ echo $err_msg['name']; }?>
              </div>
        <?php foreach ($dbFormData as $key => $val): ?>
          <label>
              <p>商品名</p>
              <input class="inputText" type="text" name="name"  value="<?php echo $val['name']; ?>">
          </label>
    
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['price'])){ echo $err_msg['price']; }?>
              </div>
              <p>金額</p>
              <input class="inputText" type="number" name="price"  value="<?php echo $val['price']; ?>">
          </label>
        <?php endforeach;?>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['category'])){ echo $err_msg['category']; }?>
              </div>
              <p>カテゴリ</p>
              <select name="category_id" id="">
                  <option value="0">選択してください</option>
                  <?php foreach ($dbCategory as $key => $val) {  ?>
                    <option value="<?php echo $val['id']; ?>" <?php if(getFormData('category_id',true) == $val['id'] ){ echo 'selected'; } ?> ><?php echo $val['name']; ?></option>
                  <?php } ?>
              </select>
          </label>

        <?php foreach ($dbFormData as $key => $val): ?>
          <label>
              <div class="areaMsg">
                  <?php if(!empty($err_msg['comment'])){ echo $err_msg['comment']; }?>
              </div>
              <p>詳細</p>
              <textarea name="comment" class="inputMemo" id="js-count" cols="30" rows="10"><?php echo $val['comment']; ?></textarea>
          </label>

          <p>プロフィール画像</p>
          <label class="area-drop" style="height:370px;line-height:370px;">
              <div class="areaMsg">
                  <?php if(!empty($err_msg['pic'])){ echo $err_msg['pic']; }?>
              </div>
              ドラッグ&ドロップ
              <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
              <input type="file" name="pic" class="input-file" style="height:370px;">
              <img src="<?php echo $val['pic']; ?>" alt="" class="prev-img">
          </label>
        <?php endforeach;?>
          <div class="submitContainer"><input class="submit" type="submit" value="出品する"></div>
          </form>
      </div>
  </div>
</section>



<?php
require('footer.php');
?>