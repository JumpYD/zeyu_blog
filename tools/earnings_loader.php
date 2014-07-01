<?php
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');
LogOpt::init ('earnings_loader', true);

$options = getopt('m:a:i:e:');

if (!isset($options['m']) || !isset($options['a']) || !isset($options['i']) || !isset($options['e']))
{
	echo "useage: php earnings_loader.php -m month -a image_id -i income -e expend".PHP_EOL;
	return; 
}

$month = $options['m'];
$income = (float)$options['i'];
$expend = (float)$options['e'];
$image_id = (int)$options['a'];

$sql = 'select path from images where image_id = '.$image_id;
$path = MySqlOpt::select_query($sql);
if (!isset($path[0]['path']))
{
	LogOpt::set('exception', 'image_not_exists', MySqlOpt::errno(), MySqlOpt::error());
	return false;
}

$article_info = array();
$article_info['title'] = $month.'财报';
$article_info['updatetime'] = 'now()';
$article_info['category_id'] = 6;
$article_id = MySqlOpt::insert('article', $article_info, true);
if ($article_id == false)
{
	LogOpt::set('exception', 'new_note_insert_into_article_error', MySqlOpt::errno(), MySqlOpt::error());
	return false;
}
else
{
	LogOpt::set('info', 'new_note_insert_into_article_success', 'article_id', $article_id);
}
$select_query = 'select article_id from category where category_id=6';
$articles = MySqlOpt::select_query($select_query);
if ($articles == false)
{
	LogOpt::set('exception', 'new_earnings_select_article_from_category_error', MySqlOpt::errno(), MySqlOpt::error());
	return false;
}
$infos = array();
$articles = $articles[0]['article_id'];
$article_array = json_decode($articles, true);
if ($article_array == null)
	$article_array = array();
$article_array[] = $article_id;
$infos['article_count'] = count($article_array);
$articles = json_encode($article_array);
$infos['article_id'] = $articles;
$infos['updatetime'] = 'now()';
$ret = MySqlOpt::update ('category', $infos, array('category_id'=>6));
if ($ret == false)
{
	LogOpt::set('exception', 'new_note_update_category_error', MySqlOpt::errno(), MySqlOpt::error());
	return;
}
$infos = array();
$infos['article_id'] = $article_id;
$infos['image_id'] = $image_id;
$infos['month'] = $month;
$infos['income'] = $income;
$infos['expend'] = $expend;
$earnings_id = MySqlOpt::insert('earnings', $infos, true);
if ($earnings_id == false)
{
	LogOpt::set('exception', 'new_earnings_insert_into_booknote_error', MySqlOpt::errno(), MySqlOpt::error());
	return false;
}
else
{
	LogOpt::set('info', 'new_earnings_insert_into_booknote_success', 'earnings_id', $earnings_id);
}
?>
