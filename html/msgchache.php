<?php
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($action)
{
case 'login':
	login_action($_REQUEST);
	break;
default:
	echo json_encode(array('code'=>1, 'msg'=>'请填写action参数'));
	break;
}

function login_action ($input)
{
	$result = array('code'=>1, 'msg'=>'用户不存在');

	if (isset($input['username'])
		&& isset($input['password'])
		&& $input['username'] == 'zeyu'
		&& md5($input['password']) == '980bf0dee3d81c40c1a17393c680a014'
	)
	{
		$result['code'] = 0;
		$result['msg'] = '登录成功';
		setcookie('LogInfo', 'admin519ca7b3591e6844af3c875cb61d0d64', time()+3600);
	}

	echo json_encode($result);
}
?>
