<?php
require_once(dirname(__FILE__).'/'.'head.php');
LogOpt::init('display_debin');

$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
if ($page < 1)
	$page = 1;

$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10;
if ($limit < 1)
	$limit = 1;

$category_id = $_REQUEST['category'];
switch($category_id)
{
case 'search':
case 'searchall':
	display_result($_REQUEST);
	break;
case 'mood':
	display_mood();
	break;
case 'all':
	display_all();
	break;
default:
	display_debin();
	break;
}

function display_mood()
{
	global $page;
	$query = 'select * from mood order by inserttime desc';
	$category_info = MySqlOpt::select_query($query);
	if ($category_info == null)
	{
		ZeyuBlogOpt::warning_opt('页面不存在', '/html');
		return;
	}
	$mood_infos = $category_info;
	$infos = array();
	for ($i=0; $i<10; ++$i)
	{
		if (!isset($mood_infos[($page-1)*10+$i]))
			break;
		$info = $mood_infos[($page-1)*10+$i];
		if (($mood_info = select_article('mood', $info)) !== false)
			$infos[] = $mood_info;
	}

	display('心情小说', '心情小说', count($category_info), $page, $infos, true);
}

function display_all()
{
	global $page;
	$query = 'select * from article order by inserttime desc';
	$articles = MySqlOpt::select_query($query);
	if ($articles == null)
	{
		LogOpt::set('exception', 'select all articles error', MySqlOpt::errno(), MySqlOpt::error());
		return;
	}
	$infos = array();
	for ($i=0; $i<10; ++$i)
	{
		if (!isset($articles[($page-1)*10+$i]))
			break;
		if (($info = select_article('article', $articles[($page-1)*10+$i])) !== false)
			$infos[] = $info;
		else
			--$i;
	}
	display('全部日志', '全部日志', count($articles), $page, $infos);
}

function display_debin()
{
	global $category_id;
	global $page;
	$limit = 10;
	$category_id = intval($category_id);
	if ($category_id == null)
	{
		ZeyuBlogOpt::warning_opt('请填写category参数', '/html');
		return;
	}

	$sql = 'select category from category where category_id='.$category_id;
	$category_info = MySqlOpt::select_query($sql);
	if ($category_info == null)
	{
		ZeyuBlogOpt::warning_opt('页面不存在', '/html');
		return;
	}
	$category = $category_info[0]['category'];
	$sql = 'select count(*) as article_count from article where category_id='.$category_id;
	$ret = MySqlOpt::select_query($sql);
	$count = $ret[0]['article_count'];

	$sql = 'select * from article where category_id='.$category_id.' order by inserttime desc limit '.(($page-1)*$limit).','.$limit;
	$article_infos = MySqlOpt::select_query($sql);
	$infos = array();
	foreach ($article_infos as $info)
		if (($info = select_article('article', $info)) !== false)
			$infos[] = $info;

	$sql = 'select count(*) as count from article where category_id ='.$category_id;
	$article_count = MySqlOpt::select_query($sql);
	$article_count = $article_count[0]['count'];

	display($category, $category, $article_count, $page, $infos);
}

function display_result($input)
{
	global $page;
	global $limit;
	$start = ($page-1)*$limit;

	$count_sql = 'select count(*) as count from article as A, article_tag_relation as B where A.article_id = B.article_id';
	$sql = 'select A.* from article as A, article_tag_relation as B where A.article_id = B.article_id';

	$tags = explode(',', $input['tags']);
	$where_str = get_where_str($tags);

	$count_sql .= $where_str;
	$count = MySqlOpt::select_query ($count_sql);
	$count = $count[0]['count'];

	$sql .= $where_str.' limit '.$start.','.$limit.' order by updatetime desc';
	$article_infos = MySqlOpt::select_query ($sql);

	$infos = array();
	foreach ($article_infos as $info)
		if (($info = select_article('article', $info)) !== false)
			$infos[] = $info;

	display('检索结果 -- '.$count, '检索结果', $count, $infos, $infos);
}

function get_where_str ($tags)
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
				$tag_ids[] = $tag_infos[2];
				break;
			case 'date':
				$tag_infos[2][4] = '-';
				$dates[] = $tag_infos[2];
				break;
			default:
				break;
			}
		}
		if (!empty($tag_ids))
			$where_str .= ' and B.tag_id in ('.implode(',', $tag_ids).')';
		if (!empty($dates))
		{
			foreach ($dates as $date)
				$where_str .= ' and A.updatetime >= "'.$date.'-01 00:00:00" and A.updatetime <= "'.$date.'-31 23:59:59"';
		}
	}
	return $where_str;
}

function display($category, $title, $category_count, $page, $infos, $ismood=false)
{
	global $smarty;
	global $category_id;
	global $_REQUEST;
	$input = $_REQUEST;
	if (isset($input['page']))
		unset($input['page']);
	$param = array();
	foreach ($input as $key=>$value)
	{
		$param[] = trim($key).'='.trim($value);
	}
	$param = implode('&', $param);
	$allcount = ($category_count-1)/10+1;
	$allcount = intval($allcount);
	if ($allcount > 1)
	{
		if ($allcount <= 10)
			$pagelist = range(1, $allcount);
		else if ($page >= $allcount-5)
		{
			$pagelist = range($allcount-9, $allcount);
			$smarty->assign('first', '1');
			$smarty->assign('pre', $page-1);
		}
		else if ($page <= 5)
		{
			$pagelist = range(1, 10);
			$smarty->assign('end', $allcount);
			$smarty->assign('last', $page+1);
		}
		else
		{
			$pagelist = range($page-4, $page+5);
			$smarty->assign('first', '1');
			$smarty->assign('pre', $page-1);
			$smarty->assign('end', $allcount);
			$smarty->assign('last', $page+1);
		}
		$smarty->assign('list', $pagelist);
	}

	$smarty->assign('category', $category);
	$smarty->assign('ismood', $ismood);
	$smarty->assign('title', $title);
	$smarty->assign('param', $param);
	$smarty->assign('category_id', $category_id);
	$smarty->assign('page', $page);
	$smarty->assign('infos', $infos);
	$smarty->display('debin.tpl');
}

function select_article ($table, $info)
{
	$infos = array();
	$key = $table.'_id';
	if ($table != 'mood')
	{
		$infos['title'] = $info['title'];
		$date = explode(' ', $info['inserttime']);
		if (count($date) != 2)
		{
			LogOpt::set ('exception', 'inserttime get error', $key, $info['article_id'], 'inserttime', $info['inserttime']);
			return false;
		}
		$date = $date[0];
		$date = explode ('-', $date);
		if (count($date) != 3)
		{
			LogOpt::set ('exception', 'inserttime.date get error', $key, $info['article_id'], 'inserttime', $info['inserttime']);
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
	else
	{
		$infos['title'] = $info['contents'];
		$infos['contents'] = $info['inserttime'];
		$date = explode (' ', $info['inserttime']);
		if (count($date) != 2)
		{
			LogOpt::set ('exception', 'inserttime get error', $key, $info['mood_id'], 'inserttime', $info['inserttime']);
			return false;
		}
		$date = $date[0];
		$date = explode ('-', $date);
		if (count($date) != 3)
		{
			LogOpt::set ('exception', 'inserttime.date get error', $key, $info['mood_id'], 'inserttime', $info['inserttime']);
			return false;
		}
		$infos['date'] = $date[2];
		$infos['month'] = $date[1].'/'.$date[0];
		return $infos;
	}
}
?>
