<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
	public function index($display = 1) {
		//提前加载报警模块的数据
		$wf = M()->query('select id,warn_type,warn_prog,warn_conf,warn_send from warn_conf');
		F('warn_conf', $wf);
		$wc = M()->query('select warn_cont.id,warn_cont.warn_date,warn_conf.warn_type,warn_cont.warn_cont from warn_cont left join warn_conf on warn_conf.id=warn_cont.warn_id');
		F('warn_cont',$wc);
		$this->chkstatus();
		$this->readjson();
		$item_conf = M();
		$sql = 'select item1_conf.id as item1_id,item1_conf.item1_num,item1_conf.item1_title,item1_chan,item2_conf.id,item2_conf.item2_title,item2_conf.item2_num,item2_chan from item1_conf left join item2_conf on item2_conf.item1_num=item1_conf.item1_num order by item1_conf.item1_num asc,item2_conf.item2_num asc';
		$items = $item_conf->query($sql);
		$items_c = count($items);
		$items_max = 1;
		for ($i = 1; $i < $items_c + 1; $i++) {
			if ($items[$i]['item1_num'] > $items_max) {
				$items_max = $items[$i]['item1_num'];
			}
		}
		$items_max = $items[$items_c - 1]['item1_num'];
		for ($n = 1; $n < $items_max + 1; $n++) {
			for ($i = 0; $i < $items_c; $i++) {
				//按一级菜单num取出一级菜单title
				if ($items[$i]['item1_num'] == $n) {
					$items1[$n]['title'] = $items[$i]['item1_title'];
					$items1[$n]['num'] = $items[$i]['item1_num'];
					$items1[$n]['id'] = $items[$i]['item1_id'];
					$items1[$n]['chan'] = $items[$i]['item1_chan'];
					if ($items[$i]['item2_title'] != null) {
						$items2[$n]['title'][] = $items[$i]['item2_title'];
						$items2[$n]['num'][] = $items[$i]['item2_num'];
						$items2[$n]['id'][] = $items[$i]['id'];
						$items2[$n]['chan'][] = $items[$i]['item2_chan'];
					}
				}
			}
			$items2s[$n] = count($items2[$n]['title']);
		}
		F('items1', $items1);
		F('items2', $items2);
		if ($display == 1) {
			$this->assign('items_max', $items_max);
			$this->assign('items1', $items1);
			$this->assign('items2', $items2);
			$this->assign('items2s', $items2s);
			$this->display('index');
		}
	}
	public function login() {
		$this->display('login');
	}

	public function chkimg() {
		$Verify = new \Think\Verify();
		$Verify->fontSize = 30;
		$Verify->length = 4;
		$Verify->useNoise = false;
		$Verify->entry();
	}

	private function check_verify($code, $id = '') {
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}

	/**
	 *校验登陆信息
	 */
	public function chklogin() {
		//var_dump($this->check_verify($code=$_POST['verify']));
		if ($_POST) {
			if ($this->check_verify($code = $_POST['verify'])) {
				if ($_POST['username'] == 'adm' & $_POST['password'] == 'adm') {
					$_SESSION['username'] = md5($_POST['username']);
					header('Location:index');
				} else {
					$this->show('<script>alert("用户名或密码错误！")</script>', 'utf-8');
					$this->display('login');
				}
			} else {
				$this->show('<script>alert("验证码错误！")</script>', 'utf-8');
				$this->display('login');
			}
		}
	}

	public function chkstatus() {
		if (!isset($_SESSION['username'])) {
			echo '<script>alert("登录超时!");window.location.href="' . C('URL_LOGIN') . '";</script>';
			exit();
		}
	}

	public function logout() {
		$_SESSION['username'] = null;
		header('Location:login');
	}
	public function itemlist($confs,$items1,$items2){
			$tbhead = '<div id="delmsg"><table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr><th style="width:7.5%">一级菜单/序号</th><th style="width:7.5%">二级菜单/序号</th><th style="text-align:center">数据来源</th><!--<th style="width:3.2%">状态</th>--><th  style="width:7%">管理/<button onclick=foritems2("item2id=6&act=add") type="button" class="btn btn-xs">新建</button></th></tr></thead>';
			foreach ($items1 as $key => $value) {
				if ($items2[$key]) {
					$itm_c = count($items2[$key]['title']);
					$tds = '';
					for ($i = 0; $i < $itm_c; $i++) {
						$btn1 = '';
						$datafrom = '';
						if ($items2[$key]['chan'][$i] != '1') {
							$btn1 = '<button type="button" class="btn btn-default" onclick=foritems2(all="item2id=6&act=edititem&item_type=2&item_num=' . $items2[$key]['num'][$i] . '&item_title=' . $items2[$key]['title'][$i] . '&item_belo=' . $value['id'] . '&item_id=' . $items2[$key]['id'][$i] . '",url="foritems2")>修改</button><button onclick=foritems2(all="item2id=6&act=delitem&item_type=2&item_id=' . $items2[$key]['id'][$i] . '",url="foritems2",outid="delmsg",warn="Y",warnword="确定删除吗？") type="button" class="btn btn-default">删除</button>';
							//$btn1=$items2[$key]['chan'][$i];
						}
						if ($confs[$items2[$key]['id'][$i]]) {
							$datafrom = stripslashes(json_encode($confs[$items2[$key]['id'][$i]], JSON_UNESCAPED_UNICODE));
						}
						$tds .= '<tr><td>' . $items2[$key]['title'][$i] . '/' . $items2[$key]['num'][$i] . '</td><td style="font-size:10px;width:60%;word-wrap: break-word;word-break:break-all;">' . $datafrom . '</td><!--<td>状态</td>--><td><div class="btn-group btn-group-xs">' . $btn1 . '</div></td></tr>';
					}
					$tbbody .= '<tr><td rowspan="' . ($itm_c + 1) . '">' . $value['title'] . '/' . $value['id'] . '</td></tr>' . $tds;
				} else {
					if ($value['chan'] != '1') {
						$btn2 = '<button onclick=foritems2(all="item2id=6&act=edititem&item_title=' . $value['title'] . '&item_type=1&item_num=' . $value['num'] . '&item_id=' . $value['id'] . '") type="button" class="btn btn-default">修改</button><button onclick=foritems2(all="item2id=6&act=delitem&item_type=1&item_id=' . $value['id'] . '",url="foritems2",outid="delmsg",warn="Y",warnword="确定删除吗？") type="button" class="btn btn-default">删除</button>';
					}
					if ($confs[$value['id']]) {
						$datafrom = stripslashes(json_encode($confs[$value['id']]));
					}else{
						//修复栏目列表中json串出现复用的问题
						$datafrom='';
					}
					$tbbody .= '<tr><td>' . $value['title'] . '/' . $value['num'] . '</td><td></td><td>' . $datafrom . '</td><!--<td>状态</td>--><td><div class="btn-group btn-group-xs">' . $btn2 . '</div></td></tr>';
				}
			}
			$tbfoot = '</table></div>';
			return $tbhead . $tbbody . $tbfoot;
	}
	public function foritems2() {
		$this->chkstatus();
		$this->index($display = 0);
		$confs = F('confs');
		$items1 = F('items1');
		$items2 = F('items2');
		if (in_array($_POST['item2id'], $items2[5]['id'])) {
			switch ($_POST['act']) {
			case 'list':
				echo $this->itemlist($confs,$items1,$items2);
				exit();
			case 'delitem':
				if ($_POST['item_type'] == '2') {
					$tbname = 'item2_conf';
				} else {
					$tbname = 'item1_conf';
				}
				$table = M();
				$sql = 'delete from ' . $tbname . ' where id=' . $_POST['item_id'];
				$affect = $table->execute($sql);
				file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
				if ($affect) {
					$_POST['act'] = 'list';
					$this->index($display = 0);
					echo $this->itemlist($confs,$items1,$items2);
					echo '删除成功！左侧栏目<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可更改';
				} else {
					$_POST['act'] = 'list';
					$this->index($display = 0);
					$this->foritems2();
					echo '该菜单不存在，请<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>重试!';
				}
				exit();
			case 'saveadd':
				if ($_POST['itm_type'] == '2') {
					if ($_POST['itm_name'] && $_POST['itm_type'] && $_POST['itm_id'] && $_POST['itm_belo']) {
						try {
							$table = M();
							$sql = 'insert into item2_conf(item1_num,item2_num,item2_title) values(' . $_POST['itm_belo'] . ',' . $_POST['itm_id'] . ',"' . $_POST['itm_name'] . '")';
							$table->execute($sql);
							file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
							echo '添加成功，<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可显示';
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					} else {
						throw new Exception('二级菜单，数据没有通过审核，请重做', 1);
					}
				} else {
					if ($_POST['itm_name'] && $_POST['itm_type'] && $_POST['itm_id']) {
						$numstr = '';
						$items1_c = count($items1);
						for ($i = 1; $i < $items1_c + 1; $i++) {
							$itm_ids[] = $items1[$i]['num'];
							$numstr .= $items1[$i]['num'] . '、';
						}
						$numstr = substr($numstr, 0, -3);
						$items1_c = count($items1);
						if (in_array($_POST['itm_id'], $itm_ids)) {
							echo '菜单栏目ID被占用，被占用的ID有' . $numstr;
							break;
						}
						$numstr = substr($numstr, 0, -3);
						try {
							$table = M();
							$sql = 'insert into item1_conf(item1_num,item1_title) values(' . $_POST['itm_id'] . ',"' . $_POST['itm_name'] . '")';
							$table->execute($sql);
							file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
							echo '添加成功，<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可显示';
						} catch (Exception $e) {
							echo $e->getMessage();
						}
					} else {
						throw new Exception('一级菜单，数据没有通过审核，请重做', 1);
					}
				}
				exit();
			case 'add':
				$select = '';
				foreach ($items1 as $key => $value) {
					$select .= '<option value="' . $key . '">' . $value['title'] . '</option>';
				}
				$str_form = '<form class="form-horizontal" role="form" style="padding-left:10%;padding-top:3%;width:80%;">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">菜单名</label>
                                <div class="col-sm-10">
                                    <input id="itm_name" type="text" class="form-control" placeholder="请输入菜单名">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单类型</label>
                                <div class="radio col-sm-10">
                                    <label>
                                        <input onclick=document.getElementById("belong").style.display="none" type="radio" name="optionsRadios" id="optionsRadios1" value="1" checked> 一级菜单
                                    </label>
                                    <label>
                                        <input onclick=document.getElementById("belong").style.display="block" type="radio" name="optionsRadios" id="optionsRadios2" value="2"> 二级菜单
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-sm-2 control-label">菜单序号</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="itm_id" placeholder="请输入菜单id">
                                </div>
                            </div>
                            <div id="belong" class="form-group" style="display:none">
                                <label for="lastname" class="col-sm-2 control-label">菜单归属</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="itm_belo">' . $select . '
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-sm-2 control-label">数据来源</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="itm_from" placeholder="请输入菜单数据来源">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button onclick="saveadd()" type="button" class="btn btn-default">提交</button>
                                    <button type="button" class="btn btn-default" onclick=foritems2("item2id=6&act=list")>返回</button>
                                </div>
                            </div>
                        </form><div id="message" style="padding-left:15%;padding-top:3%;width:70%;color:red;"></div>';
				echo $str_form;
				exit();
			case 'edititem':
				if ($_POST['item_type'] == 1) {
					$checked1 = 'checked';
					$display = 'display:none';
				} else {
					$checked2 = 'checked';
					$display = '';
				}
				$select = '';
				foreach ($items1 as $key => $value) {
					if ($key == $_POST['item_belo']) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					if ($value['id'] != $_POST['item_id']) {
						$select .= '<option ' . $selected . ' value="' . $key . '">' . $value['title'] . '</option>';
					}

				}
				$dtfm = stripslashes(json_encode($confs[$_POST['item_id']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
				$str_form = '<form class="form-horizontal" role="form" style="padding-left:10%;padding-top:3%;width:80%;">
                            <div class="form-group">
                                <label for="firstname" class="col-sm-2 control-label">菜单名</label>
                                <div class="col-sm-10">
                                    <input id="itm_name" type="text" class="form-control" value="' . $_POST['item_title'] . '">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">菜单类型</label>
                                <div class="radio col-sm-10">
                                    <label>
                                        <input onclick=document.getElementById("belong").style.display="none" type="radio" name="optionsRadios" id="optionsRadios1" value="1" ' . $checked1 . '> 一级菜单
                                    </label>
                                    <label>
                                        <input onclick=document.getElementById("belong").style.display="block" type="radio" name="optionsRadios" id="optionsRadios2" value="2"' . $checked2 . '> 二级菜单
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-sm-2 control-label">菜单序号</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="itm_num" value="' . $_POST['item_num'] . '">
                                </div>
                            </div>
                            <div class="form-group" style="display:none">
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="itm_id" value="' . $_POST['item_id'] . '">
                                    <input type="hidden" id="old_itm_sec" value="' . $_POST['item_num'] . '">
                                    <input type="hidden" id="old_itm_type" value="' . $_POST['item_type'] . '">
                                </div>
                            </div>
                            <div id="belong" class="form-group" style="' . $display . '">
                                <label for="lastname" class="col-sm-2 control-label">菜单归属</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="itm_belo">' . $select . '
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-sm-2 control-label">数据来源</label>
                                <div class="col-sm-10">
                                   	<textarea style="font-size:0.3cm;word-wrap: break-word;word-break:break-all;" class="form-control" id="itm_from"  rows="6"  value="">' . $dtfm . '</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button onclick="saveedit()" type="button" class="btn btn-default">保存</button>
                                    <button type="button" class="btn btn-default" onclick=foritems2("item2id=6&act=list")>返回</button>
                                </div>
                            </div>
                        </form><div id="message" style="padding-left:15%;padding-top:3%;width:70%;color:red;"></div>';
				echo $str_form;
				//var_dump($_POST);
				exit();
			case 'saveedit':
				$result = 0;
				$tb = M();
				$json = json_decode($_POST['itm_from'], true);
				if ($_POST['itm_type'] == $_POST['old_itm_type']) {
					if ($_POST['itm_type'] == '1') {
						$sql = 'update item' . $_POST['itm_type'] . '_conf set item' . $_POST['itm_type'] . '_title="' . $_POST['itm_name'] . '",item1_num=' . $_POST['itm_num'] . ' where id=' . $_POST['itm_id'];
						$num = '';
						$isin = 0;
						//var_dump($items1[$_POST['old_itm_sec']]['num']);
						foreach ($items1 as $k => $v) {
							$num .= $v['num'] . '、';
							if ($_POST['itm_num'] == $v['num'] && $_POST['itm_num'] != $items1[$_POST['old_itm_sec']]['num']) {
								$isin = 1;
							}
						}
						if ($isin == 1) {
							echo '执行失败：<br>您输入的菜单序号已被占用，一级菜单不允许菜单序号重复，被占用的菜单序号如下：' . substr($num, 0, -3);
							exit();
						}
					} else {
						$sql = 'update item' . $_POST['itm_type'] . '_conf set item' . $_POST['itm_type'] . '_title="' . $_POST['itm_name'] . '",item1_num=' . $_POST['itm_belo'] . ' where id=' . $_POST['itm_id'];
					}
					if ($tb->execute($sql)) {
						file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
						$msg = '修改栏目信息成功<br>';
					} else {
						$msg = '栏目信息没有任何修改<br>';
					}
				} else {
					$tb->startTrans();
					if ($_POST['old_itm_type'] == '1') {
						//菜单类型由一级改为二级
						$sql1 = 'insert into item2_conf(item1_num,item2_num,item2_title) values(' . $_POST['itm_belo'] . ',' . $_POST['itm_num'] . ',"' . $_POST['itm_name'] . '")';
						//$sql1 = 'select max(id) from item1_conf';
						//$sql2 = 'delete from item1_conf where id=' . $items1[$_POST['old_itm_sec']]['id'];
						$sql2 = 'delete from item1_conf where id=' . $_POST['itm_id'];
						//$sql3 = 'update cont_conf set item_id=(select max(id) from item2_conf),item_type=2 where item_id=' . $items1[$_POST['old_itm_sec']]['id'];
						$sql3 = 'update cont_conf set item_id=(select max(id) from item2_conf),item_type=2 where item_id=' . $_POST['itm_id'];
					} else {
						//菜单类型由二级改为一级
						$sql1 = 'insert into item1_conf(item1_num,item1_title) values(' . $_POST['itm_num'] . ',"' . $_POST['itm_name'] . '")';
						$sql2 = 'delete from item2_conf where id=' . $_POST['itm_id'];
						$sql3 = 'update cont_conf set item_id=(select max(id) from item1_conf),item_type=1 where item_id=' . $_POST['itm_id'];
						$num = '';
						$isin = 0;
						foreach ($items1 as $k => $v) {
							$num .= $v['num'] . '、';
							if ($_POST['itm_num'] == $v['num']) {
								$isin = 1;
							}
						}
						if ($isin == 1) {
							echo '执行失败：<br>您输入的菜单序号已被占用，一级菜单不允许菜单序号重复，被占用的菜单序号如下：' . substr($num, 0, -3);
							exit();
						}
					}
					if ($tb->execute($sql1) && $tb->execute($sql2) && $tb->execute($sql3)) {
						$tb->commit();
						file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
						$msg = '修改栏目信息成功<br>';
					} else {
						$tb->rollback();
						$msg = '栏目信息没有任何修改<br>';
					}
				}
				$tb->startTrans();
				if ($json) {
					$json_c = count($json);
					for ($i = 0; $i < $json_c; $i++) {
						if ($json[$i]['id']) {
							if ($json[$i]['act'] == 'del') {
								$sql = 'delete from cont_conf where id=' . $json[$i]['id'];
							} else {
								$sql = 'update cont_conf set cont_sec=' . $json[$i]['cont_sec'] . ',cont_title="' . $json[$i]['cont_title'] . '",cont_var="' . $json[$i]['cont_var'] . '",cont_url="' . $json[$i]['cont_url'] . '" where id=' . $json[$i]['id'];
							}
						} else {
							$sql = 'insert into cont_conf(item_id,cont_sec,cont_title,cont_var,cont_url,item_type) values(' . $_POST['itm_id'] . ',' . $json[$i]['cont_sec'] . ',"' . $json[$i]['cont_title'] . '","' . $json[$i]['cont_var'] . '","' . $json[$i]['cont_url'] . '",' . $_POST['itm_type'] . ')';
						}
						if ($tb->execute($sql)) {
							$result = 1;
							file_put_contents(C('STATUSFILE'),json_encode(array('status'=>1)));
						}
					}
				} else {
					$msg .= 'json内容或格式错误，请检查！<br>';
					exit();
				}
				if ($result == 0) {
					$tb->rollback();
					$msg .= '该菜单字段没有做任何修改<br>';
				} else {
					$tb->commit();
					$msg .= '配置已经更新<br>';
				}
				echo $msg;
				exit();
			default:
				echo '这里是默认的输出';
				exit();
			}
		} else {
			if (array_key_exists('item2id', $_POST)) {
				$item_type = 2;
				$item_id = $_POST['item2id'];
			} else {
				$item_type = 1;
				$item_id = $_POST['item1id'];
			}
			if ($_POST['page'] == '') {
				$_POST['page'] = 1;
			}
			$title = M()->query('select id,cont_title from cont_conf where item_id=' . $item_id . ' and item_type=' . $item_type . ' order by cont_sec');
			if (!$title) {
				echo '库中没有数据可供显示';
				exit();
			}
			$title_c = count($title);
			$tb_head = '<table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr>';
			$max_id = '';
			for ($i = 0; $i < $title_c; $i++) {
				$tb_head .= '<th style="text-align:center">' . $title[$i]['cont_title'] . '</th>';
				if ($title[$i]['id'] == $_POST['selected']) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$select .= '<option value="' . $title[$i]['id'] . '" ' . $selected . '>' . $title[$i]['cont_title'] . '</option>';
				if ($max_id < $title[$i]['id']) {
					$max_id = $title[$i]['id'];
				}
				$where .= $title[$i]['id'] . ',';
			}
			//搜索框
			$select = '<div class="form-group"> <div class="input-group col-xs-12"><span class="input-group-btn"><select id="colname" class="form-control" style="width: auto;">' . $select . '</select></span><input type="text" name="keyword" id="keyword" class="form-control" placeholder="请输入关键词" value="' . $_POST['search'] . '"><span class="input-group-btn"><button class="btn btn-success" onclick=foritems2("item' . $item_type . 'id=' . $item_id . '&item_type=' . $item_type . '&act=list&selected="+document.getElementById(\'colname\').value+"&search="+document.getElementById(\'keyword\').value+"&page=' . $_POST['page'] . '&url=' . $_POST['url'] . '")>搜索</button></span></div></div>';
			echo $select;
			$tb_head .= '<th style="text-align:center">更新时间</th></tr></thead>';
			$where = substr($where, 0, -1);
			$data = M()->query('select cont_id,cont_text,update_sec,update_date from contents where isshow=1 and cont_id in (' . $where . ')  order by update_sec desc');
			$data_c = count($data);
			//var_dump($data);
			for ($i = 0; $i < $data_c; $i++) {
				$data2[$data[$i]['update_sec']][$data[$i]['cont_id']] = $data[$i]['cont_text'];
				$data2[$data[$i]['update_sec']]['date'] = $data[$i]['update_date'];
			}
			$data2_c = count($data2);
			for ($m = 1; $m < $data2_c + 1; $m++) {
				//var_dump($data2[$m]);
				for ($n = 1; $n < $max_id + 1; $n++) {
					if ($data2[$m][$n] == null) {
						$data2[$m][$n] = '  ';
					}
				}
			}
			$trs = '';
			foreach ($data2 as $k => $v) {
				if ($_POST['selected'] and $_POST['search'] != '') {
					//var_dump($v[$_POST['selected']]);
					if (!stristr($v[$_POST['selected']], $_POST['search'])) {
						continue;
					}
				}
				$trs .= '<tr>';
				foreach ($title as $k2 => $v2) {
					foreach ($v as $k3 => $v3) {
						if ($k3 == $v2['id']) {
							$trs .= '<td>' . $v[$v2['id']] . '</td>';
						}
					}
				}
				if ($v['date'] != null) {
					$trs .= '<td>' . $v['date'] . '</td>';
				} else {
					$trs .= '<td>   </td>';
				}
				$trs .= '</tr>';
			}
			echo $tb_head . $trs . '</table>';
			echo '<a href="#">上一页</a><input type="text" id="page" value="' . $_POST['page'] . '" onkeydown="javascript:if (event.keyCode==13) foritems2(\'item' . $item_type . 'id=' . $item_id . '&item_type=' . $item_type . '&act=list&selected=\'+document.getElementById(\'colname\').value+\'&search=\'+document.getElementById(\'keyword\').value+\'&page=\'+document.getElementById(\'page\').value+\'&url=' . $_POST['url'] . '\')"><a href="#">下一页</a>';
			exit();
		}
	}
	public function test() {
		$table = M('pass_config');
		echo $table->where(array('setmanu' => 0))->find()['setmanu'];
	}
	public function readjson() {
		$cont_confs = M()->query('select id,item_id,cont_sec,cont_title,cont_var,cont_url,item_type from cont_conf order by item_id');
		$cont_confs_c = count($cont_confs);
		//最大的item_id
		$max_item_id = $cont_confs[$cont_confs_c - 1]['item_id'];
		foreach ($cont_confs as $k => $v) {
			$item_id = $v['item_id'];
			unset($v['item_id']);
			//unset($v['id']);
			unset($v['item_type']);
			$confs[$item_id][] = $v;
		}
		header('Content-type: text/json');
		$jsons = stripslashes(json_encode($confs, JSON_UNESCAPED_UNICODE));
		F('confs', $confs);
	}
	public function fromzp() {
		header('Content-type: text/json');
		echo stripslashes(json_encode(array("test1" => "测试一的内容" . date("Y-m-d H:m:s", time()), "test2" => "测试2的内容" . date("Y-m-d H:m:s", time())), JSON_UNESCAPED_UNICODE));
		exit();
	}
}

?>
