<?php
namespace hsC;
//控制器的名字要和文件名一致
class member{
	//index方法是客户端请求时无需传递任何参数的
	public function index(){
		
	}
	
	//百度查找-获取openid
	public function openId(){
		$appid = 'wx77b8392bac80a767'; // 小程序APPID
        $secret = '2cabc4b181fbb47f24521e4407ceb936'; // 小程序secret
        $code = $_GET['code'];//小程序传来的code值
        
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';    
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);

        exit(jsonCode('ok', $res));// 这里是获取到的信息
	}
	
	//课程提供获取openid的方法
	public function codeToSession(){
		$code = $_GET['code'];//小程序传来的code值
		//HS_APPID是在index.PHP文件定义的常量，可以直接使用
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . HS_APPID .
		'&secret='.HS_SECRET.'&js_code='.$code.'&grant_type=authorization_code';
		
		$curl = new \hsTool\curl;
		$res = $curl->get($url);
		exit($res);
	}
	
		//检测是否登录
	public function login(){
		//签名认证
		checkSign();
		//调用模型完成用户登录及注册
		$memberModel = new \hsModel\member();
		$memberModel->login();
	}
}
?>