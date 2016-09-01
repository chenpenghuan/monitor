<?php
namespace Home\Controller;
use Think\Controller;

class WarnController extends Controller {
	//读取报警相关配置
	public function readwarn() {
		$wf = M()->query('select * from warn_conf');
		var_dump($wf);
		exit();
	}
	//读取报警记录
	public function readhist() {
		$wc = M()->query('select warn_cont.warn_date,warn_conf.warn_type,warn_cont.warn_cont from warn_cont left join warn_conf on warn_conf.id=warn_cont.warn_id');
		var_dump($wc);
		exit();
	}
}
?>