<?php
require_once (dirname(__FILE__).'/'.'head.php');

$smarty->assign('category_label','index');
$smarty->assign('nav','true');
$smarty->display('index.tpl');
?>
