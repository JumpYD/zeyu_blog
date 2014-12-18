<?php
ini_set('date.timezone','Asia/Shanghai');
$base_dir = dirname(__FILE__).'/../';

require_once ($base_dir.'resource/smarty/libs/Smarty.class.php');
require_once ($base_dir.'library/zeyublog.php');
require_once ($base_dir.'stats/stats.php');

$smarty = new Smarty;

$smarty->cache_dir			=	$base_dir.'resource/smarty/cache';
$smarty->config_dir			=	$base_dir.'resource/smarty/config';
$smarty->compile_dir		=	$base_dir.'resource/smarty/compile';
$smarty->template_dir		=	$base_dir.'views';
$smarty->left_delimiter		=	"<{"; 
$smarty->right_delimiter	=	"}>";

$is_root = false;
if (isset($_COOKIE["LogInfo"])
	&& $_COOKIE["LogInfo"] == 'admin519ca7b3591e6844af3c875cb61d0d64'
)
{
	setcookie('LogInfo', 'admin519ca7b3591e6844af3c875cb61d0d64', time()+1800);
	$smarty->assign('is_root', true);
	$is_root = true;
}

#$query =
#	'select image_id,path from images'
#	.' where category="background" and path!="images/background.jpg"';
#$backgrounds = MySqlOpt::select_query($query);
#$idx = rand(0, count($backgrounds)-1);
if ($is_root)
{
	$background = 'images/17183518883b16614c2fe8.jpg';
}
else
{
	$background = 'images/d8b6da32f844f3d07af619b26fad1e91.jpg';
}
$smarty->assign('background', $background);
?>
