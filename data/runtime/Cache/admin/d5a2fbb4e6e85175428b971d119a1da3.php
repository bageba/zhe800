<?php if (!defined('THINK_PATH')) exit();?><!doctype html><html xmlns="http://www.w3.org/1999/xhtml"><head runat="server"><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" /><meta http-equiv="X-UA-Compatible" content="TOP" /><title>华美授权版飞天侠-管理平台-管理员登陆</title><style type="text/css">	body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;overflow:hidden;}
	a:link {font-size:12px; text-decoration:none; color:#43860c;}
	a:visited {font-size:12px; text-decoration:none; color:#43860c;}
	a:hover{font-size:12px; text-decoration:none; color:#FF3300;}
	ul,li,dl,dt,dd,form,p{margin:0; padding:0; border:none; list-style-type:none;}
	img {margin:0; padding:0; border:0;}
	.STYLE1 {color: #528311; font-size: 12px;}
	.STYLE2 {color: #42870a; font-size: 12px;}
	.STYLE3 {color: #43860c; font-size: 12px;}
	.left_tree {font-size: 12px; padding-left:15px; padding-top:5px;}
	.left_tree a{padding-left:3px;}
	.left_treeb {font-size: 12px; padding-left:15px; padding-top:5px; padding-bottom:5px;}
	.left_treeb a{padding-left:3px;}
	.yzm{position:absolute; background:url(__STATIC__/css/admin/bgimg/login_ts140x89.gif) no-repeat; width:140px; height:89px;right:330px;top:260px; text-align:center; font-size:12px;z-index:999;display:none;}
	.yzm a:link,.yzm a:visited{color:#036;text-decoration:none;}
	.yzm a:hover{color:#C30;}
	.yzm img{cursor:pointer; margin:4px auto 7px; width:130px; height:50px; border:1px solid #fff;}
	.cr{font-size:12px;font-style:inherit;text-align:center;color:#ccc;width:100%; position:absolute; bottom:58px;}
	.cr a{color:#ccc;text-decoration:none;}
</style></head><body scroll="no" class="login_body"><form action="<?php echo U('index/login');?>" method="post" name="myform" id="myform"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0"><tr><td height="600" background="__STATIC__/css/admin/bgimg/login_01.gif"><table width="862" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td height="266" background="__STATIC__/css/admin/bgimg/login_02.gif">&nbsp;</td></tr><tr><td height="95"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="424" height="80" background="__STATIC__/css/admin/bgimg/login_03.gif">&nbsp;</td><td width="183" height="80" background="__STATIC__/css/admin/bgimg/login_04.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="21%" height="22"><div align="center"><span class="STYLE1">账 号</span></div></td><td width="79%" height="22"><input type="text" name="username" id="username" style="height:18px; width:130px; border:solid 1px #cadcb2; font-size:12px; color:#81b432;"></td></tr><tr><td height="22"><div align="center"><span class="STYLE1">密 码</span></div></td><td height="22"><input type="password" name="password" id="password" style="height:18px; width:130px; border:solid 1px #cadcb2; font-size:12px; color:#81b432;"></td></tr><tr><td height="22"><div align="center"><span class="STYLE1">验证码</span></div></td><td height="22"><input type="text" name="verify_code" id="verify_code" style="height:18px; width:80px; border:solid 1px #cadcb2; font-size:12px; color:#81b432;"  ><img title="<?php echo (L("refresh_verify_code")); ?>" class="verify_img" src="<?php echo U('index/verify_code', array('t'=>time()));?>" width="40"/></td></tr><tr><td  >&nbsp;</td><td  ><img src="__STATIC__/css/admin/bgimg/logo_dl.gif" width="81" height="23" border="0" usemap="#Map"></td></tr></table></td><td width="255" background="__STATIC__/css/admin/bgimg/login_05.gif">&nbsp;</td></tr></table></td></tr><tr><td height="240" valign="top" background="__STATIC__/css/admin/bgimg/login_06.gif"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="22%" height="30">&nbsp;</td><td width="56%">&nbsp;</td><td width="22%">&nbsp;</td></tr><tr><td>&nbsp;</td><td height="30"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="44%" height="20">&nbsp;</td><td width="56%" class="STYLE2">华美版本:Ftxia v5.1 </td></tr></table></td><td>&nbsp;</td></tr></table></td></tr></table></td></tr><tr><td bgcolor="#289121">&nbsp;</td></tr></table><INPUT type=image height=0 width=0 src="__STATIC__/css/admin/bgimg/clear.gif" value="" name=Submit><map name="Map"><area shape="rect" coords="3,3,36,19" href="javascript:myform.submit();"><area shape="rect" coords="40,3,78,18" href="javascript:myform.reset();"></map></form><script language="javascript" type="text/javascript" src="__STATIC__/js/jquery/jquery.js"></script><script>$(function(){
    if(self != top){
        top.location = self.location;
    }
    
    $(".verify_img").click(function(){
        var timenow = new Date().getTime();
        $(this).attr("src","<?php echo U('index/verify_code');?>&"+timenow)
    });
});
</script></body></html>