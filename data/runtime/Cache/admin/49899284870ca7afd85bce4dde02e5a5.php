<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><!--商品列表--><div class="subnav"><div class="content_menu ib_a blue line_x"><a class="add fb " href="<?php echo U('robots/add_do');?>" ><em>添加采集器</em></a><a class="add fb " href="javascript:auto_collect()" ><em>一键自动采集</em></a></div></div><div class="pad_lr_10" ><div class="J_tablelist table_list" data-acturi="<?php echo U('robots/ajax_edit');?>"><table width="100%" cellspacing="0"><thead><tr><th><span data-tdtype="order_by" data-field="id">ID</span></th><th align="left"><span data-tdtype="order_by" data-field="name">商品名称</span></th><th width="80"><span data-tdtype="order_by" data-field="cate_id">分类</span></th><th width="70"><span data-tdtype="order_by" data-field="page">采集页数</span></th><th width="100"><span data-tdtype="order_by" data-field="last_page">上次采集页数</span></th><th width="40"><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th><th width="40"><span data-tdtype="order_by" data-field="status">状态</span></th><th width="120"><span data-tdtype="order_by" data-field="last_time">最近时间</span></th><th width="200"><?php echo L('operations_manage');?></th></tr></thead><tbody><?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr><td align="center"><?php echo ($val["id"]); ?></td><td align="left"><span data-tdtype="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"  ><?php echo ($val["name"]); ?></span></td><td align="center"><b><?php echo ($cate_list[$val['cate_id']]); ?></b></td><td align="center" class="red"><span data-tdtype="edit" data-field="page" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["page"]); ?></span></td><td align="center" class="red"><?php echo ($val["last_page"]); ?></td><td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td><td align="center"><img data-tdtype="toggle" data-id="<?php echo ($val["id"]); ?>" data-field="status" data-value="<?php echo ($val["status"]); ?>" src="__STATIC__/images/admin/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" /></td><td align="center"><?php echo (frienddate($val["last_time"])); ?></td><td align="center"><a href="javascript:collect(<?php echo ($val["id"]); ?>,<?php echo ($val["last_page"]); ?>);">继续上次采集</a> |<a href="javascript:collect(<?php echo ($val["id"]); ?>,1);">开始采集</a> |<a href="<?php echo u('robots/edit', array('id'=>$val['id'], 'menuid'=>$menuid));?>"><?php echo L('edit');?></a> | <a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('robots/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['name']);?>"><?php echo L('delete');?></a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table></div><div class="btn_wrap_fixed"><div id="pages"><?php echo ($page); ?></div></div></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/ftxia.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><div style="display:none;"><script language="javascript" type="text/javascript" src="http://js.users.51.la/16598910.js"></script></div><script>    var collect_url = "<?php echo U('robots/collect');?>";
    var id = 0;
	var p = 1;
	function collect(id,p){
        $.getJSON(collect_url, {id:id,p:p}, function(result){
            if(result.status == 1){
				$.dialog({id:'cmt_taobao', title:result.msg, content:result.data, padding:'', lock:true});
                p++;
				setTimeout("collect_page("+ id +","+ p+")",1000);
            }else{
                $.ftxia.tip({content:result.msg});
            }
        });
    }
	function collect_page(id,p){
        $.getJSON(collect_url, {id:id,p:p}, function(result){
            if(result.status == 1){
                $.dialog.get('cmt_taobao').content(result.data);
                p++;
				setTimeout("collect_page("+ id +","+ p+")",1000);
            }else{
                $.dialog.get('cmt_taobao').close();
                $.ftxia.tip({content:result.msg});
            }
        });
    }




    function auto_collect(){

        $.getJSON(collect_url, {auto:1}, function(result){
            if(result.status == 1){
                $.dialog({id:'cmt_ftxia', title:result.msg.title, content:result.data, padding:'', lock:true});
                rid = result.msg.rid;
                p = result.msg.np;
                
                setTimeout("auto_collect_page("+ rid +","+ p+")",1000);
            }else{
                $.ftxia.tip({content:result.msg});
            }
        });
    }
    function auto_collect_page(rid,p){
        $.getJSON(collect_url, {rid:rid,p:p,auto:1}, function(result){
            if(result.status == 1){
                $.dialog.get('cmt_ftxia').content(result.data);
                rid = result.msg.rid;
                p = result.msg.np;
                setTimeout("auto_collect_page("+ rid +","+ p+")",1000);
            }else{
                $.dialog.get('cmt_ftxia').close();
                $.ftxia.tip({content:result.msg});
            }
        });
    }
</script></body></html>