<?php
$base_dir = dirname(__FILE__).'/../';

require_once ($base_dir."resource/smarty/libs/Smarty.class.php");
$smarty = new Smarty;

$smarty->cache_dir			=	$base_dir.'resource/smarty/cache';
$smarty->config_dir			=	$base_dir.'resource/smarty/config';
$smarty->compile_dir		=	$base_dir.'resource/smarty/compile';
$smarty->template_dir		=	$base_dir.'views';
$smarty->left_delimiter		=	"<{"; 
$smarty->right_delimiter	=	"}>";

$smarty->display('experiment.tpl');
?>
