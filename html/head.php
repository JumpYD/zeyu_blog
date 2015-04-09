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

$conf = file_get_contents('/etc/zeyu203/techlog.conf');
$conf = unserialize(base64_decode($conf));

$is_root = false;
if (isset($_COOKIE["LoginInfo"])
	&& $_COOKIE["LoginInfo"] == $conf['admin']['logininfo']
)
{
	setcookie('LoginInfo', $conf['admin']['logininfo'], time()+1800);
	$smarty->assign('is_root', true);
	$is_root = true;
}
if (!$is_root)
	require_once ($base_dir.'stats/stats.php');

if ($is_root)
{
	$background = 'images/183755241795a6aac850b8.jpg';
}
else
{
	$background = 'images/17183518883b16614c2fe8.jpg';
}
$smarty->assign('background', $background);
?>
