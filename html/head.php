<?php
ini_set('date.timezone','Asia/Shanghai');
$base_dir = dirname(__FILE__).'/../';

require_once ($base_dir.'resource/smarty/libs/Smarty.class.php');
require_once ($base_dir.'library/zeyublog.php');

$smarty = new Smarty;

$smarty->cache_dir			=	$base_dir.'resource/smarty/cache';
$smarty->config_dir			=	$base_dir.'resource/smarty/config';
$smarty->compile_dir		=	$base_dir.'resource/smarty/compile';
$smarty->template_dir		=	$base_dir.'views';
$smarty->left_delimiter		=	"<{"; 
$smarty->right_delimiter	=	"}>";

$query = 'select image_id,path from images where category="background" and path!="images/background.jpg"';
$backgrounds = MySqlOpt::select_query($query);
$idx = rand(0, count($backgrounds)-1);
if ($_SERVER["REMOTE_ADDR"] == '192.168.72.1' || !empty($_GET['noback']))
	$backgrounds[$idx]['path'] = 'images/background.jpg';
$smarty->assign('background', $backgrounds[$idx]['path']);
?>
