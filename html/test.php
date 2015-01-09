<?php
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;

if (!$is_root)
{
	ZeyuBlogOpt::warning_opt('访问的页面不存在', 'index.php');
	return;
}

$draft_id = $_GET['id'];

$contents = ZeyuBlogOpt::pre_treat_article(
	file_get_contents(dirname(__FILE__).'/../'.'draft/draft'.$draft_id.'.tpl')
);

if (StringOpt::spider_string($contents, '"page-header"', '</div>') === null)
{
	$contents = '<div class="page-header"><h1>草稿'
		.$draft_id.'</h1></div>'.$contents;
}

$index = ZeyuBlogOpt::get_index($contents);

if (count($index) > 0)
	$smarty->assign('index', $index);
$smarty->assign('title', '测试页面');
$smarty->assign('title_desc', '仅供测试');
$smarty->assign('contents', $contents);
$smarty->display('article.tpl');
?>
