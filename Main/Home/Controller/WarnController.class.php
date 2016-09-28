<?php
namespace Home\Controller;
use Think\Controller;

class WarnController extends Controller {
	//读取报警相关配置
	public function readwarn() {
		//$wf = M()->query('select id,warn_type,warn_prog,warn_conf,warn_send from warn_conf');
		$wf = F('warn_conf');
		//报警ID 报警类型 级别 参数名 参数阀值 判断逻辑
		$tbhead = '<div id="delmsg"><table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr><th>报警ID</th><th>报警类型</th><th>报警级别</th><th>报警参数</th><th>报警阀值</th><th>关系逻辑</th><th>报警对象</th><th>管理/<button onclick=create_warn()>新建</button></th></tr></thead>';
		$trs = '';
		foreach ($wf as $k => $v) {
			$trs .= '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_key'] . '</td><td>' . $v['warn_value'] . '</td><td>' . $v['warn_logic'] . '</td><td>' . $v['warn_send'] . '</td><td><button onclick=foritems2(all="act=edit&wk=' . $k . '",url="' . C('MOD_WARN') . '/editwarn",outid="create_warn",warn="N",warnword="确定提交吗？",add="数据库错误，请检查添加信息！")>修改</button><button onclick=foritems2(all="act=del&id=' . $v['id'] . '",url="' . C('MOD_WARN') . '/delwarn",outid="create_warn",warn="Y",warnword="确定提交吗？",add="数据库错误，请检查添加信息！")>删除</button></td></tr>';
		}
		$tbfoot = '</table></div><div id="create_warn"></div>';
		echo $tbhead . $trs . $tbfoot;
		exit();
	}
	//编辑已有报警设置
	public function editwarn() {
		$warn_conf = F('warn_conf');
		$form = '<form class="form-horizontal" role="form" style="padding-left:10%;padding-top:3%;width:80%;"><div class="form-group"><label for="firstname" class="col-sm-2 control-label">报警ID</label><div class="col-sm-10"><input id="warn_id" type="text" class="form-control" readonly="true" value="' . $warn_conf[$_POST['wk']]['id'] . '"></div></div><div class="form-group"><label for="firstname" class="col-sm-2 control-label">报警类型</label><div class="col-sm-10"><input id="warn_type" type="text" class="form-control" value="' . $warn_conf[$_POST['wk']]['warn_type'] . '"></div></div><div class="form-group"><label for="lastname" class="col-sm-2 control-label">报警级别</label><div class="col-sm-10"><input type="text" class="form-control" id="warn_level" value="' . $warn_conf[$_POST['wk']]['warn_level'] . '"></div></div><div class="form-group"><label for="lastname" class="col-sm-2 control-label">报警参数</label><div class="col-sm-10"><input id="warn_key" type="text" class="form-control" value=' . $warn_conf[$_POST['wk']]['warn_key'] . '></div></div><div class="form-group"><label for="lastname" class="col-sm-2 control-label">报警阀值</label><div class="col-sm-10"><input id="warn_value" type="text" class="form-control" value=' . $warn_conf[$_POST['wk']]['warn_value'] . '></div></div><div class="form-group"><label for="lastname" class="col-sm-2 control-label">关系逻辑</label><div class="col-sm-10"><input id="warn_logic" type="text" class="form-control" value=' . $warn_conf[$_POST['wk']]['warn_logic'] . '></div></div><div class="form-group"><label for="lastname" class="col-sm-2 control-label">报警对象</label><div class="col-sm-10"><input id="warn_send" type="text" class="form-control" value=' . $warn_conf[$_POST['wk']]['warn_send'] . '></div></div><div class="form-group"><div class="col-sm-offset-2 col-sm-10"><button onclick=foritems2(all="warn_id="+document.getElementById("warn_id").value+"&warn_type="+document.getElementById("warn_type").value+"&warn_level="+document.getElementById("warn_level").value+"&warn_key="+document.getElementById("warn_key").value+"&warn_value="+document.getElementById("warn_value").value+"&warn_logic="+document.getElementById("warn_logic").value+"&warn_send="+document.getElementById("warn_send").value,url="' . C('MOD_WARN') . '/saveedit",outid="edit_warn_info",warn="Y",warnword="确定提交吗？",add="数据库错误，请检查添加信息！") type="button" class="btn btn-default">提交</button><button type="button" class="btn btn-default" onclick=document.getElementById("create_warn").innerHTML="" >返回</button></div></div></form><div id="edit_warn_info" style="padding-left:15%;padding-top:3%;width:70%;color:#ff0000;"></div>';
		echo $form;
		exit();
	}
	//保存对已有报警设置的编辑
	public function saveedit() {
		if ($_POST['warn_type'] && $_POST['warn_level'] && $_POST['warn_key'] && $_POST['warn_value'] && $_POST['warn_logic'] && $_POST['warn_send']) {
			/*
				$sql = 'update warn_conf set warn_type=\'' . $_POST['warn_type'] . '\',warn_prog=\'' . $_POST['warn_prog'] . '\',warn_conf=\'' . $_POST['warn_conf'] . '\',warn_send=\'' . $_POST['warn_send'] . '\' where id=' . $_POST['warn_id'];
			*/
			$sql = 'update warn_conf set warn_type=\'' . $_POST['warn_type'] . '\',warn_level=' . $_POST['warn_level'] . ',warn_key=\'' . $_POST['warn_key'] . '\',warn_value=\'' . $_POST['warn_value'] . '\',warn_logic=' . $_POST['warn_logic'] . ',warn_send=\'' . $_POST['warn_send'] . '\' where id=' . $_POST['warn_id'];
			file_put_contents(C('WARN_CONF'), ''); //将后台服务的配置文件置空
			M()->execute($sql);
			echo '修改成功，刷新后即可显示！';
		} else {
			echo '请不要留空内容';
		}
		exit();
	}
	//删除已有报警配置
	public function delwarn() {
		if ($_POST['id']) {
			$sql = 'delete from warn_conf where id=' . $_POST['id'];
			file_put_contents(C('WARN_CONF'), ''); //将后台服务的配置文件置空
			M()->execute($sql);
			echo '删除成功，刷新后即可显示！';
		} else {
			echo '没有发现该报警ID，请刷新后重试！';
		}
		exit();
	}
	//保存新增报警配置
	public function savecreate() {
		//var_dump($_POST);
		try {
			if ($_POST['warn_type'] && $_POST['warn_level'] && $_POST['warn_key'] && $_POST['warn_value'] && $_POST['warn_logic'] && $_POST['warn_send']) {
				//$sql = 'insert into warn_conf(warn_type,warn_prog,warn_conf,warn_send) values(\'' . $_POST['warn_type'] . '\',\'' . $_POST['warn_prog'] . '\',\'' . $_POST['warn_conf'] . '\',\'' . $_POST['warn_send'] . '\')';
				$sql = 'insert into warn_conf(warn_type,warn_level,warn_key,warn_value,warn_logic,warn_send) values("' . $_POST['warn_type'] . '",' . $_POST['warn_level'] . ',"' . $_POST['warn_key'] . '","' . $_POST['warn_value'] . '",' . $_POST['warn_logic'] . ',"' . $_POST['warn_send'] . '")';
				//echo $sql;
				//@unlink(C('WARN_CONF'));
				file_put_contents(C('WARN_CONF'), '');
				$result = M()->execute($sql);
				if ($result) {
					//file_put_contents(C('WARN_STATUS'), json_encode(array("status" => 0)));
					echo '添加成功，<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>后即可显示！';
				} else {
					echo '添加失败,请联系管理员！';
				}
			} else {
				//echo '报警类型和报警程序不能为空';
				echo '请不要输入空内容';
			}
		} catch (Exception $e) {
			var_dump($e);
			//echo '新增报警失败:' . $e->getMessage();
		}
		exit();
	}
	//读取报警记录
	public function readhist() {
		$wc = F('warn_cont');
		$options = array("warn_level" => "报警级别", "warn_type" => "报警类型", "warn_cont" => "报警内容");
		$optstr = '';
		foreach ($options as $k => $v) {
			if ($k == $_POST['key']) {
				$optstr .= '<option value="' . $_POST['key'] . '" selected="selected">' . $v . '</option>';
			} else {
				$optstr .= '<option value="' . $k . '">' . $v . '</option>';
			}
		}
		$select = '<div class="form-group"> <div class="input-group col-xs-12"><span class="input-group-btn"><select id="colname" class="form-control" style="width: auto;">' . $optstr . '</select></span><input type="text" name="keyword" id="keyword" class="form-control" placeholder="请输入关键词" value="' . $_POST['value'] . '"><span class="input-group-btn"><button class="btn btn-success" onclick=foritems2(all="key="+document.getElementById("colname").value+"&value="+document.getElementById("keyword").value,url="/monitor/index.php/warn/readhist",outid="result",warn="N",warnword="确定吗？",add="正在加载。。。")>搜索</button></span></div></div>';
		//var_dump($_POST);
		echo $select;
		$tbhead = '<div id="delmsg"><table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr><th>报警ID</th><th>报警日期</th><th>报警级别</th><th>报警类型</th><th>报警内容</th></tr></thead>';
		//分页部分

		foreach ($wc as $k => $v) {
			//取出所有符合搜索条件的内容
			if ($_POST['value'] != "") {
				if ($_POST['key'] == 'level') {
					if ($v[$_POST['key']] == $_POST['value']) {
						$trs[] = '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
					}
				} else {
					if (strstr($v[$_POST['key']], $_POST['value'])) {
						//echo "进来了";
						$trs[] = '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
					}
				}
			} else {
				$trs[] = '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
			}
		}
		$strs_c = count($trs);
		$pages = (intval(count($trs) / 13) + 1);
		if ($_POST['page'] > 1) {
			//当前分页大于总页数
			if ($_POST['page'] >= $pages) {
				$page = $pages;
			} else {
				$page = $_POST['page'];
			}
			$start = ($page - 1) * 13;
			$stop = $page * 13-1;
		} else {
			$page = 1;
			$start = 0;
			$stop = 12;
		}
		$tbbody = '';
		for ($i = 0; $i < $strs_c; $i++) {
			//取出行号在此页中的
			if ($start <= $i && $i <= $stop) {
				$tbbody .= $trs[$i];
			}
		}

/*
foreach ($wc as $k => $v) {
//$k为行号
if ($start < $k && $k < $stop) {
if ($_POST['value'] != "") {
if ($_POST['key'] == 'level') {
if ($v[$_POST['key']] == $_POST['value']) {
$trs .= '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
}
} else {
if (strstr($v[$_POST['key']], $_POST['value'])) {
//echo "进来了";
$trs .= '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
}
}
} else {
$trs .= '<tr><td>' . $v['id'] . '</td><td>' . $v['warn_date'] . '</td><td>' . $v['warn_level'] . '</td><td>' . $v['warn_type'] . '</td><td>' . $v['warn_cont'] . '</td></tr>';
}
}
}
 */
		$tbfoot = '</table></div>';
		echo $tbhead . $tbbody . $tbfoot;
		if ($strs_c == 0) {
			echo '<h2>没有符合搜索条件的内容</h2>';
		} else {
			echo '<div style="float:right">共' . $pages . '页，共' . $strs_c . '条<button onclick=foritems2(all="key=' . $_POST['key'] . '&value=' . $_POST['value'] . '&page=' . ($page - 1) . '",url="/monitor/warn/readhist",outid="result",warn="N",warnword="确定吗？",add="正在加载。。。")>上一页</button><input type="text" style="width:40px;" value="' . $page . '" /><button onclick=foritems2(all="key=' . $_POST['key'] . '&value=' . $_POST['value'] . '&page=' . ($page + 1) . '",url="/monitor/warn/readhist",outid="result",warn="N",warnword="确定吗？",add="正在加载。。。")>下一页</button></div>';
		}
		exit();
	}
}