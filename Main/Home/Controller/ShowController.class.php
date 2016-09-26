<?php
namespace Home\Controller;
use Think\Controller;

class ShowController extends Controller {
	public function index() {
		echo 'show模块';
	}
	public function createimg() {
		import("Org.Util.Chart");
		$chart = new \Chart();
		$title = ""; //标题
		$data = array(20, 27, 45, 75, 90, 10, 80, 100); //数据
		$size = 500; //尺寸
		$width = 750; //宽度
		$height = 350; //高度
		$legend = array("哥哥 ", "bbbb", "cccc", "dddd ", "eeee", "ffff", "gggg", "hhhh"); //说明
		$chart->createcolumnar($title, $data, $size, $height, $width, $legend);
	}
}
?>
