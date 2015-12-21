<?php if (!defined('THINK_PATH')) exit();?><!--编辑商品接口--><div class="dialog_content" style="padding:0 20px;"><form id="info_form" action="<?php echo U('items_site/edit');?>" method="post"><table width="100%" class="table_form"><tr><th><?php echo L('item_site_code');?> :</th><td><?php echo ($info["code"]); ?></td></tr><tr><th><?php echo L('item_site_name');?> :</th><td><input type="text" name="name" id="J_name" class="input-text" size="30" value="<?php echo ($info["name"]); ?>"></td></tr><tr><th><?php echo L('item_site_domain');?> :</th><td><input type="text" name="domain" id="J_domain" class="input-text" size="30" value="<?php echo ($info["domain"]); ?>"></td></tr><tr><th><?php echo L('item_site_url');?> :</th><td><input type="text" name="url" id="J_url" class="input-text" size="30" value="<?php echo ($info["url"]); ?>"></td></tr><?php if(is_array($file_info["config"])): $i = 0; $__LIST__ = $file_info["config"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i;?><tr><th><?php echo ($config["text"]); ?> :</th><td><?php switch($config["type"]): case "text": ?><input type="text" name="<?php echo ($key); ?>" class="input-text" size="30" value="<?php echo ($info['config'][$key]); ?>">&nbsp;&nbsp;<span class="gray"><?php echo ($config["desc"]); ?></span><?php break; case "select": ?><select name="<?php echo ($key); ?>"><?php if(is_array($config["items"])): $item_key = 0; $__LIST__ = $config["items"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($item_key % 2 );++$item_key;?><option value="<?php echo ($item_key); ?>" <?php if($item_key == $info['config'][$key]): ?>selected<?php endif; ?>><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><?php break; endswitch;?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></table><input type="hidden" name="id" value="<?php echo ($info["id"]); ?>" /><input type="hidden" name="code" value="<?php echo ($info["code"]); ?>" /></form></div><script>$(function(){
    $.formValidator.initConfig({formid:"info_form",autotip:true});
    $("#J_name").formValidator({ onshow:lang.please_input+lang.item_site_name, onfocus:lang.please_input+lang.item_site_name, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.item_site_name}).defaultPassed();
    $("#J_domain").formValidator({ onshow:lang.please_input+lang.item_site_domain, onfocus:lang.please_input+lang.item_site_domain, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.item_site_domain}).defaultPassed();
    $("#J_url").formValidator({ onshow:lang.please_input+lang.item_site_url, onfocus:lang.please_input+lang.item_site_url, oncorrect:lang.input_right}).inputValidator({ min:1, onerror:lang.please_input+lang.item_site_url});
        
    $('#info_form').ajaxForm({success:complate,dataType:'json'});
    function complate(result){
        if(result.status == 1){
            $.dialog.get(result.dialog).close();
            $.ftxia.tip({content:result.msg});
            window.location.reload();
        } else {
            $.ftxia.tip({content:result.msg, icon:'alert'});
        }
    }
})
</script>