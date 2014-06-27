<?php
require_once (dirname(__FILE__).'/../'.'library/zeyublog.php');
LogOpt::init ('earnings_loader', true);

$options = getopt('m:p:i:e:');

if (!isset($options['m']) || !isset($options['p']) || !isset($options['i']) || !isset($options['e']))
{
	echo "useage: php earnings_loader.php -m month -p imagepath -i income -e expend".PHP_EOL;
	return; 
}

$month = $options['m'];
$imagepath = $options['p'];
$income = (float)$options['i'];
$expend = (float)$options['e'];

if (($image_id = ZeyuBlogOpt::load_image($imagepath, 'earnings')) === false)
{
	LogOpt::set('exception', 'earning image add error');
	return;
}
LogOpt::set('info', 'add image success', 'image_id', $image_id);
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

$query = 'select month,income,expend from earnings';
$infos = MySqlOpt::select_query($query);
if ($infos == null)
{
	LogOpt::set('exception', 'select infos error', MySqlOpt::errno(), MySqlOpt::error());
	return;
}
$income_points = array();
$expend_points = array();
$sub_points = array();
$month = array();
foreach ($infos as $info)
{
	$income_points[] = intval($info['income']);
	$expend_points[] = intval($info['expend']);
	$sub_points[] = intval($info['income']) - intval($info['expend']);
	$month[] = $info['month'];
}

$points = array();
$points[] = array('points'=>$income_points, 'label'=>'income');
$points[] = array('points'=>$sub_points, 'label'=>'subtraction');
$points[] = array('points'=>$expend_points, 'label'=>'expend');
$average = 0;
foreach ($sub_points as $sub)
	$average += $sub;
$average = intval($average/count($sub_points));
$ave_points = array();
foreach ($sub_points as $sub)
	$ave_points[] = $average;
$points[] = array('points'=>$ave_points, 'label'=>'average');

$axis = array('axis_points'=>$month, 'label'=>'month');
unlink($base_dir.'html/images/earning.png');
ZeyuBlogOpt::draw_line_chart($points, 'Income And Expend', $axis, $base_dir.'html/images/earning.png');
?>
