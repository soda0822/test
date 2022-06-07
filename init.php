<? php
date_default_timezone_set('Asia/Shanghai');
session_set_cookie_params(0,null,null,null,true);
mb_internal_encoding('UTF-8');
require './common/function.php';
session_start();
if(!isset($_SESSION['fun'])){
	$_SESSION=['fun'=>[]];
}

require './common/library/Db.php';