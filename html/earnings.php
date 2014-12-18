<?php
// 图片大小: 400*345
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;
LogOpt::init('display_earnings', true);

if (!$is_root)
{
	ZeyuBlogOpt::warning_opt('亲，没权限呢', '/html');
	return;
}

$det_beg = empty($_GET['beg']) ? date('Y-m', time()) : $_GET['beg'];
$det_end = empty($_GET['end']) ? '2013-09' : $_GET['end'];
if ($det_beg > $det_end)
	list($det_beg, $det_end) = array($det_end, $det_beg);
$drw_beg = empty($_GET['drw_beg']) ? date('Y-m', time()) : $_GET['drw_beg'];
$drw_end = empty($_GET['drw_end']) ? '2013-09' : $_GET['drw_end'];
if ($drw_beg > $drw_end)
	list($drw_beg, $drw_end) = array($drw_end, $drw_beg);

$query = 'select * from earnings order by month desc limit 24';
$earnings = MySqlOpt::select_query($query);
$earn_infos = array();
$month = array();
$income = array();
$expend = array();

$index = 0;

foreach ($earnings as $earning)
{
	$infos = array();

	if ($earning['month'] >= $det_beg
		and $earning['month'] <= $det_end
		and $index < 12
	)
	{
		$infos['idx_href'] = 'article.php?id='.$earning['article_id'];

		$image_select_query =
			'select path from images where image_id='.$earning['image_id'];
		$image_ret = MySqlOpt::select_query($image_select_query);

		$path = $image_ret[0]['path'];
		$infos['image_path'] = $path;
		$infos['title'] = $earning['month'];
		$infos['descs'] =
			'结余:&nbsp;&nbsp;'.($earning['income']-$earning['expend']);
		$earn_infos[] = $infos;
		$index++;
	}

	if ($earning['month'] >= $drw_beg and $earning['month'] <= $drw_end)
	{
		$month[] = $earning['month'];
		$income[] = $earning['income'];
		$expend[] = $earning['expend'];
	}
}
$average = round((array_sum($income)-array_sum($expend))/count($month), 2);

$smarty->assign('det_beg_month', $det_beg);
$smarty->assign('det_end_month', $det_end);
$smarty->assign('drw_beg_month', $drw_beg);
$smarty->assign('drw_end_month', $drw_end);
$smarty->assign('labels', json_encode(array_reverse($month)));
$smarty->assign('income', json_encode(array_reverse($income)));
$smarty->assign('expend', json_encode(array_reverse($expend)));
$smarty->assign('average', $average);
$smarty->assign('infos', $earn_infos);
$smarty->assign('category_id', '1');
$smarty->assign('title', '龙泉财报');
$smarty->display('note.tpl');
?>
