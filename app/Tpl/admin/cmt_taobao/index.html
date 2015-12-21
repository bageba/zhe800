<include file="public:header" />
<div class="subnav">
    <h1 class="title_2 line_x">淘宝评论采集</h1>
</div>

<div class="pad_lr_10">
    <form id="J_info_form" action="{:U('cmt_taobao/setting')}" method="post">
    <table width="100%" cellspacing="0" class="table_form">
        <tr>
            <th width="150">采集分类 :</th>
            <td><select class="J_cate_select mr10" data-pid="0" data-uri="{:U('items_cate/ajax_getchilds', array('type'=>0))}"></select></td>
        </tr>
        <tr>
            <th>采集顺序 :</th>
            <td>
                <select name="orders">
                    <option value="add_time">发布时间</option>
                    <option value="hits">访问量</option>
                    <option value="volume">销量</option>
                </select>
            </td>
        </tr>
		<tr>
			<th>采集条件</th>
			<td>
				<input type="radio" value="1" name="collect" class="radio" id="passed">
                <label for="passed" class="radio_lalel">只采集未采集过的</label>&nbsp;&nbsp;&nbsp;
                <input type="radio" value="0" name="collect" id="fail" class="radio" checked>
                <label for="fail" class="radio_lalel">全部采集</label>
			</td>
		</tr>
        <tr>
            <th></th>
            <td><input type="submit" value="{:L('submit')}" name="dosubmit" class="smt mr10"></td>
        </tr>
    </table>
    <input type="hidden" name="cate_id" id="J_cate_id" value="0" />
    </form>
</div>

<include file="public:footer" />
<script>
$(function(){
    var collect_url = "{:U('cmt_taobao/collect')}";
    $('#J_info_form').ajaxForm({success:complete, dataType:'json'});

    var p = 1;
    function complete(result){
        if(result.status == 1){
            //开始采集
            $.dialog({id:'cmt_taobao', title:result.msg, content:result.data, padding:'', lock:true});
            p = 1;
            collect_page();
        } else {
            $.ftxia.tip({content:result.msg, icon:'alert'});
        }
    }
    function collect_page(){
        $.getJSON(collect_url, {p:p}, function(result){
            if(result.status == 1){
                $.dialog.get('cmt_taobao').content(result.data);
                p++;
                collect_page(p);
            }else{
                $.dialog.get('cmt_taobao').close();
                $.ftxia.tip({content:result.msg});
            }
        });
    }
    //分类联动
    $('.J_cate_select').cate_select({field:'J_cate_id'});
});
</script>
</body>
</html>