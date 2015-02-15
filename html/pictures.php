<?php
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;
LogOpt::init('pictures');

if (!$is_root)
{
	ZeyuBlogOpt::warning_opt('访问的页面不存在', 'index.php');
	return;
}

$opt_type = isset($_REQUEST['opt_type']) ? $_REQUEST['opt_type'] : 'search';
unset($_REQUEST['opt_type']);
switch ($opt_type)
{
case 'insert':
	picture_insert($_REQUEST);
	break;
default:
	picture_search($_REQUEST);
	break;
}

function picture_insert($input)
{
	global $smarty;

	$url = '/html/pictures.php';
	if ($_FILES["file"]["error"] > 0)
	{
		$smarty->assign('message', 'Error: '.$_FILES["file"]["error"]);
		$smarty->assign('url', $url);
		$smarty->display('warning.tpl');
	}

	$params_key = array('insert_id', 'insert_category');
	$params = getParams($input, $params_key);
	if (!isset($params['insert_id']) || intval($params['insert_id']) <= 0)
		$params['insert_id'] = null;
	$params['name'] = trim($_FILES["file"]["name"]);
	$file = '/home/zeyu/Documents/images/'.$params['name'];
	$ret = copy($_FILES["file"]["tmp_name"], $file);
	if ($ret == false)
	{
		$smarty->assign('message', '临时文件不存在');
		$smarty->assign('url', $url);
		$smarty->display('warning.tpl');
	}

	$ret = ZeyuBlogOpt::picture_insert(
		$params['name'],
		$params['insert_category'],
		$params['insert_id']
	);

	switch ($ret)
	{
	case -1:
		$message = '源文件不存在';
		break;
	case -2:
		$message = '文件替换失败，请查看权限';
		break;
	case -3:
		$message = '目录创建失败，请查看权限';
		break;
	case -4:
		$message = '指定被替换文件 ID 不存在';
		break;
	case -5:
		$message = '文件添加失败，请查看权限';
		break;
	default:
		$message = '文件添加成功';
		$url .= '?image_id='.$ret;
	}

	$smarty->assign('message', $message);
	$smarty->assign('url', $url);
	$smarty->display('warning.tpl');
}

function picture_search($input)
{
	global $smarty;
	$params_key = array(
		'image_id', 'path',
		'md5', 'category',
		'start_time', 'end_time'
	);
	$params = getParams($input, $params_key);
	$category = $params['category'];
	if ($params['category'] == 'all')
		unset($params['category']);

	$limit = 10;
	$page = isset($input['page'])?intval($input['page']):1;
	if ($page<1)
		$page = 1;
	$start = ($page-1)*$limit;

	$sql = 'select count(*) as count from images where 1';
	foreach ($params as $key => $value)
	{
		if ($key == 'start_time')
			$sql .= ' and inserttime>="'.mysql_escape_string($value).'"';
		else if ($key == 'end_time')
			$sql .= ' and inserttime<="'.mysql_escape_string($value).'"';
		else
			$sql .= ' and '.$key.'="'.mysql_escape_string($value).'"';
	}

	$count = MySqlOpt::select_query($sql);
	$count = $count[0]['count'];
	$allcount = intval(($count-1)/$limit + 1);

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

	$sql = 'select * from images where 1';
	foreach ($params as $key => $value)
	{
		if ($key == 'start_time')
			$sql .= ' and inserttime>="'.mysql_escape_string($value).'"';
		else if ($key == 'end_time')
			$sql .= ' and inserttime<="'.mysql_escape_string($value).'"';
		else
			$sql .= ' and '.$key.'="'.mysql_escape_string($value).'"';
	}
	$sql .= ' limit '.$start.', '.$limit;
	$infos = MySqlOpt::select_query($sql);

	$sql = 'select category from images group by category';
	$category_infos = MySqlOpt::select_query($sql);
	$category_list = array('all');
	foreach ($category_infos as $cat)
		$category_list[] = $cat['category'];

	unset($input['page']);
	$param = array();
	foreach ($input as $key=>$value)
	{
		$param[] = trim($key).'='.trim($value);
	}
	if (!isset($input['category']))
		$param[] = 'category=all';
	$param = implode('&', $param);

	$smarty->assign('category_list', $category_list);
	$smarty->assign('start_time', $input['start_time']);
	$smarty->assign('end_time', $input['end_time']);
	$smarty->assign('category', $category);
	$smarty->assign('page', $page);
	$smarty->assign('count', $count);
	$smarty->assign('infos', $infos);
	$smarty->assign('param', $param);
	$smarty->assign('title', '龙潭相册');
	$smarty->display('pictures.tpl');
}

function getParams ($input, $keys)
{
	$params = array();
	foreach ($keys as $key)
	{
		if (isset($input[$key]) && $input[$key] != '')
			$params[$key] = $input[$key];
	}
	return $params;
}
?>
