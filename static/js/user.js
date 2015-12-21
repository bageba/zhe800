/**
 * @name 用户相关
 * @url http://www.ftxia.com
 */
;(function($){
    $.ftxia.user = {
        init: function(options){
            $.ftxia.user.bind_form();
			$.ftxia.user.sign();
			$.ftxia.user.changeTag();
        },
        //登陆页面
        login_validate: function(form){
            //验证
            $.formValidator.initConfig({formid:form.attr('id'),autotip:true});
            form.find('#J_name').formValidator({onshow:' ', onfocus:lang.please_input+lang.username, oncorrect: ' '}).inputValidator({min:1,onerror:lang.please_input+lang.username});
            form.find('#J_pass').formValidator({onshow:' ', onfocus:lang.please_input+lang.password, oncorrect: ' '}).inputValidator({min:1,onerror:lang.please_input+lang.password});
        },
        //找回密码验证
        findpwd_email_validate: function(form){
            $.formValidator.initConfig({formid:form.attr('id'),autotip:true});
            form.find('#J_email').formValidator({onshow:' ', onfocus:lang.please_input+lang.email, oncorrect: ' '}).inputValidator({min:1,onerror:lang.please_input+lang.email});
            $('#J_captcha1').formValidator({onshow:' ',onfocus:lang.captcha_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.captcha_empty});
        },
		findpwd_name_validate: function(form){
            $.formValidator.initConfig({formid:form.attr('id'),autotip:true});
            form.find('#J_name').formValidator({onshow:' ', onfocus:lang.please_input+lang.username, oncorrect: ' '}).inputValidator({min:1,onerror:lang.please_input+lang.username});
            $('#J_captcha2').formValidator({onshow:' ',onfocus:lang.captcha_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.captcha_empty});
        },
        //重置密码表单
        resetpwd_form: function(){
            $.formValidator.initConfig({formid:'J_resetpwd_form',autotip:true});
            
            $('#J_password').formValidator({onshow:' ',onfocus:lang.password_tip, oncorrect: ' '})
            .inputValidator({min:6,onerror:lang.password_too_short})
            .inputValidator({max:20,onerror:lang.password_too_long});

            $('#J_repassword').formValidator({onshow:' ',onfocus:lang.repassword_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.repassword_empty})
            .compareValidator({desid:'J_password',operateor:'=',onerror:lang.passwords_not_match});

            $('#J_captcha').formValidator({onshow:' ',onfocus:lang.captcha_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.captcha_empty});
        },
        //绑定表单
        bind_form: function(){
            if ($('#J_bind_form')) {
                $.formValidator.initConfig({formid:'J_bind_form',autotip:true});
                $('#J_email').formValidator({onshow:' ',onfocus:lang.email_tip, oncorrect: ' '})
                .inputValidator({min:1,onerror:lang.please_input+lang.email})
                .regexValidator({regexp:'email',datatype:'enum',onerror:lang.email_format_error})
                .ajaxValidator({
                    type: 'get',
                    url: FTXIAER.root + '/?m=user&a=ajax_check',
                    data: 'type=email',
                    datatype: 'json',
                    async:'false',
                    success: function(result){
                        return result.status == '1' ? !0 : !1;
                    },
                    buttons: $('#J_regsub'),
                    onerror: lang.email_exists,
                    onwait : lang.wait
                });
                
                $('#J_username').formValidator({onshow:' ',onfocus:lang.username_tip, oncorrect: ' '})
                .inputValidator({min:1,onerror:lang.please_input+lang.username})
                .inputValidator({max:20,onerror:lang.username_tip})
                .ajaxValidator({
                    type: 'get',
                    url: FTXIAER.root + '/?m=user&a=ajax_check',
                    data: 'type=username',
                    datatype: 'json',
                    async:'false',
                    success: function(result){
                        return result.status == '1' ? !0 : !1;
                    },
                    buttons: $('#J_regsub'),
                    onerror: lang.username_exists,
                    onwait : lang.wait
                }).defaultPassed();

                $('#J_password').formValidator({onshow:' ',onfocus:lang.password_tip, oncorrect: ' '})
                .inputValidator({min:6,onerror:lang.password_too_short})
                .inputValidator({max:20,onerror:lang.password_too_long});

                $('#J_repassword').formValidator({onshow:' ',onfocus:lang.repassword_tip, oncorrect: ' '})
                .inputValidator({min:1,onerror:lang.repassword_empty})
                .compareValidator({desid:'J_password',operateor:'=',onerror:lang.passwords_not_match});
            }
        },

		sign: function(){
			$.getJSON(FTXIAER.root + '/?m=sign&a=signstatus', function(result){
					if(result.status == 1){
						$('.signIn_btn').html(result.data.today);
						$('.user_score').html(result.data.score);
						$('.sign_btn').attr("class","sign_btn signed");
						$('.sign').append('<div class="box-sign" style="display: none; "><p><label>金币：</label><span class="dou">'+result.data.score+'</span><i class="icon-dou"></i></p><p><a href="index.php?m=gift&a=index" target="_blank" class="exchange">免费兑换&gt;&gt;</a></p><p><a href="index.php?m=union&a=index" target="_blank" class="visit">邀请得金币</a></p></div>');
					}
			});
			
			$('.sign_btn').live('click',function(){
				if(!$.ftxia.dialog.islogin()) return !1;
				$.getJSON(FTXIAER.root + '/?m=sign&a=ajax_sign', function(result){
					if(result.status == 1){

						var dou_calender_tpl='<div class="alert_content"><div class="top_tips">'
											+'{HEADER}'
											+'</div>'
											+'<div class="weeks">'
											+'<span>周日</span>'
											+'<span>周一</span>'
											+'<span>周二</span>'
											+'<span>周三</span>'
											+'<span>周四</span>'
											+'<span>周五</span>'
											+'<span>周六</span>'
											+'</div>'
											+'<table width="100%" cellspacing="2" cellpadding="0" border="0">'
											+'<tbody>{TABLE}</tbody>'
											+'</table></div>';
						var not_sign='<p class="tips">恭喜您，成功获得<span class="num">{TODAY_DOU}</span>个金币！</p>'
									+'<p class="tom_tips">明天再来，就可以领到 {TOMORROW_DOU} 个金币，别忘记哦~</p>';
						var signed='<p class="tips">您今天已经签过到了，明天再来，就可以获得{TOMORROW_DOU}金币！</p>';
						var today_dou=result.data.point;
						var tomorrow_dou=result.data.tmr_point;
						if(today_dou == tomorrow_dou){
							var dou_calender=dou_calender_tpl.replace(/{HEADER}/,signed);
						}else{
							var dou_calender=dou_calender_tpl.replace(/{HEADER}/,not_sign);
							var totaldou_obj=$('p.all_juandou').eq(0);
							var total_dou=totaldou_obj.text().match(/\d+/);totaldou_obj.text(totaldou_obj.text().replace(/\d+/,parseInt(total_dou)+parseInt(today_dou)));
							//$('div.juan_btn a.signIn_btn').text('已签到 +'+today_dou);
						}
						dou_calender=dou_calender.replace(/{TODAY_DOU}/i,today_dou).replace(/{TOMORROW_DOU}/i,tomorrow_dou).replace(/{TABLE}/i,result.data.table);
						$.dialog({id:'sign_success', title:result.msg, content:dou_calender, width:450, padding:'', fixed:true, lock:true});
					}else{
						$.ftxia.tip({content:result.msg, icon:'error'});
					}
				});
			});
			
			
			$('.signIn_btn').live('click',function(){
				if(!$.ftxia.dialog.islogin()) return !1;
				$.getJSON(FTXIAER.root + '/?m=sign&a=ajax_sign', function(result){
					if(result.status == 1){

						var dou_calender_tpl='<div class="alert_content"><div class="top_tips">'
											+'{HEADER}'
											+'</div>'
											+'<div class="weeks">'
											+'<span>周日</span>'
											+'<span>周一</span>'
											+'<span>周二</span>'
											+'<span>周三</span>'
											+'<span>周四</span>'
											+'<span>周五</span>'
											+'<span>周六</span>'
											+'</div>'
											+'<table width="100%" cellspacing="2" cellpadding="0" border="0">'
											+'<tbody>{TABLE}</tbody>'
											+'</table></div>';
						var not_sign='<p class="tips">恭喜您，成功获得<span class="num">{TODAY_DOU}</span>个金币！</p>'
									+'<p class="tom_tips">明天再来，就可以领到 {TOMORROW_DOU} 个金币，别忘记哦~</p>';
						var signed='<p class="tips">您今天已经签过到了，明天再来，就可以获得{TOMORROW_DOU}金币！</p>';
						var today_dou=result.data.point;
						var tomorrow_dou=result.data.tmr_point;
						if($('div.juan_btn a.signIn_btn').text().indexOf('已签到')!=-1){
							var dou_calender=dou_calender_tpl.replace(/{HEADER}/,signed);
						}else{
							var dou_calender=dou_calender_tpl.replace(/{HEADER}/,not_sign);
							var totaldou_obj=$('p.all_juandou').eq(0);
							var total_dou=totaldou_obj.text().match(/\d+/);totaldou_obj.text(totaldou_obj.text().replace(/\d+/,parseInt(total_dou)+parseInt(today_dou)));
							//$('div.juan_btn a.signIn_btn').text('已签到 +'+today_dou);
						}
						dou_calender=dou_calender.replace(/{TODAY_DOU}/i,today_dou).replace(/{TOMORROW_DOU}/i,tomorrow_dou).replace(/{TABLE}/i,result.data.table);
						$.dialog({id:'sign_success', title:result.msg, content:dou_calender, width:450, padding:'', fixed:true, lock:true});
					}else{
						$.ftxia.tip({content:result.msg, icon:'error'});
					}
				});
			});
		},

		changeTag: function(){
			$(".find_password .tit,.find_email .tit").live("click",function(){
				if($(this).find("em").attr("class")=="down"){
					$(this).find("em").removeClass("down").addClass("up");
					$(this).parent().find("ul").css("display","block");
					if($(this).parent().attr("class")=="get_password_way find_password"){
						$(".find_email ul").css("display","none");
						$(".find_email").find("em").removeClass("up").addClass("down");
					}else{
						$(".find_password ul").css("display","none");
						$(".find_password").find("em").removeClass("up").addClass("down");
					}
				}else if($(this).find("em").attr("class")=="up"){
					$(this).find("em").removeClass("up").addClass("down");
					$(this).parent().find("ul").css("display","none");
					if($(this).parent().attr("class")=="get_password_way find_password"){
						$(".find_email ul").css("display","block");
						$(".find_email").find("em").removeClass("down").addClass("up");
					}else{
						$(".find_password ul").css("display","block");
						$(".find_password").find("em").removeClass("down").addClass("up");
					}
				}
			});
			$(".get_password_way input").live("blur",function(){
				$(this).removeClass("cur_input");
			});
		},



        //注册验证
        register_form: function(form){
            //协议
            $('#J_protocol_btn').live('click', function(){
                var content = $('#J_protocol').html();
                $.dialog({id:'protocol', title:lang.user_protocol, content:content, padding:'', fixed:true, lock:true});
            });
            //验证
            $.formValidator.initConfig({formid:'J_register_form',autotip:true});
            $('#J_email').formValidator({onshow:' ',onfocus:lang.email_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.please_input+lang.email})
            .regexValidator({regexp:'email',datatype:'enum',onerror:lang.email_format_error})
            .ajaxValidator({
                type: 'get',
                url: FTXIAER.root + '/?m=user&a=ajax_check',
                data: 'type=email',
                datatype: 'json',
                async:'false',
                success: function(result){
                    return result.status == '1' ? !0 : !1;
                },
                buttons: $('#J_regsub'),
                onerror: lang.email_exists,
                onwait : lang.wait
            });
            $('#J_username').formValidator({onshow:' ',onfocus:lang.username_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.please_input+lang.username})
            .inputValidator({max:20,onerror:lang.username_tip})
            .ajaxValidator({
                type: 'get',
                url: FTXIAER.root + '/?m=user&a=ajax_check',
                data: 'type=username',
                datatype: 'json',
                async:'false',
                success: function(result){
                    return result.status == '1' ? !0 : !1;
                },
                buttons: $('#J_regsub'),
                onerror: lang.username_exists,
                onwait : lang.wait
            });
            $('#J_password').formValidator({onshow:' ',onfocus:lang.password_tip, oncorrect: ' '})
            .inputValidator({min:6,onerror:lang.password_too_short})
            .inputValidator({max:20,onerror:lang.password_too_long});
            $('#J_repassword').formValidator({onshow:' ',onfocus:lang.repassword_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.repassword_empty})
            .compareValidator({desid:'J_password',operateor:'=',onerror:lang.passwords_not_match});
            $('#J_captcha').formValidator({onshow:' ',onfocus:lang.captcha_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.captcha_empty});
        }
    };
    $.ftxia.user.init(); //用户
})(jQuery);