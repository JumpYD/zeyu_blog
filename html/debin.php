<?php
require_once(dirname(__FILE__).'/'.'head.php');
LogOpt::init('display_debin');

$page = isset($_GET['page'])?intval($_GET['page']):1;
if ($page<1)
	$page = 1;
$category_id = $_GET['category'];
switch($category_id)
{
case 'search':
case 'searchall':
	display_result($_GET);
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

	$sql = 'select category,article_count from category where category_id='.$category_id;
	$category_info = MySqlOpt::select_query($sql);
	if ($category_info == null)
	{
		ZeyuBlogOpt::warning_opt('页面不存在', '/html');
		return;
	}
	$category = $category_info[0]['category'];
	$count = $category_info[0]['article_count'];

	$sql = 'select * from article where category_id='.$category_id.' order by inserttime desc limit '.(($page-1)*$limit).','.$limit;
	$article_infos = MySqlOpt::select_query($sql);
	$articles_id = array();
	$infos = array();
	foreach ($article_infos as $info)
		if (($info = select_article('article', $info)) !== false)
			$infos[] = $info;

	display($category, $category, count($articles_id), $page, $infos);
}

function display_result($input)
{
	global $page;
	global $category_id;
	$article_infos = array();
	$infos = array();
	$dates_json = html_entity_decode($input['dates']);
	$dates_json = StringOpt::spider_string($dates_json, '$[$', '$]$');
	$dates = json_decode($dates_json, true);
	$date_info = '';
	for ($i=0; $i<count($dates); ++$i)
	{
		$dates[$i]['5'] = '-';
		if ($i>0)
			$date_info .= ' or';
		else
			$date_info .= ' and (';
		$date_info .= '(inserttime>="'.$dates[$i].'-01 00:00:00" and inserttime<="'.$dates[$i].'-31 23:59:59")';
		if ($i==count($dates)-1)
			$date_info .= ')';
	}
	if ($input['opt_type'] != 'mood')
	{
		if ($input['opt_type']!='title' && $input['opt_type']!='contents')
		{
			ZeyuBlogOpt::warning_opt ('参数有误', '/html/search.php');
			return;
		}
		$tags_json = html_entity_decode($input['tags']);
		$input_json = StringOpt::spider_string($tags_json, '$[$', '$]$');
		$tags = json_decode($input_json, true);
		if (count($tags) > 0)
		{
			$articles = array();
			foreach ($tags as $tag_id)
			{
				$tag_id = intval($tag_id);
				$query = 'select article_id from tags where tag_id='.$tag_id.$date_info;
				$ret = MySqlOpt::select_query ($query);
				if ($ret == null || !isset($ret[0]['article_id']))
				{
					LogOpt::set('exception', 'select by tag error', 'tag_id', $tag_id, 'date_info', $date_info, MySqlOpt::errno(), MySqlOpt::error());
					continue;
				}
				$article_ids = json_decode($ret[0]['article_id'], true);
				$articles[] = $article_ids;
			}
			$selected = $articles[0];
			for ($i=1; $i<count($articles); ++$i)
				$selected = array_intersect($selected, $articles[$i]);
			foreach ($selected as $article_id)
			{
				$query = 'select * from article where article_id='.$article_id.$date_info;
				$ret = MySqlOpt::select_query ($query);
				if ($ret == null)
				{
					LogOpt::set('exception', 'select article error', 'article_id', $article_id, MySqlOpt::errno(), MySqlOpt::error());
					continue;
				}
				$ret = $ret[0];
				$contents = $ret[$input['opt_type']];
				if (!isset($input['search']) || $input['search']=='' || mb_strpos(strtolower($contents), strtolower($input['search']))!==false)
				{
					$article_infos[] = $ret;
				}
			}
			$article_infos = array_reverse($article_infos);
		}
		else
		{
			if ($category_id == 'search')
				$query = 'select * from article where category_id!=5'.$date_info.' order by inserttime desc';
			else
				$query = 'select * from article where 1'.$date_info.' order by inserttime desc';
			$rets = MySqlOpt::select_query ($query);
			if ($rets != null)
			{
				foreach ($rets as $ret)
				{
					$contents = $ret[$input['opt_type']];
					if (!isset($input['search']) || $input['search']=='' || mb_strpos(strtolower($contents), strtolower($input['search']))!==false)
					{
						$article_infos[] = $ret;
					}
				}
			}
		}
		for ($i=0; $i<10; ++$i)
		{
			if (!isset ($article_infos[($page-1)*10+$i]))
				break;
			$info = $article_infos[($page-1)*10+$i];
			if (($info = select_article('article', $info)) !== false)
			{
				$infos[] = $info;
			}
		}
		display('检索结果 -- '.count($article_infos), '检索结果', count($article_infos), $page, $infos);
	}
	else
	{
		$query = 'select * from mood where true'.$date_info.' order by inserttime desc';
		$rets = MySqlOpt::select_query ($query);
		if ($rets != null)
		{
			foreach ($rets as $ret)
			{
				$contents = $ret['contents'];
				if (!isset($input['search']) || $input['search']=='' || mb_strpos(strtolower($contents), strtolower($input['search']))!==false)
				{
					$article_infos[] = $ret;
				}
			}
			for ($i=0; $i<10; ++$i)
			{
				if (!isset ($article_infos[($page-1)*10+$i]))
					break;
				$info = $article_infos[($page-1)*10+$i];
				if (($info = select_article('mood', $info)) !== false)
				{
					$infos[] = $info;
				}
			}
		}
		display('检索结果 -- '.count($article_infos), '检索结果', count($article_infos), $page, $infos, true);
	}
	return;
}

function display($category, $title, $category_count, $page, $infos, $ismood=false)
{
	global $smarty;
	global $category_id;
	global $_GET;
	$input = $_GET;
	if (isset($input['page']))
		unset($input['page']);
	if (isset($input['tags']))
	{
		$input['tags'] = htmlspecialchars('$[$'.StringOpt::spider_string($input['tags'], '$[$', '$]$').'$]$');
		$input['dates'] = htmlspecialchars('$[$'.StringOpt::spider_string($input['dates'], '$[$', '$]$').'$]$');
	}
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
