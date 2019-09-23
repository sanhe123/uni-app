/**
 * @desc 登录检测函数
 * @param backPage 返回页面
 * @param backType 返回类型 [1 : redirectTo 2 : switchTab]
 */
function checkLogin(backPage,backType){
	//同步方式获取本地存储的数据
	let SUID = uni.getStorageSync('SUID');
	let SRAND = uni.getStorageSync('SRAND');//随机码
	let SNAME = uni.getStorageSync('SNAME');//name
	let SFACE = uni.getStorageSync('SFACE');//face
	if(SUID == '' || SRAND == '' || SNAME == '' || SFACE == ''){
		//重定向到登录页面
		uni.redirectTo({
			url:"../login/login?backPage="+backPage+"&backType="+backType
		})
		return false;
	}
	return [SUID,SRAND,SNAME,SFACE];
}

var APITOKEN = 'api2018';
var apiServer = 'http://192.168.1.180/index.php?token='+APITOKEN+"&c=";

function postRequest({url,method,data,callBack}){
	uni.request({
		url: apiServer + url,
		method: 'POST',
		header: {'content-type' : "application/x-www-form-urlencoded"},
		data: data,
		success: res => {
			res = res.data;
			if(res.status === 'ok'){
				callBack(res.data);
			}else{
				callBack(res.error);
			}
		}
	}); 
}


module.exports ={
	checkLogin:checkLogin,
	apiServer:apiServer,
	postRequest:postRequest
}