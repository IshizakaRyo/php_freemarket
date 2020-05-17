<?php
require('function.php');

// セッションを削除
session_destroy();
// ログイン画面へ
header("Location:login.php");
