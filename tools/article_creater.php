<?php
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');

LogOpt::init('article_creater', true);

$options = getopt('i:t:g:c:d:a:');
if (!isset($options['t']) || !isset($options['g']) || !isset($options['c']))
{
	echo 'usage: php article_creater.php [-a inserttime] [-i article_id] -t title [-d title_desc] -g tags -c category'.PHP_EOL;
	return;
}

if (isset($options['i']))
	$draft_file = dirname(__FILE__).'/../'.'draft/draft'.$options['i'].'.tpl';
else
	$draft_file = dirname(__FILE__).'/../'.'draft/draft.tpl';

if (!file_exists($draft_file))
{
	echo '指定日志的草稿不存在'.PHP_EOL;
	return;
}

$infos = array();

// 获取 contents
$infos['draft'] = file_get_contents($draft_file);
$temp_contents = ZeyuBlogOpt::pre_treat_article($infos['draft']);

// 获取 images
$image_ids = array();
while (1)
{
	$image_path = StringOpt::spider_string($temp_contents, 'img<![&&]>src="', '"', $temp_contents);
	if ($image_path === null || $image_path === false || trim($image_path) == '')
		break;
	$image_path = trim($image_path);
	if (!file_exists(dirname(__FILE__).'/'.'../html/'.$image_path))
	{
		echo '文中目标图片不存在'."\t".$image_path.PHP_EOL;
		return;
	}
	$query = 'select image_id from images where path="'.$image_path.'"';
	$image_id = MySqlOpt::select_query($query);
	if ($image_id == null)
	{
		$full_path = dirname(__FILE__).'/../'.'html/'.$image_path;
		$image_id = ZeyuBlogOpt::load_image ($full_path, 'article');
		if ($image_id == null)
		{
			LogOpt::set('exception', '添加图片到数据库失败', 'image_path', $image_path, MySqlOpt::errno(), MySqlOpt::error());
			return;
		}
		LogOpt::set('info', '添加图片到数据库成功', 'image_id', $image_id, 'image_path', $image_path);
		$image_ids[] = $image_id;
	}
	$image_ids[] = $image_id[0]['image_id'];
}
$infos['images'] = json_encode($image_ids);

// 获取 category_id
$query = 'select category_id,article_id from category where category="'.$options['c'].'"';
$category_info = MySqlOpt::select_query($query);
if ($category_info == null)
{
	echo '指定category不存在'."\t".$options['c']."\t".PHP_EOL;
	return;
}
$infos['category_id'] = $category_info[0]['category_id'];

// 获取 tags 
$tags = explode(',', $options['g']);
if ($tags == null)
{
	echo 'tags 参数有误'."\t".$tags.PHP_EOL;
	return;
}
$tag_ids = array();
foreach ($tags as $tag)
{
	$tag = trim($tag);
	if ($tag == '')
		continue;
	$query = 'select tag_id from tags where tag_name="'.$tag.'"';
	$tag_infos = MySqlOpt::select_query($query);
	if ($tag_infos == null)
	{
		$tag_id = MySqlOpt::insert('tags', array('tag_name'=>$tag), true);
		if ($tag_id == false)
		{
			LogOpt::set ('exception', 'tag 添加失败', MySqlOpt::errno(), MySqlOpt::error());
			return;
		}
	}
	else
		$tag_id = $tag_infos[0]['tag_id'];
	$tag_ids[] = $tag_id;
}
$infos['tags'] = json_encode($tag_ids);

// 获取 index
$indexs = json_encode(ZeyuBlogOpt::get_index($temp_contents));
if ($indexs != null)
	$infos['indexs'] = $indexs;

// 获取 title、title_desc、updatetime
$infos['title'] = $options['t'];
if (isset($options['d']))
	$infos['title_desc'] = $options['d'];
$infos['updatetime'] = 'now()';

// 设置inserttime
if (isset($options['a']))
{
	$infos['inserttime'] = $options['a'];
}

// 插入日志
if (isset($options['i']))
{
	$ret = MySqlOpt::update('article', $infos, array('article_id'=>$options['i']));
	if ($ret == null)
	{
		LogOpt::set ('exception', '日志更新失败', 'article_id', $options['i'], MySqlOpt::errno(), MySqlOpt::error());
		return;
	}
	$article_id = $options['i'];
}
else
{
	$article_id = MySqlOpt::insert('article', $infos, true);
	if ($article_id == false)
	{
		LogOpt::set ('exception', '日志插入失败', MySqlOpt::errno().':'.MySqlOpt::error());
		return;
	}
}
LogOpt::set ('info', '日志插入成功', 'article_id', $article_id, 'title', $options['t']);
unlink($draft_file);

// 添加 article 并获取新加 article_id 后需要更新为 tags 表对应项
foreach ($tag_ids as $tag_id)
{
	$query = 'select article_id,tag_name from tags where tag_id="'.$tag_id.'"';
	$article_id_ret = MySqlOpt::select_query($query);
	if ($article_id_ret == null)
	{
		LogOpt::set ('exception', 'tags 查询失败', 'tag_id', $tag_id);
		return;
	}
	$article_ids = json_decode($article_id_ret[0]['article_id'], true);
	$tag_name = $article_id_ret[0]['tag_name'];
	if ($article_ids == null)
		$article_ids = array();
	if (!in_array($article_id, $article_ids))
	{
		$infos = array();
		$article_ids[] = $article_id;
		$infos['article_id'] = json_encode($article_ids);
		$infos['article_count'] = count($article_ids);
		$ret = MySqlOpt::update('tags', $infos, array('tag_id'=>$tag_id));
		if ($ret == null)
		{
			LogOpt::set ('exception', 'tags 更新失败', 'tag_id', $tag_id, 'tag_name', $tag_name, 'article_ids', json_encode($article_ids), 'article_count', count($article_ids));
			return;
		}
		LogOpt::set ('info', 'tags.article_id 更新成功', 'tag_id', $tag_id, 'tag_name', $tag_name, 'article_ids', json_encode($article_ids), 'article_count', count($article_ids));
	}
	else
		LogOpt::set ('info', 'tags.article_id 已存在', 'article_id', $article_id, 'tag_id', $tag_id, 'tag_name', $tag_name, 'article_ids', json_encode($article_ids), 'article_count', count($article_ids));
}

// 添加 article 并获取新加 article_id 后需要更新为 category 表对应项
$articles_category = json_decode($category_info[0]['article_id'], true);
if ($articles_category == null || !is_array($articles_category))
	$articles_category = array();
if (!in_array($article_id, $articles_category))
{
	$articles_category[] = $article_id;
	$articles = json_encode($articles_category);
	$ret = MySqlOpt::update('category', array('article_id'=>$articles, 'article_count'=>count($articles_category), 'updatetime'=>'now()'), array('category_id'=>$category_info[0]['category_id']));
	if ($ret == null)
	{
		LogOpt::set ('exception', 'category 更新失败', 'category_id', $category_info[0]['category_id'], 'article_ids', $articles, MySqlOpt::errno(), MySqlOpt::error());
		return;
	}
	LogOpt::set ('info', 'category.article_id 更新成功', 'categroy_id', $category_info[0]['category_id'], 'article_ids', $articles, 'article_count', count($articles));
}
else
	LogOpt::set ('info', 'category.article_id 已存在 ', 'article_id', $article_id);
?>
