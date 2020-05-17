<?php
require('function.php');
$profInfo = getProfInfo($_SESSION['user_id']);
$dbCategory = getCategory();
//現在のページ(パラメータに値が入っていたらそれ、出なければ1)
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;

// カテゴリー順番
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// 金額順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

// 表示件数
$listSpan = 20;
// 現在の表示レコード先頭
$currentMinNum = (($currentPageNum-1)*$listSpan);
// 表示する商品情報を取得
$dbProductData = getProNumAll($currentMinNum,$listSpan,$category,$sort,$sort_t);
?>
<?php
$siteTitle = 'マイページ';
require('header.php');
?>

<main>
    <div class="container flexContainer">

        <!-------------------- サイドバー -------------------->
        <section class="sidebar sideForm">
            <div class="container">
                <form action="" method="get">
                  <label class="sidebarForm">
                    <p>カテゴリー</p>
                      <div class="sidebarFormItem">
                        <select name="c_id">
                          <option value="0" <?php if(getFormData('c_id',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
                          <?php foreach ($dbCategory as $key => $val) {  ?>
                            <option value="<?php echo $val['id']; ?>" <?php if(getFormData('c_id',true) == $val['id'] ){ echo 'selected'; } ?> ><?php echo $val['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                  </label>
                  <label class="sidebarForm">
                    <p>金額</p>
                      <div class="sidebarFormItem">
                        <select name="sort">
                          <option value="0" <?php if(getFormData('sort',true) == 0 ){ echo 'selected'; } ?> >選択してください</option>
                          <option value="1" <?php if(getFormData('sort', true) == 1 ){ echo 'selected'; } ?> >金額が低い順</option>
                          <option value="2" <?php if(getFormData('sort', true) == 2 ){ echo 'selected'; } ?> >金額が高い順</option>
                        </select>
                      </div>
                  </label>
                   <input type="submit" value="検索する" class="submitCenter">

                </form>
            </div>
        </section>

        <section class="mypageBlock"> 
            <div class="container">
                 <div class="resultBar">
                     <p><?php echo sanitize($dbProductData['total']); ?>件の商品結果が見つかりました<span class="resultBarNum"><?php echo $currentMinNum+1; ?>-<?php echo $currentMinNum+count($dbProductData['data']); ?>件 / <?php echo sanitize($dbProductData['total']); ?>件中</span></p>
                 </div>
                 <!-------------------- 検索結果一覧 -------------------->
                <div class="stockWrapper resultWrapper">   
                    <?php foreach ($dbProductData['data'] as $key => $val) : ?>
                        <div class="item">
                        <a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>">
                          <img src="<?php echo $val['pic']; ?>">
                          <p><?php echo $val['name']; ?><span>PC</span></p>
                          <p>¥<?php echo $val['price']; ?></p>
                        </a>
                        </div>  
                    <?php endforeach; ?>
                    <div class="item">
                    </div>  
                    <div class="item">
                    </div>  
                </div>

                <!-------------------- ページネーション -------------------->
                <?php pagination($currentPageNum, $dbProductData['total_page']); ?>
            </div>
        </section>
    </div>

</main>



<?php
require('footer.php');