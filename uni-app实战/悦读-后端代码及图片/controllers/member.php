<?php
namespace hsC;
class member{
	
	public function wxaes(){
		if(empty($_POST['session_key']) || empty($_POST['encryptedData']) || empty($_POST['iv'])){exit(jsonCode('error', 'data error'));}
		include HS_TOOLS.'WXBizDataCrypt.php';
		$pc = new \WXBizDataCrypt(HS_APPID, $_POST['session_key']);
		$data = '';
        $errCode = $pc->decryptData($_POST['encryptedData'], $_POST['iv'], $data);
        if ($errCode == 0) {
            exit($data);
        } else {
            exit(jsonCode('error', $errCode));
        }
	}
	
	public function codeToSession(){
		$url =  "https://api.weixin.qq.com/sns/jscode2session?appid=".HS_APPID.
        "&secret=".HS_SECRET."&js_code=".$_GET['code']."&grant_type=authorization_code";
		
		$curl = new \hsTool\curl();
		$res = $curl->get($url);
		echo $res;
	}
	
	public function index(){
		
	}
	
	public function login(){
		// 签名验证
		//checkSign();
		//调用模型完成用户登录及注册
        $memberModel = new \hsModel\member();
        $memberModel->login();
	}
}