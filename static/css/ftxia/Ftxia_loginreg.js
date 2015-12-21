var userFlg=true;var mailFlg=true;var passFlg=true;var npassFlg=true;var checkbox=true;var checkboxChange=function(){checkbox=($(".wrap_left .checkbox").attr("checked")!="checked")?false:true;};var PasswordStrength={LevelValue:[30,20,0],Factor:[1,2,5],KindFactor:[0,0,10,20],Regex:[/[a-zA-Z]/g,/\d/g,/[^a-zA-Z0-9]/g]}
var getStrLength=function(str){var a=str.length;var b=str.match(/[^\x00-\x80]/g)?str.match(/[^\x00-\x80]/g).length:0;var c=(a-b)+b*2;return c;};function autoGetUsername(){var un="&:h#{D_xiuUserName";var user="";user=decodeURIComponent(user);var h=window.location.href;var t=h.indexOf("register");var obj=$("#username");if(t==-1&&user){$("#username").focus();if(obj.val()=="用户名/邮箱"||obj.val()==""){obj.val(user);value=obj.val();}
$("#password").focus();}}
autoGetUsername();$(".wrap input[type=text], .wrap input[type=password]").live("focus",function(){$('.wrap input[type=text]').removeClass('cur_input');$('.wrap input[type=password]').removeClass('cur_input');$(this).addClass('cur_input');$(this).parent().next().css("display","block");});function blurEmail(obj){var tip=$("#emailtip");var re=RegExp("^[a-z|A-Z|0-9]+([\.|\-|_][a-z|A-Z|0-9]+)*@[a-z|A-Z|0-9]+([\.|\-][a-z|A-Z|0-9]+)*(\.[a-z|A-Z]+)+$");if(obj.value.length==0){tip.attr('class','tips-red');tip.html('请填写邮箱地址');mailFlg=false;return false;}
if(!re.test(obj.value)){tip.attr('class','tips-red');tip.html('邮箱格式不对哦');mailFlg=false;return false;}
$.post("ajax.php",{type:"regCheck",args:{type:"email",value:obj.value}},function(data){if(data.status.code==1002){tip.attr('class','tips-red');tip.html('该邮箱已被用过啦 <a href="/login">请登陆</a>');mailFlg=false;return false;}else if(data.status.code==1001){tip.attr('class','tips-green');tip.html('该邮箱可以使用');mailFlg=true;return true;}},"json");return true;}
function blurUser(obj){var tip=$("#usertip");var re1=RegExp("[^\u4E00-\u9FA5|^a-zA-Z0-9|^_]");var re2=getStrLength(obj.value);var re3=RegExp("(^_)|(_$)");if(obj.value.length==0){tip.attr('class','tips-red');tip.html('昵称不能为空哦');mailFlg=false;return false;}
if(re3.test(obj.value)){tip.attr('class','tips-red');tip.html('不能用下划线开头或结束哦');userFlg=false;return false;}
if(re1.test(obj.value)){tip.attr('class','tips-red');tip.html('只能使用汉字、字母或者数字哦');userFlg=false;return false;}
if(re2<4||re2>20){tip.attr('class','tips-red');tip.html('2~10汉字/4~20字母或数字内');userFlg=false;return false;}
$.post("ajax.php",{type:"regCheck",args:{type:"username",value:obj.value}},function(data){if(data.status.code==1002){tip.attr('class','tips-red');tip.html('该用户名已被用过啦 <a href="/login">请登陆</a>');userFlg=false;return false;}else if(data.status.code==1001){tip.attr('class','tips-green');tip.html('该用户名可以使用');userFlg=true;return true;}},"json");return true;}
function blurPass(obj){var tip=$("#passtip");if(obj.value.length<6||obj.value.length>16){tip.attr('class','tips-red');tip.html('有点短哦，最少6位');passFlg=false;return false;}
value=PasswordStrength.StrengthValue(obj.value);if(value<=15){tip.attr('class','tips-green');tip.html('一般安全 (╯﹏╰)');}else if(value<=25){tip.attr('class','tips-green');tip.html('比较安全 (∩_∩)');}else{tip.attr('class','tips-green');tip.html('非常安全 (^_^)');}
passFlg=true;return true;}
PasswordStrength.StrengthValue=function(pwd){var strengthValue=0;var ComposedKind=0;for(var i=0;i<this.Regex.length;i++){var chars=pwd.match(this.Regex[i]);if(chars!=null){strengthValue+=chars.length*this.Factor[i];ComposedKind++;}}
strengthValue+=this.KindFactor[ComposedKind];return strengthValue;}
function blurNPass(obj){var tip=$("#npasstip");if(obj.value.length==0){tip.attr('class','tips-red');tip.html('请输入确认密码');npassFlg=false;return false;}
if(obj.value!=$("#password")[0].value){tip.attr('class','tips-red');tip.html('确认密码与密码不一致');npassFlg=false;return false;}
tip.attr('class','tips-green');tip.html('确认密码正确');npassFlg=true;return true;}
function chkReg(obj){if(!blurEmail(obj.email)||!mailFlg){obj.email.focus();return false;}
if(!blurUser(obj.username)||!userFlg){obj.username.focus();return false;}
if(!blurPass(obj.password)||!passFlg){obj.password.focus();return false;}
if(!blurNPass(obj.npassword)||!npassFlg){obj.npassword.focus();return false;}
obj.submits.value='提交中..';obj.submits.disabled=true;}
function chkRegMore(obj){if(undefined!=obj.email){if(!blurEmail(obj.email)||!mailFlg)return false;}
if(!blurUser(obj.username)||!userFlg)return false;if(undefined!=obj.password){if(!blurPass(obj.password)||!passFlg)return false;}
obj.submits.value='提交中..';obj.submits.disabled=true;}
function chkLogin(obj){if(obj.username.value==''||obj.username.value=='用户名/邮箱'){$("#err_name").css('display','inline');obj.username.focus();return false;}
else if(obj.password.value==''){$("#err_pass").css('display','inline');obj.password.focus();return false;}
obj.submits.value='登录中..';obj.disabled=true;$.ajax({url:"ajax.php",type:"POST",timeout:6E4,data:{type:"fastlogin",args:{username:obj.username.value,password:obj.password.value,cookietime:checkbox}},dataType:"json",success:function(b){if(b.status.code==1001){window.location="user.php";return false;}else{obj.submits.value='登　录';obj.disabled=false;if(b.status.code==1002){$("#passerror").css("display","none");$("#usererror").css("display","block");}else{$("#usererror").css("display","none");$("#passerror").css("display","block");}
return false;}
return false;}});return false;}
function chkAct(obj){blurUser(obj.username);blurPass(obj.password);blurNPass(obj.npassword);if(!(userFlg&&passFlg&&npassFlg))return false;obj.submits.disabled=true;return true;}
function chkEmailGet(obj)
{var re=RegExp("^[a-z|A-Z|0-9]+([\.|\-|_][a-z|A-Z|0-9]+)*@[a-z|A-Z|0-9]+([\.|\-][a-z|A-Z|0-9]+)*(\.[a-z|A-Z]+)+$");if($("#email").val()==''){alert('请输入邮箱地址！');$("#email").focus();return false;}
if(!re.test($("#email").val())){alert('您输入的邮箱地址错误！');$("#email").focus();return false;}
if($(".find_password ul").attr("display")!="none"){if(obj.code.value==''){alert('请输入验证码！');obj.code.focus();return false;}}
obj.submits.value='提交中..';obj.submits.disabled=true;return true;}
function chkUserGet(obj)
{if($("#username").val()==''){alert('请输入用户名！');$("#username").focus();return false;}
if($(".find_email ul").attr("display")!="none"){if(obj.code.value==''){alert('请输入验证码！');obj.code.focus();return false;}}
obj.submits.value='提交中..';obj.submits.disabled=true;return true;}
function chkPassGet(obj)
{if(obj.password.value==''){alert('请输入新密码！');obj.password.focus();return false;}
if(obj.password.value!=obj.npassword.value){alert('两次密码不一致！');obj.npassword.focus();return false;}
if(obj.code.value==''){alert('请输入验证码！');obj.code.focus();return false;}
return true;}
changeTag=function(){$(".find_password .tit,.find_email .tit").live("click",function(){if($(this).find("em").attr("class")=="down"){$(this).find("em").removeClass("down").addClass("up");$(this).parent().find("ul").css("display","block");if($(this).parent().attr("class")=="get_password_way find_password"){$(".find_email ul").css("display","none");$(".find_email").find("em").removeClass("up").addClass("down");}else{$(".find_password ul").css("display","none");$(".find_password").find("em").removeClass("up").addClass("down");}}else if($(this).find("em").attr("class")=="up"){$(this).find("em").removeClass("up").addClass("down");$(this).parent().find("ul").css("display","none");if($(this).parent().attr("class")=="get_password_way find_password"){$(".find_email ul").css("display","block");$(".find_email").find("em").removeClass("down").addClass("up");}else{$(".find_password ul").css("display","block");$(".find_password").find("em").removeClass("down").addClass("up");}}});$(".get_password_way input").live("blur",function(){$(this).removeClass("cur_input");});};changeTag();$(document).ready(function(){$('#email').focus();});