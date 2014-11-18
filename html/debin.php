<?php

require_once(dirname(__FILE__).'/'.'head.php');
require_once(dirname(__FILE__).'/../'.'library/SphinxClient.php');

LogOpt::init('display_debin');

$query_info = get_query_info($_REQUEST);

$category_map = array('0'=>'检索结果', 1=>'龙潭书斋', 2=>'读书笔记', 3=>'龙渊阁记', 4=>'技术分享', 5=>'龙泉日记', 6=>'龙泉财报', 'mood'=>'心情小说');
if (!isset($category_map[$query_info['category']]))
{
	ZeyuBlogOpt::warning_opt('请填写category参数', '/html');
	return;
}

$sphinx = get_sphinx();

switch($query_info['category'])
{
case 'mood':
	display_mood();
	break;
default:
	display_article();
}

function display_article()
{
	global $category_map, $sphinx, $query_info;

	$category = $query_info['category'];
	$opt_type = $query_info['opt_type'];
	$search = trim($query_info['search']);

	$count_sql = 'select count(*) as count from article where 1';
	$sql = 'select * from article where 1';

	$tags = explode(',', $query_info['tags']);
	$where_str = get_where($tags);

	if (!empty($search))
	{
		$article_ids = array();
		$searchs = explode(' ', $query_info['search']);
		foreach ($searchs as $key)
		{
			$key = trim($key);
			if (empty($key))
				continue;
			$search_ret = $sphinx->query($key, $opt_type);

			if (empty($article_ids))
				$article_ids = array_keys($search_ret['matches']);
			else
				$article_ids = array_intersect($article_ids, array_keys($search_ret['matches']));
		}
		$where_str .= ' and article_id in ('.implode(',', $article_ids).')';
	}
	else if ($category == '0' && $opt_type != 'all')
		$where_str .= ' and category_id != 5';

	if ($category >= 1 && $category <= 6)
		$where_str .= ' and category_id = '.$category;

	$count_sql .= $where_str;

	$count = MySqlOpt::select_query ($count_sql);
	$count = intval($count[0]['count']);

	$sql .= $where_str.' order by updatetime desc limit '.$query_info['start'].','.$query_info['limit'];
	$article_infos = MySqlOpt::select_query ($sql);

	$infos = array();
	foreach ($article_infos as $info)
		if (($info = select_article($info)) !== false)
			$infos[] = $info;

	display($category_map[$category], $count, $infos);
}

function display_mood()
{
	global $category_map, $sphinx, $query_info;

	$count_sql = 'select count(*) as count from mood where 1';
	$sql = 'select * from mood where 1';

	$tags = explode(',', $query_info['tags']);
	$where_str = get_where($tags, true);

	$mood_ids = array();
	if (!empty($query_info['search']))
	{
		$search = explode(' ', $query_info['search']);
		foreach ($search as $key)
		{
			$key = trim($key);
			if (empty($key))
				continue;
			$search_ret = $sphinx->query($key, 'mood');
			if (empty($mood_ids))
				$mood_ids = array_keys($search_ret['matches']);
			else
				$mood_ids = array_intersect($mood_ids, array_keys($search_ret['matches']));
		}
		$where_str .= ' and mood_id in ('.implode(',', $mood_ids).')';
	}

	$count_sql .= $where_str;
	$count = MySqlOpt::select_query ($count_sql);
	$count = intval($count[0]['count']);

	$sql .= $where_str.' order by inserttime desc limit '.$query_info['start'].','.$query_info['limit'];
	$mood_infos = MySqlOpt::select_query ($sql);

	$infos = array();
	foreach ($mood_infos as $info)
		if (($info = select_mood($info)) !== false)
			$infos[] = $info;

	display($category_map[$query_info['category']], $count, $infos, true);
}

function get_query_info($input)
{
	$query_info['page'] = isset($input['page']) ? intval($input['page']) : 1;
	if ($query_info['page'] < 1)
		$query_info['page'] = 1;

	$query_info['limit'] = isset($input['limit']) ? intval($input['limit']) : 10;
	if ($query_info['limit'] < 1)
		$query_info['limit'] = 1;

	$query_info['start'] = ($query_info['page'] - 1) * $query_info['limit'];
	$query_info['category'] = isset($input['category']) ? $input['category'] : '';
	$query_info['search'] = isset($input['search']) ? $input['search'] : '';
	$query_info['tags'] = isset($input['tags']) ? $input['tags'] : '';
	$query_info['opt_type'] = isset($input['opt_type']) ? $input['opt_type'] : 'content';

	return $query_info;
}

function get_sphinx()
{
	$sphinx = new SphinxClient();
	$sphinx->setServer("localhost", 9312);
	$sphinx->setMatchMode(SphinxClient::SPH_MATCH_PHRASE);
	$sphinx->setLimits(0, 1000);
	$sphinx->setMaxQueryTime(30);

	return $sphinx;
}

function get_where ($tags, $ismood = false)
{
	$dates = array();
	$tag_ids = array();
	$where_str = '';

	if (!empty($tags))
	{
		foreach ($tags as $tag)
		{
			$tag_infos = explode('_', $tag);
			if (count($tag_infos) != 3)
				continue;
			switch ($tag_infos[1])
			{
			case 'tag':
				$tag_ids[] = mysql_escape_string($tag_infos[2]);
				break;
			case 'date':
				$tag_infos[2][4] = '-';
				$dates[] = $tag_infos[2];
				break;
			default:
				break;
			}
		}

		if ($ismood)
			$time_key = 'inserttime';
		else
			$time_key = 'updatetime';

		if (!empty($tag_ids) && !$ismood)
			$where_str .= ' and article_id in (select article_id from article_tag_relation where tag_id in ('.implode(',', $tag_ids).'))';

		if (!empty($dates))
		{
			$where_arr = array();
			foreach ($dates as $date)
				$where_arr[] .= $time_key.' >= "'.$date.'-01 00:00:00" and '.$time_key.' <= "'.$date.'-31 23:59:59"';
			$where_str = ' and ('.implode(' or ', $where_arr).')';
		}
	}
	return $where_str;
}

function select_mood ($info)
{
	$infos = array();
	$infos['title'] = $info['contents'];
	$infos['contents'] = $info['inserttime'];

	$date = explode (' ', $info['inserttime']);
	if (count($date) != 2)
	{
		LogOpt::set ('exception', 'inserttime get error', 'mood_id', $info['mood_id'], 'inserttime', $info['inserttime']);
		return false;
	}

	$date = $date[0];
	$date = explode ('-', $date);
	if (count($date) != 3)
	{
		LogOpt::set ('exception', 'inserttime.date get error', 'mood_id', $info['mood_id'], 'inserttime', $info['inserttime']);
		return false;
	}

	$infos['date'] = $date[2];
	$infos['month'] = $date[1].'/'.$date[0];

	return $infos;
}

function select_article($info)
{
	$infos = array();
	$infos['title'] = $info['title'];

	$date = explode(' ', $info['inserttime']);
	if (count($date) != 2)
	{
		LogOpt::set ('exception', 'inserttime get error', 'article_id', $info['article_id'], 'inserttime', $info['inserttime']);
		return false;
	}

	$date = $date[0];
	$date = explode ('-', $date);
	if (count($date) != 3)
	{
		LogOpt::set ('exception', 'inserttime.date get error', 'article_id', $info['article_id'], 'inserttime', $info['inserttime']);
		return false;
	}

	$infos['date'] = $date[2];
	$infos['month'] = $date[1].'/'.$date[0];
	$infos['tags'] = array_slice(ZeyuBlogOpt::get_tags($info['article_id']), 0, 4);

	$contents = ZeyuBlogOpt::pre_treat_article($info['draft']);
	$imgpath = StringOpt::spider_string($contents, 'img<![&&]>src="', '"');
	if ($imgpath == null)
	{
		$infos['contents'] = strip_tags($contents);
		$infos['contents'] = mb_substr($infos['contents'], 0, 500, 'utf-8');
	}
	else
	{
		$infos['contents'] = '<p><img class="img-thumbnail" alt="200x200" style="height: 200px;" src="'.$imgpath.'"></p><br /><p>'.mb_substr(strip_tags($contents), 0, 100, 'utf-8').'</p>';
	}

	$infos['article_id'] = $info['article_id'];

	return $infos;
}

function display($title, $category_count, $infos, $ismood=false)
{
	global $smarty, $query_info;

	$page = $query_info['page'];
	$limit = $query_info['limit'];

	$allcount = ($category_count-1)/$limit+1;
	$allcount = intval($allcount);
	if ($allcount > 1)
	{
		if ($allcount <= $limit)
			$pagelist = range(1, $allcount);
		else if ($page >= $allcount - $limit/2)
		{
			$pagelist = range($allcount-$limit-1, $allcount);
			$smarty->assign('first', '1');
			$smarty->assign('pre', $page-1);
		}
		else if ($page <= $limit/2)
		{
			$pagelist = range(1, $limit);
			$smarty->assign('end', $allcount);
			$smarty->assign('last', $page+1);
		}
		else
		{
			$pagelist = range($page-$limit/2-1, $page+$limit/2);
			$smarty->assign('first', '1');
			$smarty->assign('pre', $page-1);
			$smarty->assign('end', $allcount);
			$smarty->assign('last', $page+1);
		}
		$smarty->assign('list', $pagelist);
	}

	$smarty->assign('category', $title.' -- '.$category_count);
	$smarty->assign('ismood', $ismood);
	$smarty->assign('title', $title);
	$smarty->assign('page', $query_info['page']);
	$smarty->assign('infos', $infos);
	$smarty->assign('query_info', $query_info);
	$smarty->display('debin.tpl');
}
?>
