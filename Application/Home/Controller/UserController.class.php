<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){
		$this->display('index');
	}
	public function login(){
		$this->display('login');
	}
		public function chkimg() {
		$Verify =new \Think\Verify();
		$Verify->fontSize = 30;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->entry();
	}
	private function check_verify($code, $id = '') {
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
	public function chklogin() {
		if($_POST){
			if($this->check_verify($code=$_POST['verify'])){
				if($_POST['username']=='adm' & $_POST['password']=='adm'){
					$_SESSION['username']=$_POST['username'];
					header('Location:index');
				}else{
					$this->show('<script>alert("用户名或密码错误！")</script>','utf-8');
					$this->display('login');
				}
			}else{
				$this->show('<script>alert("验证码错误！")</script>','utf-8');
				$this->display('login');
			}
		}
	}
	public function chkstatus() {
		if(!isset($_SESSION['username'])){
			echo 'logout';
			exit();
		}
	}
	public function getajax(){
		if($_POST['func']=='f1'){
			echo '<button onclick="loadXMLDoc(\'f2\')">f2</button>';
		}else{
			echo '<button onclick="loadXMLDoc(\'f1\')">f1</button>';
		}
	}
	public function createimg(){
		import("Org.Util.Chart");
		$chart = new \Chart();
		$title = "柱状图"; //标题
		$data = array(20,27,45,75,90,10,80,100); //数据
		$size = 140; //尺寸
		$width = 750; //宽度
		$height = 350; //高度
		$legend = array("哥哥 ","bbbb","cccc","dddd ","eeee ","ffff ","gggg ","hhhh ");//说明
		$chart->createcolumnar($title,$data,$size,$height,$width,$legend);
	}
}
?>
