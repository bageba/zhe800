<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><link href="__STATIC__/css/admin/style.css" rel="stylesheet"/><title><?php echo L('website_manage');?></title><script>	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?></script></head><body><div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div><?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav"><div class="content_menu ib_a blue line_x"><?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?><a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?></div></div><?php endif; ?><div class="subnav"><h1 class="title_2 line_x">天天特价 - 明日预告采集 - [预告无推广地址，请注意！]</h1></div><div class="pad_lr_10"><form id="J_info_form" action="<?php echo U('advanced/setting');?>" method="post"><table width="100%" cellspacing="0" class="table_form"><tr><th width="150">采集分类 :</th><td><select class="J_tejia_select mr10" data-pid="0" data-uri="<?php echo U('tejia_cate/ajax_getchilds', array('type'=>0));?>"></select></td></tr><tr><th width="150">对应分类 :</th><td><select class="J_cate_select mr10" data-pid="0" data-uri="<?php echo U('items_cate/ajax_getchilds', array('type'=>0));?>"></select></td></tr><tr><th></th><td><input type="submit" value="采集" name="dosubmit" class="smt mr10"></td></tr></table><input type="hidden" name="cate_id" id="J_cate_id" value="0" /><input type="hidden" name="tejia_cate_id" id="J_tejia_cate_id" value="0" /></form></div><script src="__STATIC__/js/jquery/jquery.js"></script><script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script><script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script><script src="__STATIC__/js/ftxia.js"></script><script src="__STATIC__/js/admin.js"></script><script src="__STATIC__/js/dialog.js"></script><script>//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script><?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script><script>$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?><div style="display:none;"><script language="javascript" type="text/javascript" src="http://js.users.51.la/16598910.js"></script></div><script>$(function(){
    var collect_url = "<?php echo U('advanced/collect');?>";
    $('#J_info_form').ajaxForm({success:complete, dataType:'json'});
    var p = 2;
    function complete(result){
        if(result.status == 1){
            $.dialog({id:'advanced', title:result.msg, content:result.data, padding:'', lock:true});
            p = 2;
            collect_page();
        } else {
            $.ftxia.tip({content:result.msg, icon:'alert'});
        }
    }
    function collect_page(){
        $.getJSON(collect_url, {p:p}, function(result){
            if(result.status == 1){
                $.dialog.get('advanced').content(result.data);
                p++;
                collect_page(p);
            }else{
                $.dialog.get('advanced').close();
                $.ftxia.tip({content:result.msg});
            }
        });
    }
    //分类联动
	$('.J_tejia_select').tejia_select({field:'J_tejia_cate_id'});
    $('.J_cate_select').cate_select({field:'J_cate_id'});
});
</script></body></html>