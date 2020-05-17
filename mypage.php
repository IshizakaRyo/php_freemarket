<?php

require('function.php');
$profInfo = getProfInfo($_SESSION['user_id']);
$sellProduct = getSellProducts($profInfo['id']);
$buyProductId = getBuyProductId($profInfo['id']);
?>
<?php
$siteTitle = 'マイページ';
require('header.php');
?>

<main>
    <div class="container flexContainer">
        <section class="sidebar">
            <div class="container">
                <ul>
                  <li><a href="registProduct.php">出品する</a></li>
                  <li><a href="myInfo.php">プロフィール</a></li>
                  <li><a href="transaction.php">取引一覧</a></li>
                  <li><a href="withdrawl.php">退会</a></li>
            </ul>
        </section>

        <section class="mypageBlock"> 
            <div class="container">
                <!-------------------- 購入商品一覧 -------------------->
                <h2 class="blockTitle">購入商品一覧</h2>
                <div class="stockWrapper">
                <?php foreach ($buyProductId as $key => $val): ?>
                  <?php $buyProduct = getBuyProducts($val['product_id']); ?>
                    <?php for ($i=0; $i < count($buyProduct); $i++) { ?>
                      <div class="item">
                        <a href="msg.php">
                          <img src="<?php echo $buyProduct[$i]['pic'];?>">
                          <p><?php echo $buyProduct[$i]['name']; ?></p>
                          <p><?php echo $buyProduct[$i]['price']; ?></p>
                        </a>
                      </div>
                    <?php } ?>
                  <?php endforeach; ?>
                    <div class="item">
                    </div>
                    <div class="item">
                    </div>
                  </div>
                <!-------------------- 出品商品一覧 -------------------->
                <h2 class="blockTitle">出品商品一覧</h2>
                <div class="stockWrapper">
                    <?php foreach ($sellProduct as $key => $val) : ?>
                      <div class="item">
                        <a href="editProduct.php?p_id=<?php echo $val['id']; ?>">
                          <img src="<?php echo $val['pic']; ?>">
                          <p><?php echo $val['name']; ?></p>
                          <p>¥<?php echo $val['price']; ?></p>
                        </a>
                      </div>   
                    <?php endforeach; ?>    
                    <div class="item">
                    </div>                       
                    <div class="item">
                    </div> 
        

                <!-------------------- 取引一覧 -------------------->
                <section>
                <h2 class="blockTitle">取引一覧</h2>
                <div class="msgWrapper">
                    <div class="msgBox">
                        <table>
                            <tr>
                                <th>送信日時</th><th>取引相手</th><th>メッセージ</th>
                            </tr>
                            <tr>
                                <td>2020.4.9</td><td>田中太郎</td><td><a href="#">こんにちは!初めて取引させていただきます、 田中です。この度は...</a></td>
                            </tr>
                            <tr>
                                <td>2020.4.9</td><td>田中太郎</td><td><a href="#">こんにちは!初めて取引させていただきます、 田中です。この度は...</a></td>
                            </tr>
                            <tr>
                                <td>2020.4.9</td><td>田中太郎</td><td><a href="#">こんにちは!初めて取引させていただきます、 田中です。この度は...</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
                </section>

                 <!-------------------- お気に入り -------------------->
                <h2 class="blockTitle">お気に入り</h2>
                <div class="stockWrapper">
                    <div class="item">
                        <a href="msg.php">
                          <img src="./images/desk-ausu.jpg">
                          <p>ASUS パソコン<span>PC</span></p>
                          <p>¥100,000</p>
                        </a>
                    </div>

                    <div class="item">
                        <a href="msg.php">
                          <img src="./images/desk-lenovo.png">
                          <p>ASUS パソコン<span>PC</span></p>
                          <p>¥100,000</p>
                        </a>
                    </div>
 
                    <div class="item">
                        <a href="msg.php">
                          <img src="./images/desk-imac.jpeg">
                          <p>ASUS パソコン<span>PC</span></p>
                          <p>¥100,000</p>
                        </a>
                    </div>

                    <div class="item">
                        <a href="msg.php">
                          <img src="./images/pc-asus.jpg">
                          <p>ASUS パソコン<span>PC</span></p>
                          <p>¥100,000</p>
                        </a>
                    </div>

                    <div class="item">
                        <a href="msg.php">
                          <img src="./images/headfon-black.jpg">
                          <p>ASUS パソコン<span>PC</span></p>
                          <p>¥100,000</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
    </div>

</main>



<?php
require('footer.php');