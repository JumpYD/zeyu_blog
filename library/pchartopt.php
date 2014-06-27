<?php
$base_dir = dirname(__FILE__).'/../';
require_once($base_dir.'resource/pChart/class/pData.class.php');
require_once($base_dir.'resource/pChart/class/pDraw.class.php');
require_once($base_dir.'resource/pChart/class/pImage.class.php');

class PChartOpt
{
	private static $pdata = null;
	private static $picture = null;
	private static $outfile = null;
	private static $picture_info = array('coordinate'=>array(0, 0, 770, 253), 'color'=>array("R"=>255, "G"=>255, "B"=>255));
	private static $gradient_info = array('coordinate'=>array(0, 0, 770, 253), 'color'=>array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>70));
	private static $rectangle_info = array('coordinate'=>array(0, 0, 769, 252), 'color'=>array("R"=>0,"G"=>0,"B"=>0));
	private static $graph_info = array('coordinate'=>array(40, 60, 730, 213), 'color'=>array("R"=>0,"G"=>82,"B"=>121, "Surrounding"=>-200,"Alpha"=>90));
	private static $lengend_info = array('coordinate'=>array(610, 18), 'info'=>array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_VERTICAL));
	private static $draw_spline = true;

	public static function get_pdata()
	{
		if (self::$pdata === null)
			self::$pdata = new pData();
		return self::$pdata;
	}

	public static function add_points($points)
	{
		if (self::get_pdata() === null)
			return false;
		foreach($points as $infos)
		{
			self::$pdata->addPoints($infos['points'], $infos['label']);
			// 绘制虚线用，参数为虚线中实线长度
			if (isset($infos['ticks']))
			{
				self::$padata->setSerieTicks($infos['label'], $infos['ticks']);
			}
		}
		return true;
	}

	public static function set_axis_infos($infos)
	{
		if (self::get_pdata() === null)
			return false;
		if (isset($infos['name']))
		{
			self::$pdata->setAxisName(0, $infos['name']);
		}
		if (isset($infos['axis_points']))
		{
			$points = array();
			$points[0]['points'] = $infos['axis_points'];
			$points[0]['label'] = 'Labels';
			self::add_points($points);
			self::$pdata->setAbscissa('Labels');
		}
	}

	public static function set_picture($infos)
	{
		foreach ($infos as $key=>$value)
			self::$picture_info[$key] = $value;
	}

	public static function set_gradient($infos)
	{
		foreach ($infos as $key=>$value)
			self::$gradient_info[$key] = $value;
	}

	public static function set_rectangle($infos)
	{
		foreach ($infos as $key=>$value)
			self::$rectangle_info[$key] = $value;
	}

	public static function set_graph($infos)
	{
		foreach ($infos as $key=>$value)
			self::$graph_info[$key] = $value;
	}

	public static function set_legend($infos)
	{
		foreach ($infos as $key=>$value)
			self::$legend_info[$key] = $value;
	}

	public static function set_sp ($draw_sp)
	{
		self::$draw_spline = $draw_sp;
	}

	public static function set_outfile ($outfile)
	{
		self::$outfile = $outfile;
	}

	public static function draw_line_chart ($position, $title)
	{
		global $base_dir;
		if (self::get_pdata() === null)
			return false;
		$pimage = new pImage(self::$picture_info['coordinate'][2]-self::$picture_info['coordinate'][0], self::$picture_info['coordinate'][3]-self::$picture_info['coordinate'][1], self::$pdata);

		$pimage->drawFilledRectangle(self::$picture_info['coordinate'][0], self::$picture_info['coordinate'][1], self::$picture_info['coordinate'][2], self::$picture_info['coordinate'][3], self::$picture_info['color']);

		$pimage->drawGradientArea(self::$gradient_info['coordinate'][0], self::$gradient_info['coordinate'][1], self::$gradient_info['coordinate'][2], self::$gradient_info['coordinate'][3], self::$gradient_info['color']);

		$pimage->drawRectangle(self::$rectangle_info['coordinate'][0], self::$rectangle_info['coordinate'][1], self::$rectangle_info['coordinate'][2], self::$rectangle_info['coordinate'][3], self::$rectangle_info['color']);

		$pimage->setFontProperties(array("FontName"=>$base_dir."library/pChart/fonts/Forgotte.ttf","FontSize"=>11));
		$pimage->drawText($position[0], $position[1], $title, array("FontSize"=>20, "Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

		$pimage->setGraphArea(self::$graph_info['coordinate'][0], self::$graph_info['coordinate'][1], self::$graph_info['coordinate'][2], self::$graph_info['coordinate'][3]);
		$pimage->drawFilledRectangle(self::$graph_info['coordinate'][0], self::$graph_info['coordinate'][1], self::$graph_info['coordinate'][2], self::$graph_info['coordinate'][3], self::$graph_info['color']);
		$pimage->drawScale(array("DrawSubTicks"=>TRUE));
		$pimage->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
		$pimage->setFontProperties(array("FontName"=>$base_dir."library/pChart/fonts/pf_arma_five.ttf","FontSize"=>10));
		if (self::$draw_spline)
			$pimage->drawSpLineChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));
		else
			$pimage->drawLineChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));

		$pimage->setShadow(FALSE);

		$pimage->drawLegend(self::$lengend_info['coordinate'][0], self::$lengend_info['coordinate'][1], self::$lengend_info['info']);

		$pimage->autoOutput(self::$outfile);
	}
}
?>
