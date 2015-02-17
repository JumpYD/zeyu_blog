<?php
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;
LogOpt::init('viewcount', true);

$labels = array();
$uv = array();
$pv = array();
$timestamp = time();
for ($i=0; $i<14; ++$i)
{
	$date = date('Y-m-d', $timestamp - 3600*24*(14-1) + $i*3600*24);

	$sql = 'select date(time_str) as date, count(*) as total from'
		.' (select time_str from stats where time_str <= "'.$date.' 23:59:59"'
		.' and time_str >= "'.$date.' 00:00:00" group by remote_host) as A'
		.' group by date(time_str)';
	$remote_host_count = MySqlOpt::select_query($sql);
	if ($remote_host_count == false)
	{
		ZeyuBlogOpt::warning_opt('数据库访问失败', '/html');
		return;
	}
	$uv[] = $remote_host_count[0]['total'];
	$labels[] = $remote_host_count[0]['date'];
}

$sql = 'select date(time_str) as date, count(*) as total from'
	.' stats where time_str <= "'.date('Y-m-d', $timestamp).' 23:59:59"'
	.' and time_str >= "'.date('Y-m-d', $timestamp - 3600*24*(14-1)).' 00:00:00"'
	.' group by date(time_str) order by time_str';
$pv_count = MySqlOpt::select_query($sql);
if ($pv_count == false)
{
	ZeyuBlogOpt::warning_opt('数据库访问失败', '/html');
	return;
}

foreach ($pv_count as $pv_info)
	$pv[] = $pv_info['total'];

$sql = 'select count(*) as total from stats';
$all_pv = MySqlOpt::select_query($sql);
if ($all_pv == false)
{
	ZeyuBlogOpt::warning_opt('数据库访问失败', '/html');
	return;
}
$all_pv = $all_pv[0]['total'];

$sql = 'select count(*) as total from'
	.' (select count(*) as total from stats group by remote_host) as A';
$all_uv = MySqlOpt::select_query($sql);
if ($all_uv == false)
{
	ZeyuBlogOpt::warning_opt('数据库访问失败', '/html');
	return;
}
$all_uv = $all_uv[0]['total'];

$smarty->assign('all_pv', $all_pv);
$smarty->assign('all_uv', $all_uv);
$smarty->assign('today_pv', $pv[13]);
$smarty->assign('today_uv', $uv[13]);
$smarty->assign('pv', json_encode($pv));
$smarty->assign('uv', json_encode($uv));
$smarty->assign('labels', json_encode($labels));
$smarty->assign('title', '数据统计');
$smarty->display('viewcount.tpl');
?>
