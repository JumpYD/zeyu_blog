<?php
require_once (dirname(__FILE__).'/'.'head.php');
global $smarty;

$query = 'select * from booknote order by idx';
$notes = MySqlOpt::select_query($query);
$booknotes = array();
foreach ($notes as $note)
{
	$infos = array();
	$infos['idx_href'] = 'article.php?id='.$note['index_article_id'];
	$image_select_query = 'select path from images where image_id='.$note['image_id'];
	$image_ret = MySqlOpt::select_query($image_select_query);
	$path = $image_ret[0]['path'];
	$infos['image_path'] = $path;
	$title_select_query = 'select title from article where article_id='.$note['index_article_id'];
	$title = MySqlOpt::select_query($title_select_query);
	$infos['title'] = mb_substr($title[0]['title'], 0, 15);
	$infos['descs'] = mb_substr($note['descs'], 0, 35);
	$booknotes[] = $infos;
}

$smarty->assign('infos', $booknotes);
$smarty->assign('title', '读书笔记');
$smarty->assign('category_id','2');
$smarty->display('note.tpl');
?>
