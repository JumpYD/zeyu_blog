<?php
require_once (dirname(__FILE__).'/'.'../library/mysqlopt.php');
require_once (dirname(__FILE__).'/'.'../library/logopt.php');
require_once (dirname(__FILE__).'/'.'../library/pchartopt.php');
require_once (dirname(__FILE__).'/'.'../library/stringopt.php');

class ZeyuBlogOpt
{
	public static function pre_treat_article ($file)
	{
		$lines = explode(PHP_EOL, $file);
		$contents = '';
		$i = 'a';
		$codes = array();
		for ($index=0; $index<count($lines); ++$index)
		{
			$line = $lines[$index];
			$line = trim($line);
			if (empty($line))
				$contents .= '<p>&nbsp;&nbsp;</p>';
			else if ($line == '<div>')
			{
				while (1)
				{
					$index++;
					if ($index >= count($lines))
						break;
					$line = trim($lines[$index]);
					if ($line == '</div>')
						break;
					$contents .= $line.PHP_EOL;
				}
			}
			else if ($line == '<ol>' || $line == '<ul>')
			{
				$contents .= $line;
				while (1)
				{
					$index++;
					if ($index >= count($lines))
						break;
					$line = trim($lines[$index]);
					if ($line == '</ol>' || $line == '</ul>')
					{
						$contents .= $line;
						break;
					}
					else
						$contents .= '<p><li>'.self::str_trans($line).'</li></p>';
				}
			}
			else if (substr($line, 0, 4) == '<img')
			{
				$id = StringOpt::spider_string($line, 'id="', '"');
				if ($id != null)
				{
					$image_id = intval(trim($id));
					$sql = 'select path from images where image_id='.$image_id;
					$path = MySqlOpt::select_query($sql);
					if (isset($path[0]['path']))
					{
						$path = $path[0]['path'];
						$line = str_replace('id="'.$id.'"', 'src="'.$path.'"', $line);
					}
					else
						$line = '<strong>图片ID不存在</strong>';
				}
				$contents .= '<p style="text-indent:0em;">'.$line.'</p><p>&nbsp;&nbsp;</p>';
			}
			else if (substr($line, 0, 5) == '<code')
			{
				$line_sum = StringOpt::spider_string($line, 'line="', '"');
				$line_sum = intval($line_sum);
				$mode = StringOpt::spider_string($line, 'mode="', '"');
				if (empty($mode))
					$mode = 'c_cpp';
				$code = '';
				$code_line = 0;
				while (1)
				{
					$index++;
					if ($index >= count($lines))
						break;
					$line = $lines[$index];
					if (trim($line) === '</code>')
						break;
					$code .= self::str_trans($line, false).PHP_EOL;
					$code_line++;
				}
				$line_sum = $line_sum > $code_line ? $line_sum : $code_line;
				$contents .= '<div id="editor_'.$i.'" style="position: relative; width: 765px; height: '.($line_sum*19+10).'px;">'.trim($code).'</div><p>&nbsp;&nbsp;</p>';
				$codes[] = array('id'=>'editor_'.$i++, 'mode'=>$mode);
				continue;
			}
			else if (substr($line, 0, 4) === '<h1>')
				$contents .= '<div class="page-header"><h1 id="'.$i++.'">'.self::str_trans(substr($line, 4)).'</h1></div>';
			else if (substr($line, 0, 4) === '<h3>')
				$contents .= '<p><h3>'.self::str_trans(substr($line, 4)).'</h3></p>';
			else
				$contents .= '<p>'.self::str_trans($line).'</p>';
		}

		if (!empty($codes))
		{
			$js_arr = array();
			foreach ($codes as $code)
			{
				$js_arr[] = '{"id":"'.$code['id'].'","mode":"'.$code['mode'].'"}';
			}
			$contents .= '<script>var CODE_DIVS=[';
			$contents .= implode(',', $js_arr);
			$contents .= '];</script>';
		}

		return $contents;
	}

	private function str_trans($str, $nbsp = true)
	{
		$str = str_replace('&', '&amp;', $str);
		$str = str_replace('"', '&quot;', $str);
		$str = str_replace('<', '&lt;', $str);
		$str = str_replace('>', '&gt;', $str);
		if ($nbsp)
			$str = str_replace(' ', '&nbsp;&nbsp;', $str);
		return $str;
	}

	public static function getfilepath($file_path)
	{
		$file_path = trim($file_path);
		if ($file_path[0] != '/')
			$file_path = dirname(__FILE__).'/'.$file_path;
		$file_path = str_replace('/./', '/', $file_path);
		while (($current_pos = strpos($file_path, '/../')) !== false)
		{
			$current_path = substr($file_path, 0, $current_pos);
			$last_pos = strrpos($current_path, '/');
			if ($last_pos === false)
				return;
			$last_path = substr($file_path, 0, $last_pos+1);
			$extra_path = substr($file_path, $current_pos+strlen('/../'));
			$file_path = $last_path.$extra_path;
		}
		return $file_path;
	}

	public static function load_image ($path, $category='')
	{
		$file_path = self::getfilepath($path);
		if (!file_exists($file_path))
		{
			echo $file_path.PHP_EOL;
			return false;
		}
		$db_parrams = array();
		$db_parrams['md5'] = md5_file($file_path);
		$pos = strpos($file_path, '/html/');
		if ($pos === false)
			return false;
		$db_parrams['path'] = substr($file_path, $pos+strlen('/html/'));
		$db_parrams['category'] = $category;
		$ret = MySqlOpt::insert('images', $db_parrams, true);
		if ($ret == false)
		{
			LogOpt::set('exception', 'insert_into_images_error', MySqlOpt::errno(), MySqlOpt::error());
		}
		else
		{
			LogOpt::set('info', 'insert_into_images_success', 'image_id', $ret, 'path', $path, 'category', $category);
		}
		return $ret;
	}

	public static function warning_opt ($msg, $url)
	{
		global $smarty;
		$smarty->assign('message', $msg);
		$smarty->assign('url', $url);
		$smarty->display('warning.tpl');
	}

	public static function get_index ($html_str)
	{
		$str = $html_str;
		$index = array();
		while (1)
		{
			$value = '';
			$key = StringOpt::spider_string ($str, '<div', '</div>', $str);
			if ($key === null)
				break;
			if ($key === false)
				return false;
			$key = StringOpt::spider_string ($key, 'class="page-header"<![&&]>id="', '"', $value);
			if ($key === null)
				continue;
			$value = StringOpt::spider_string ($value, '>', '<');
			if ($value === null)
				continue;
			if ($value === false)
				return false;
			$index[$key] = $value;
		}
		return $index;
	}

	public static function get_tags ($article_id)
	{
		$sql = 'select * from article_tag_relation where article_id='.$article_id,' order by inserttime desc';
		$infos = MySqlOpt::select_query($sql);
		if ($infos == null)
			return $infos;
		$tags = array();
		foreach ($infos as $info)
		{
			$tag_id = $info['tag_id'];
			$sql = 'select tag_name from tags where tag_id='.$tag_id;
			$tag_name = MySqlOpt::select_query($sql);
			if ($tag_name == null)
			{
				LogOpt::set ('exception', 'get tag error', 'tag_id', $tag_id, MySqlOpt::errno(), MySqlOpt::error());
				return false;
			}
			$tag_name = $tag_name[0]['tag_name'];
			$tags[] = $tag_name;
		}
		return $tags;
	}

	public static function draw_line_chart ($points, $title, $axis ,$outfile)
	{
		PChartOpt::add_points($points);
		PChartOpt::set_axis_infos($axis);
		PChartOpt::set_sp(false);
		PChartOpt::set_outfile($outfile);
		PChartOpt::draw_line_chart(array(148, 43), $title);
	}
}
?>
