<?php
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');

LogOpt::init('diary_loader', true);

$options = getopt('t:');

$infos = array();
$draft_file = dirname(__FILE__).'/../'.'draft/draft.tpl';
if (!file_exists($draft_file))
{
	echo '指定日志的草稿不存在'.PHP_EOL;
	return;
}
$infos['draft'] = file_get_contents($draft_file);
$contents = ZeyuBlogOpt::pre_treat_article ($infos['draft']);
$image_ids = array();
while (1)
{
	$image_path = StringOpt::spider_string($contents, 'img<![&&]>src="', '"', $contents);
	if ($image_path === null || $image_path === false || trim($image_path) == '')
		break;
	$image_path = trim($image_path);
	if (!file_exists(dirname(__FILE__).'/../'.'html/'.$image_path))
	{
		LogOpt::set('exception', 'the image does not exist', 'image_path', $image_path);
		return;
	}
	$query = 'select image_id from images where path="'.$image_path.'"';
	$image_id = MySqlOpt::select_query($query);
	if ($image_id == null)
	{
		$full_path = dirname(__FILE__).'/../'.'html/'.$image_path;
		$image_id = ZeyuBlogOpt::load_image($full_path, 'article');
		if ($image_id == false)
		{
			LogOpt::set('exception', '添加图片到数据库失败', 'image_path', $image_path, MySqlOpt::errno(), MySqlOpt::error());
		}
		LogOpt::set('info', '添加图片到数据库成功', 'image_id', $image_id, 'image_path', $image_path);
		$image_ids[] = $image_id;
	}
	else 
		$image_id = $image_id[0]['image_id'];
	$image_ids[] = $image_id;
}
$infos['images'] = json_encode($image_ids);
if (isset($options['t']))
	$infos['title'] = $options['t'].' -- '.date('Y-m-d');
else
	$infos['title'] = 'now()';
$infos['category_id'] = '5';
$infos['updatetime'] = 'now()';
$article_id = MySqlOpt::insert('article', $infos, true);
if ($article_id == null)
{
	LogOpt::set('exception', 'article insert error');
	return;
}
$query = 'select article_id,article_count from category where category_id=5';
$articles = MySqlOpt::select_query($query);
if ($articles == null)
{
	LogOpt::set('info', 'category 中暂无文章', 'category_id', 5);
	$articles = array();
}
else
	$articles = json_decode($articles[0]['article_id'], true);
$articles[] = $article_id;
$infos = array();
$infos['article_id'] = json_encode($articles);
$infos['updatetime'] = 'now()';
$infos['article_count'] = count($articles);
$ret = MySqlOpt::update('category', $infos, array('category_id'=>5));
if ($ret == false)
{
	LogOpt::set('exception', 'article insert into category failed', 'article_id', $article_id);
	return;
}
LogOpt::set ('info', 'category update success', 'article_id', $article_id);
unlink($draft_file);
?>
