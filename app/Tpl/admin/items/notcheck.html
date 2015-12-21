<include file="public:header" />
<!--商品列表-->
<div class="pad_10" >
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
                <td>
                <div class="explain_col">
                    <input type="hidden" name="g" value="admin" />
                    <input type="hidden" name="m" value="items" />
                    <input type="hidden" name="a" value="notcheck" />
                    <input type="hidden" name="menuid" value="{$menuid}" />
                    报名时间：
                    <input type="text" name="time_start" id="time_start" class="date" size="12" value="{$search.time_start}">
                    -
                    <input type="text" name="time_end" id="time_end" class="date" size="12" value="{$search.time_end}">
                    &nbsp;&nbsp;商品分类：
                    <select class="J_cate_select mr10" data-pid="0" data-uri="{:U('items_cate/ajax_getchilds')}" data-selected="{$search.selected_ids}"></select>
                    <input type="hidden" name="cate_id" id="J_cate_id" value="{$search.cate_id}" />
                    &nbsp;关键字 :
                    <input name="keyword" type="text" class="input-text" size="25" value="{$search.keyword}" />
                    <input type="submit" name="search" class="btn" value="搜索" />
                </div>
                </td>
            </tr>
        </tbody>
    </table>
    </form>

 <div class="J_tablelist table_list" data-acturi="{:U('items/ajax_edit')}">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=25><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th width="50" align="center">缩略图</th>
                <th align="left"><span data-tdtype="order_by" data-field="title">商品名称</span></th>
                <th width="60"><span data-tdtype="order_by" data-field="cate_id">分类</span></th>
                <th width="60">用户名</th>
                <th width="70"><span data-tdtype="order_by" data-field="price">价格(元)</span></th>
				<th width="70"><span data-tdtype="order_by" data-field="coupon_price">报名价(元)</span></th>
				<th width="70"><span data-tdtype="order_by" data-field="coupon_rate">库存</span></th>
                <th width="40"><span data-tdtype="order_by" data-field="ordid">{:L('sort_order')}</span></th>
                <th width="40"><span data-tdtype="order_by" data-field="pass">{:L('pass')}</span></th>
                <th width="120"><span data-tdtype="order_by" data-field="add_time">报名时间</span></th>
                <th width="120">{:L('operations_manage')}</th>
            </tr>
        </thead>
    	<tbody>
            <volist name="list" id="val" >
            <tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="{$val.id}"></td>
                <td align="center">{$val.id}</td>
                <td align="right">
                    <div class="img_border"><a href="http://item.taobao.com/item.htm?id={$val.num_iid}" target="_blank"><img src="{:attach(get_thumb($val['pic_url'], '_s'), 'item')}" width="32" width="32" class="J_preview" data-bimg="{:attach(get_thumb($val['pic_url '],'_m'), 'item')}"></a></div>
                </td>
                <td align="left"><span data-tdtype="edit" data-field="title" data-id="{$val.id}" class="tdedit" style="color:{$val.colors};">{$val.title}</span></td>
                <td align="center"><b>{$cate_list[$val['cate_id']]}</b></td>
                <td align="center">{$val.uname}</td>
                <td align="center" class="red">{$val.price}</td>
				<td align="center" class="red">{$val.coupon_price}</td>
				<td align="center" class="red">{$val.inventory}</td> 
                <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="{$val.id}" class="tdedit">{$val.ordid}</span></td>
                <td align="center"><img data-tdtype="toggle" data-id="{$val.id}" data-field="pass" data-value="{$val.pass}" src="__STATIC__/images/admin/toggle_<if condition="$val.pass eq 0">disabled<else/>enabled</if>.gif" /></td>
                <td align="center">{$val.add_time|frienddate}</td>
                <td align="center"><a href="{:u('items/edit', array('id'=>$val['id'], 'menuid'=>$menuid))}">{:L('edit')}</a> | <a href="javascript:void(0);" class="J_confirmurl" data-uri="{:u('items/delete', array('id'=>$val['id']))}" data-acttype="ajax" data-msg="{:sprintf(L('confirm_delete_one'),$val['title'])}">{:L('delete')}</a> </td>
            </tr>
            </volist>
    	</tbody>
    </table>
    </div>

    <div class="btn_wrap_fixed">
        <label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall">{:L('select_all')}/{:L('cancel')}</label>
        <input type="button" class="btn btn_submit" data-tdtype="batch_action" data-acttype="ajax" data-uri="{:U('items/do_check')}" data-name="id" data-msg="{:L('confirm_check')}" value="{:L('check')}" />
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="{:U('items/delete')}" data-name="id" data-msg="{:L('confirm_delete')}" value="{:L('delete')}" />
        <div id="pages">{$page}</div>
    </div>

</div>
<include file="public:footer" />
<link rel="stylesheet" type="text/css" href="__STATIC__/js/calendar/calendar-blue.css"/>
<script src="__STATIC__/js/calendar/calendar.js"></script>
<script>
var chcek_url = "{:U('items/getcheck')}";
Calendar.setup({
	inputField : "time_start",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});
Calendar.setup({
	inputField : "time_end",
	ifFormat   : "%Y-%m-%d",
	showsTime  : false,
	timeFormat : "24"
});
$(function(){
    $('.J_cate_select').cate_select({top_option:lang.all}); //分类联动
    $('.J_tooltip[title]').tooltip({offset:[10, 2], effect:'slide'}).dynamic({bottom:{direction:'down', bounce:true}});
});

	function check(id){
		$.getJSON(chcek_url, {id:id}, function(result){
            if(result.status == 1){
                $.dialog({id:'check_form', title:result.msg, content:result.data, padding:'', lock:true});
            }else{
                $.ftxia.tip({content:result.msg, icon:'alert'});
            }
        });
    }
</script>
</body>
</html>