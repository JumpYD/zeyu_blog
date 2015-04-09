<?php
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$conf = file_get_contents('/etc/zeyu203/techlog.conf');
$conf = unserialize(base64_decode($conf));

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
	global $conf;
	$result = array('code'=>1, 'msg'=>'用户不存在');

	if (isset($input['username'])
		&& isset($input['password'])
		&& $input['username'] == $conf['admin']['user']
		&& md5($input['password']) == $conf['admin']['pwd']
	)
	{
		$result['code'] = 0;
		$result['msg'] = '登录成功';
		setcookie('LoginInfo', $conf['admin']['logininfo'], time()+1800);
	}

	echo json_encode($result);
}
?>
