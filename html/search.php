<?php
require_once (dirname(__FILE__).'/'.'head.php');
$opt_type = isset($_REQUEST['opt_type'])?strtolower($_REQUEST['opt_type']): 'show';
LogOpt::init('article_searcher_'.$opt_type, true);
switch ($opt_type)
{
case 'show':
	search_show_opt($_REQUEST);
	break;
case 'change':
	search_change_opt($_REQUEST);
	break;
case 'icon':
	search_icon_opt($_REQUEST);
	break;
}

function search_show_opt($input)
{
	global $smarty;
	$sql = 'select tag_id,count(*) as article_count from article_tag_relation group by tag_id order by article_count desc, inserttime desc';
	$infos = MySqlOpt::select_query($sql);
	$tag_infos = array();
	foreach ($infos as $info)
	{
		$sql = 'select tag_name from tags where tag_id='.$info['tag_id'];
		$ret = MySqlOpt::select_query($sql);
		$info['tag_name'] = $ret[0]['tag_name'];
		$tag_infos[] = $info;
	}

	//$first_date = '2013-12-15';
	$dates = array();
	$month_num = (date('Y')-2013)*12 + (date('m')-12) + 1;
	for ($i=0; $i<$month_num; $i++)
	{
		$info = array();
		$date = date("Y-m", mktime(0, 0, 0, date("m")-$i, date("d"), date("Y")));
		$info['id'] = date('Y0m', mktime(0, 0, 0, date("m")-$i, date("d"), date("Y")));
		$info['month'] = $date;
		$query = 'select count(*) from article where inserttime>="'.$date.'-01 00:00:00" and inserttime<="'.$date.'-31 23:59:59"';
		$article_count = MySqlOpt::select_query($query);
		if ($article_count == null)
		{
			LogOpt::set('exception', 'select article by inserttime error', 'date', $date, MySqlOpt::errno(), MySqlOpt::error());
			return;
		}
		$article_count = intval($article_count[0]['count(*)']);
		$info['article'] = $article_count;
		$query = 'select count(*) from mood where inserttime>="'.$date.'-01 00:00:00" and inserttime<="'.$date.'-31 23:59:59"';
		$mood_count = MySqlOpt::select_query($query);
		if ($mood_count == null)
		{
			LogOpt::set('exception', 'select mood by inserttime error', 'date', $date, MySqlOpt::errno(), MySqlOpt::error());
			return;
		}
		$info['mood'] = intval($mood_count[0]['count(*)']);
		$dates[] = $info;
	}

	$smarty->assign('dates', $dates);
	$smarty->assign('tags', $tag_infos);
	$smarty->assign('tags_count', count($tag_infos));
	$smarty->assign('title', '检索一下');
	$smarty->assign('category_id', 1);
	$smarty->display('search.tpl');
}

function search_change_opt($input)
{
	$label_json = $input['label_json'];
	$input_json = StringOpt::spider_string($label_json, '$[$', '$]$');
	$labels = json_decode($input_json, true);
	if (in_array($input['label_id'], $labels))
	{
		$idx = array_search($input['label_id'], $labels);
		unset($labels[$idx]);
		$labesls = array_values($labels);
	}
	else
	{
		$labels[] = $input['label_id'];
	}
	$label_json = json_encode($labels);
	echo str_replace('$[$'.$input_json.'$]$', '$[$'.$label_json.'$]$', $input['label_json']);
}

function search_icon_opt($input)
{
	$label_icon = $input['label_icon'];
	if (strpos($label_icon, 'glyphicon-bookmark') === false)
		echo str_replace('glyphicon-ok', 'glyphicon-bookmark', $label_icon);
	else
		echo str_replace('glyphicon-bookmark', 'glyphicon-ok', $label_icon);
}
?>
