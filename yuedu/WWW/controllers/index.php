<?php
/**
 * 访问某一个控制器的时候在url后面加&c=index
 * 访问控制器中的某个方法在url后面加&c=index&m=test
 * 访问别的控制器的时候在url后面加&c=控制器名，此时直接访问该控制器下的index方法
 * 结尾不加任何东西直接访问index控制器下的index方法
 */
namespace hsC;
class index{//定义一个控制器
	//定义一个方法
	public function index(){
		exit(jsonCode('ok','api 1.0.1'));
	}
}
?>