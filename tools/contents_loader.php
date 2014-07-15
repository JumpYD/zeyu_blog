<?php
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');

LogOpt::init('contents_loader', true);

$options = getopt('i:');

if (!isset($options['i']) || !is_numeric($options['i']))
{
	echo 'usage: php contents_loader.php -i article_id'.PHP_EOL;
	return;
}
$draft_file = dirname(__FILE__).'/../'.'draft/draft'.$options['i'].'.tpl';
if (!file_exists($draft_file))
{
	echo '指定日志的草稿不存在'.PHP_EOL;
	return;
}
$infos = array();
$infos['draft'] = file_get_contents ($draft_file);
$contents = ZeyuBlogOpt::pre_treat_article ($infos['draft']);
$indexs = json_encode(ZeyuBlogOpt::get_index($contents));
if ($indexs != null)
	$infos['indexs'] = $indexs;
$infos['updatetime'] = 'now()';
$image_ids = array();
while (1)
{
	$image_path = StringOpt::spider_string($contents, 'img<![&&]>src="', '"', $contents);
	if ($image_path === null || $image_path === false || trim($image_path) == '')
		break;
	$image_path = trim($image_path);
	if (!file_exists(dirname(__FILE__).'/'.'../html/'.$image_path))
	{
		LogOpt::set('exception', '文中目标图片不存在', 'image_path', $image_path);
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
			return;
		}
		LogOpt::set('info', '添加图片到数据库成功', 'image_id', $image_id, 'image_path', $image_path);
		$image_ids[] = $image_id;
	}
}

$ret = MySqlOpt::update('article', $infos, array('article_id'=>$options['i']));
if ($ret == null)
{
	LogOpt::set ('exception', 'article 更新失败', 'article_id', $options['i'], MySqlOpt::errno(), MySqlOpt::error());
	return;
}
LogOpt::set ('info', 'article 更新成功', 'article_id', $options['i']);
unlink($draft_file);
?>
