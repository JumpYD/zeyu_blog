<?php
//调用方式: php note_loader.php -b bookname -i bookimageid [-d desc]
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');
LogOpt::init ('note_loader', true);

$options = getopt('b:i:d:');

if (!isset($options['b']) || !isset($options['i']))
{
	echo "usage: php note_loader.php -b bookname -i bookimageid [-d desc]"
		.PHP_EOL;

	return; 
}

$bookname = $options['b'];
$image_id = intval(trim($options['i']));
$descs = isset($options['d']) ? $options['d'] : null;

$sql = 'select * from images where image_id='.$image_id;
$ret = MySqlOpt::select_query($sql);
if (!isset($ret[0]['image_id']))
{
	LogOpt::set('exception', 'image not exists', 'image_id', $image_id);
	return false;
}
$article_info = array();
$article_info['title'] = $bookname;
$article_info['updatetime'] = 'now()';
$article_info['category_id'] = 2;
$article_id = MySqlOpt::insert('article', $article_info, true);
if ($article_id == false)
{
	LogOpt::set('exception', 'new_note_insert_into_article_error',
		MySqlOpt::errno(), MySqlOpt::error()
	);

	return false;
}
else
{
	LogOpt::set('info', 'new_note_insert_into_article_success',
		'article_id', $article_id);
}
$select_query = 'select article_id from category where category_id=2 limit 1';
$articles = MySqlOpt::select_query($select_query);
if ($articles == false)
{
	LogOpt::set('exception', 'new_note_select_article_from_category_error',
		MySqlOpt::errno(), MySqlOpt::error()
	);

	return false;
}

$articles = $articles[0]['article_id'];
$article_array = json_decode($articles, true);
if ($article_array == null)
	$article_array = array();

$article_array[] = $article_id;
$articles = json_encode($article_array);

$infos = array();
$infos['article_id'] = $articles;
$infos['updatetime'] = 'now()';

$ret = MySqlOpt::update ('category', $infos, array('category_id'=>2));
if ($ret == false)
{
	LogOpt::set('exception', 'new_note_update_category_error',
		MySqlOpt::errno(), MySqlOpt::error()
	);

	return;
}

$infos = array();
$infos['index_article_id'] = $article_id;
$infos['image_id'] = $image_id;
$infos['updatetime'] = 'now()';
if ($descs != null)
	$infos['descs'] = $descs;

$note_id = MySqlOpt::insert('booknote', $infos, true);
if ($note_id == false)
{
	LogOpt::set('exception', 'new_note_insert_into_booknote_error',
		MySqlOpt::errno(), MySqlOpt::error()
	);

	return false;
}
else
{
	LogOpt::set('info', 'new_note_insert_into_booknote_success',
		'note_id', $note_id
	);
}
?>
