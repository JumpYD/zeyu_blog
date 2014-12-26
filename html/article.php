<?php
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;
LogOpt::init('display_article');

$article_id = intval($_GET['id']);

$article_query = 'select * from article where article_id='.$article_id;
if (!$is_root)
	$article_query .= ' and category_id < 5';

$article_info = MySqlOpt::select_query($article_query);

if ($article_info == false)
	ZeyuBlogOpt::warning_opt('访问的页面不存在', 'index.php');

$article_info = $article_info[0];
$indexs = json_decode($article_info['indexs']);
if ($indexs != null)
{
	$smarty->assign('index', $indexs);
}

$tags = ZeyuBlogOpt::get_tags($article_id);
if ($tags != null)
	$smarty->assign('tags', $tags);
$contents = ZeyuBlogOpt::pre_treat_article($article_info['draft']);
if (StringOpt::spider_string($contents, '"page-header"', '</div>') === null)
{
	$contents = '<div class="page-header"><h1>'
		.$article_info['title']
		.'</h1></div>'.$contents;
}

$sql = 'update article set access_count=access_count+1 where article_id = "'.$article_id.'"';
MySqlOpt::update_query($sql);

$time = $article_info['inserttime']
	.'&nbsp;&nbsp;&nbsp;最后更新: '
	.$article_info['updatetime']
	.'&nbsp;&nbsp;&nbsp;访问数量：'
	.($article_info['access_count']+1);

$smarty->assign('inserttime', $time);
$smarty->assign('title', $article_info['title']);
$smarty->assign('title_desc', $article_info['title_desc']);
$smarty->assign('contents', $contents);
$smarty->assign('category_id', $article_info['category_id']);
$smarty->display('article.tpl');
?>
