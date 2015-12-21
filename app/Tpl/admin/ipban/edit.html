<!--编辑禁止IP-->
<div class="dialog_content">
	<form id="info_form" name="info_form" action="{:u('ipban/edit')}" method="post">
	<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
		<input type="hidden" name="type" value="$info['type']" />
        <tr>
			<th width="80">IP地址 :</th>
			<td><input type="text" name="name" id="name" value="{$info.name}" class="input-text"></td>
		</tr>
		<tr>
			<th width="80">有效期 :</th>
			<td><input type="text" name="expires_time" id="expires_time" value="{$info.expires_time|date='Y-m-d H:i:s',###}" class="date input-text"></td>
		</tr>
	</table>
	<input type="hidden" name="id" value="{$info.id}" />
	</form>
</div>
<script>
Calendar.setup({
    inputField : "expires_time",
    ifFormat   : "%Y-%m-%d",
    showsTime  : true,
    timeFormat : "24"
});
$(function(){
	$.formValidator.initConfig({formid:"info_form",autotip:true});
	$("#name").formValidator({onshow:lang.please_input+'IP地址',onfocus:lang.please_input+'IP地址'}).inputValidator({min:1,onerror:lang.please_input+'IP地址'}).defaultPassed();
	
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