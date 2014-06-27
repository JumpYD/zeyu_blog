<?php
// 图片大小: 400*345
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;
LogOpt::init('display_earnings', true);

$query = 'select * from earnings order by month desc';
$earnings = MySqlOpt::select_query($query);
$earn_infos = array();
$month = array();
$income = array();
$expend = array();
foreach ($earnings as $earning)
{
	$infos = array();
	$infos['idx_href'] = 'article.php?id='.$earning['article_id'];
	$image_select_query = 'select path from images where image_id='.$earning['image_id'];
	$image_ret = MySqlOpt::select_query($image_select_query);
	$path = $image_ret[0]['path'];
	$infos['image_path'] = $path;
	$infos['title'] = $earning['month'];
	$infos['descs'] = '结余:&nbsp;&nbsp;'.($earning['income']-$earning['expend']);
	$earn_infos[] = $infos;

	$month[] = $earning['month'];
	$income[] = $earning['income'];
	$expend[] = $earning['expend'];
}
$average = round((array_sum($income)-array_sum($expend))/count($month), 2);

$smarty->assign('labels', json_encode(array_reverse($month)));
$smarty->assign('income', json_encode(array_reverse($income)));
$smarty->assign('expend', json_encode(array_reverse($expend)));
$smarty->assign('average', $average);
$smarty->assign('infos', $earn_infos);
$smarty->assign('category_id', '1');
$smarty->assign('title', '龙泉财报');
$smarty->display('note.tpl');
?>
