<?php
$base_dir = dirname(__FILE__).'/../';
require_once ($base_dir.'library/zeyublog.php');

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action)
{
case 'login':
	login_action($_REQUEST);
	break;
default:
	ZeyuBlogOpt::warning_opt('请填写category参数', '/html');
	break;
}

function login_action ($_REQUEST)
{
	$result = array('code'=>1, 'msg'=>'用户不存在');

	if (isset($_REQUEST['username'])
		&& isset($_REQUEST['password'])
		&& $_REQUEST['username'] == 'zeyu'
		&& md5($_REQUEST['password']) == '980bf0dee3d81c40c1a17393c680a014'
	)
	{
		$result['code'] = 0;
		$result['msg'] = '登录成功';
		setcookie('LogInfo', 'admin519ca7b3591e6844af3c875cb61d0d64', time()+3600);
	}

	echo json_encode($result);
}
?>
