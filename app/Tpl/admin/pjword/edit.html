<div class="dialog_content">
<form id="info_form" action="{:u('pjword/edit')}" method="post">
<div class="common-form">
	<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
        <tr>
			<th width="50">关键词 :</th>
			<td><input type="text" name="word" id="word" class="input-text" size="30" value="{$info.word}"></td>
		</tr>
	</table>
</div>
<input type="hidden" name="id" value="{$info.id}" />
</form>
</div>
<script type="text/javascript">
var check_name_url = "{:U('pjword/ajax_check_name', array('id'=>$info['id']))}";
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	$("#word").formValidator({onshow:"请填写关键词",onfocus:"请填写关键词"}).inputValidator({min:1,onerror:"请填写关键词"}).ajaxValidator({
	    type : "get",
		url : check_name_url,
		datatype : "json",
		async:'false',
		success : function(result){	
            if(result.status == 0){
                return false;
			}else{
                return true;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "关键已经存在",
		onwait : "正在验证"
	}).defaultPassed();
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
});
</script>