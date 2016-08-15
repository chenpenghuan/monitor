<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller
{
    public function index($display=1)
    {
        $this->chkstatus();
        $this->readjson();
        $item_conf=M();
        $sql='select item1_conf.id as item1_id,item1_conf.item1_num,item1_conf.item1_title,item1_chan,item2_conf.id,item2_conf.item2_title,item2_conf.item2_num,item2_chan from item1_conf left join item2_conf on item2_conf.item1_num=item1_conf.item1_num order by item1_conf.item1_num asc,item2_conf.item2_num asc';
        $items=$item_conf->query($sql);
        $items_c=count($items);
        $items_max=1;
        for ($i=1; $i <$items_c+1; $i++) { 
            if($items[$i]['item1_num']>$items_max){
                $items_max=$items[$i]['item1_num'];
            }
        }
        $items_max=$items[$items_c-1]['item1_num'];
        for ($n=1; $n <$items_max+1; $n++) { 
            for ($i=0; $i <$items_c ; $i++) { 
                //按一级菜单num取出一级菜单title
                if($items[$i]['item1_num']==$n){
                    $items1[$n]['title']=$items[$i]['item1_title'];
                    $items1[$n]['num']=$items[$i]['item1_num'];
                    $items1[$n]['id']=$items[$i]['item1_id'];
                    $items1[$n]['chan']=$items[$i]['item1_chan'];
                    if($items[$i]['item2_title']!=null){
                        $items2[$n]['title'][]=$items[$i]['item2_title'];
                        $items2[$n]['num'][]=$items[$i]['item2_num'];
                        $items2[$n]['id'][]=$items[$i]['id'];
                        $items2[$n]['chan'][]=$items[$i]['item2_chan'];
                    }
                }
            }
            $items2s[$n]=count($items2[$n]['title']);
        }
        F('items1',$items1);
        F('items2',$items2);
        /*
        var_dump($items2);
        */
        if($display==1){
            $this->assign('items_max',$items_max);
            $this->assign('items1',$items1);
            $this->assign('items2',$items2);
            $this->assign('items2s',$items2s);
            $this->display('index');
        }
    }
    public function login()
    {
        $this->display('login');
    }

    public function chkimg()
    {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 30;
        $Verify->length = 4;
        $Verify->useNoise = false;
        $Verify->entry();
    }

    private function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    /**
     *校验登陆信息
     */
    public function chklogin()
    {
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

    public function chkstatus()
    {
        if (!isset($_SESSION['username'])) {
            header('Location:'.C('URL_LOGIN'));
        }
    }

    public function logout()
    {
        $_SESSION['username'] = null;
        header('Location:login');
    }

    public function foritems2()
    {
        $this->chkstatus();
        $this->index($display=0);
        $confs=F('confs');
        $items1=F('items1');
        $items2=F('items2');
        if($_POST['item2id']==6) {
            switch ($_POST['act']) {
                case 'list':
                    $tbhead='<div id="delmsg"><table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr><th>一级菜单/序号</th><th>二级菜单/序号</th><th style="text-align:center">数据来源</th><th>状态</th><th>管理/<button onclick=foritems2("item2id=6&act=add") type="button" class="btn btn-xs">新建</button></th></tr></thead>';
                    foreach ($items1 as $key => $value) {
                        if($items2[$key]){
                            $itm_c=count($items2[$key]['title']);
                            $tds='';
                            for ($i=0; $i <$itm_c ; $i++) { 
                                $btn1='';
                                $datafrom='';
                                if ($items2[$key]['chan'][$i]!='1') {
                                    $btn1='<button type="button" class="btn btn-default" onclick=foritems2(all="item2id=6&act=edititem&item_type=2&item_title='.$items2[$key]['title'][$i].'&item_id='.$items2[$key]['id'][$i].'",url="foritems2")>修改</button><button onclick=foritems2(all="item2id=6&act=delitem&item_type=2&item_id='.$items2[$key]['id'][$i].'",url="foritems2",outid="delmsg",warn="Y",warnword="确定删除吗？") type="button" class="btn btn-default">删除</button>';
                                    //$btn1=$items2[$key]['chan'][$i];
                                }
                                if ($confs[$items2[$key]['id'][$i]]) {
                                    $datafrom=stripslashes(json_encode($confs[$items2[$key]['id'][$i]]));
                                }
                                $tds.='<tr><td>'.$items2[$key]['title'][$i].'/'.$items2[$key]['num'][$i].'</td><td style="font-size:10px;width:60%;">'.$datafrom.'</td><td>状态</td><td><div class="btn-group btn-group-xs">'.$btn1.'</div></td></tr>';
                            }
                            $tbbody.='<tr><td rowspan="'.($itm_c+1).'">'.$value['title'].'/'.$value['id'].'</td></tr>'.$tds;
                        }else{
                            if($value['chan']!='1'){
                                $btn2='<button onclick=foritems2(all="item2id=6&act=edititem&item_name='.$value['title'].'&item_type=1&item_id='.$value['id'].'") type="button" class="btn btn-default">修改</button><button onclick=foritems2(all="item2id=6&act=delitem&item_type=1&item_id='.$value['id'].'",url="foritems2",outid="delmsg",warn="Y",warnword="确定删除吗？") type="button" class="btn btn-default">删除</button>';
                            }
                            if ($confs[$value['id']]) {
                                $datafrom=stripslashes(json_encode($confs[$value['id']]));
                            }
                            $tbbody.='<tr><td>'.$value['title'].'/'.$value['num'].'</td><td></td><td>'.$datafrom.'</td><td>状态</td><td><div class="btn-group btn-group-xs">'.$btn2.'</div></td></tr>';
                        }
                    }
                    $tbfoot='</table></div>';
                    echo $tbhead.$tbbody.$tbfoot;
                    exit();
                case 'delitem' :
                    if($_POST['item_type']=='2'){
                        $tbname='item2_conf';
                    }else{
                        $tbname='item1_conf';
                    }
                    $table=M();
                    $sql='delete from '.$tbname.' where id='.$_POST['item_id'];
                    $affect=$table->execute($sql);
                    if($affect){
                        $_POST['act']='list';
                        $this->index($display=0);
                        $this->foritems2();
                        echo '删除成功！左侧栏目<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可更改';
                    }else{
                        $_POST['act']='list';
                        $this->index($display=0);
                        $this->foritems2();
                        echo '该菜单不存在，请<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>重试!';
                    }
                    exit();
                case 'saveadd':
                    if($_POST['itm_type']=='2'){
                        if($_POST['itm_name'] && $_POST['itm_type'] && $_POST['itm_id'] && $_POST['itm_belo']){
                            try {
                                $table=M();
                                $sql='insert into item2_conf(item1_num,item2_num,item2_title) values('.$_POST['itm_belo'].','.$_POST['itm_id'].',"'.$_POST['itm_name'].'")';
                                $table->execute($sql);
                                echo '添加成功，<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可显示';
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }else{
                            echo '二级菜单，数据没有通过审核，请重做';
                        }
                    }else{
                        if ($_POST['itm_name'] && $_POST['itm_type'] && $_POST['itm_id']) {
                            try {
                                $table=M();
                                $sql='insert into item1_conf(item1_num,item1_title) values('.$_POST['itm_id'].',"'.$_POST['itm_name'].'")';
                                $table->execute($sql);
                                echo '添加成功，<button type="button" class="btn btn-success" onclick="location.reload()">刷新</button>即可显示';
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    }
                    exit();
                case  'add':
                    $select='';
                    foreach ($items1 as $key => $value) {
                        $select.='<option value="'.$key.'">'.$value['title'].'</option>';
                    }
                    $str_form='<form class="form-horizontal" role="form" style="padding-left:10%;padding-top:3%;width:80%;">
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
                                <label for="lastname" class="col-sm-2 control-label">菜单id</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="itm_id" placeholder="请输入菜单id">
                                </div>
                            </div>
                            <div id="belong" class="form-group" style="display:none">
                                <label for="lastname" class="col-sm-2 control-label">菜单归属</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="itm_belo">'.$select.'
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
                    //var_dump($_POST);
                    //var_dump($items2);
                    //$tb_info=M()->query('select ');
                    $data=M()->query('select * from item_'.$_POST['item_type'].'_'.$_POST['item_id']);
                    $data_c=count($data);
                    foreach ($data as $k => $v) {
                        var_dump($v);
                    }
                    exit();
                default:
                    echo '这里是默认的输出';
                    exit();
            }
        }else{
            if(array_key_exists('item2id', $_POST)){
                $item_type=2;
                $item_id=$_POST['item2id'];
            }else{
                $item_type=1;
                $item_id=$_POST['item1id'];
            }
            $data=M()->query('select contents.id,cont_id,cont_text,cont_var,cont_title,cont_sec,update_sec from contents left join cont_conf on cont_conf.id=contents.cont_id where cont_conf.item_id='.$item_id.' order by cont_sec asc,update_sec desc');
            //var_dump($data);
            $data_c=count($data);
            $tb_head='';
            $rows=0;
            var_dump($data[0]);
            for ($i=0; $i < $data_c; $i++) { 
                $updates[$data[$i]['update_sec']]['title'][]=$data[$i]['cont_title'];
                $updates[$data[$i]['update_sec']]['cont'][]=$data[$i]['cont_text'];
                if($rows<$data[$i]['update_sec']){
                    $rows=$data[$i]['update_sec'];
                }
            }
            //var_dump($updates);
            //var_dump($rows);
            $cols=[];
            for ($i=$rows; $i >=0; $i--) { 
                $lines.='<tr>';
                for ($n=0; $n < count($updates[$i]['title']); $n++) { 
                    if(!in_array($updates[$i]['title'][$n], $cols)){
                        $tb_head.='<th>'.$updates[$i]['title'][$n].'</th>';
                        array_push($cols,$updates[$i]['title'][$n]);
                    }
                    var_dump($updates[$i]['title'][$n]);
                }
                $lines.='</tr>';
            }
            //$tb_head.='<th>'.$v.'</th>';
            $tb_head='<table  class="table table-bordered table-hover definewidth m10" style="font-size:12px;"><thead><tr>'.$tb_head.'</tr></thead>';
            echo $tb_head.$tb_body.'</table>';
        }
    }
    public function createimg()
    {
        import("Org.Util.Chart");
        $chart = new \Chart();
        $title = ""; //标题
        $data = array(20, 27, 45, 75, 90, 10, 80, 100); //数据
        $size = 500; //尺寸
        $width = 750; //宽度
        $height = 350; //高度
        $legend = array("哥哥 ", "bbbb", "cccc", "dddd ", "eeee", "ffff", "gggg", "hhhh");//说明
        $chart->createcolumnar($title, $data, $size, $height, $width, $legend);
    }
    public function test(){
        $table=M('pass_config');
        echo $table->where(array('setmanu' => 0))->find()['setmanu'];
    }
    public function readjson(){
        //echo json_encode(array('name' =>'陈鹏欢','sex'=>'男','age'=>21,'test'=>'这部分是测试内容' ),JSON_UNESCAPED_UNICODE);
        $cont_confs=M()->query('select * from cont_conf order by item_id');
        $cont_confs_c=count($cont_confs);
        //最大的item_id
        $max_item_id=$cont_confs[$cont_confs_c-1]['item_id'];
        foreach ($cont_confs as $k => $v) {
            $item_id=$v['item_id'];
            unset($v['item_id']);
            unset($v['id']);
            unset($v['item_type']);
            $confs[$item_id][]=$v;
        }
        /*
        $jsons=stripslashes(json_encode($confs,JSON_UNESCAPED_UNICODE));
        echo $jsons;
        var_dump(json_decode($jsons,true)[2]);
        */
        F('confs',$confs);
        //var_dump($confs);
    }
}

?>
