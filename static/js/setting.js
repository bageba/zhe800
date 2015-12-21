/**
 * @name 个人中心
 * @author andery@foxmail.com
 * @url http://www.ftxia.com
 */
;(function(){
    $.ftxia.setting = {
        init: function(){
            $.ftxia.setting.basic();
            $.ftxia.setting.password();
            $.ftxia.setting.union();
            $.ftxia.setting.message_list();
            $.ftxia.setting.message();
            $.ftxia.setting.target();
        },
        //上传头像
        basic: function(){
            $('#J_birthday')[0] && Calendar.setup({
                inputField : "J_birthday",
                ifFormat   : "%Y-%m-%d",
                showsTime  : false,
                timeFormat : "24"
            });
            $('#J_upload_avatar').uploader({
                action_url: FTXIAER.root + '/?m=user&a=upload_avatar',
                input_name: 'avatar',
                onComplete: function(id, fileName, result){
                    if(result.status == '1'){
                        $('#J_avatar').attr('src', result.data);
                    }
                }
            });
        },
        //修改密码
        password: function(){
            //验证
            $.formValidator.initConfig({formid:'J_password_form',autotip:true});

            $('#J_password').formValidator({onshow:' ',onfocus:lang.password_tip, oncorrect: ' '})
            .inputValidator({min:6,onerror:lang.password_too_short})
            .inputValidator({max:20,onerror:lang.password_too_long});

            $('#J_repassword').formValidator({onshow:' ',onfocus:lang.repassword_tip, oncorrect: ' '})
            .inputValidator({min:1,onerror:lang.repassword_empty})
            .compareValidator({desid:'J_password',operateor:'=',onerror:lang.passwords_not_match});
        },

		union: function(){
            $('#links_btn').live('click', function(){
                var sibling_input=$(this).siblings('input.links_in');
				sibling_input.select();
				 $.ftxia.setting.copy(sibling_input.val(),'ok');
            });
			$('input.links_in').click(function(){
				$(this).data('focus',1);
				$(this).select();
			});
			$('input.links_in').blur(function(){
				if($(this).data('focus')){
					 $.ftxia.setting.copy($(this).val(),'not');
					$(this).data('focus',0);
				}
			});
        },

		copy: function(text,isalert) {
             if(window.clipboardData){
				window.clipboardData.setData('text',text);
				alert('复制成功');
			}else{
				if(isalert=='ok'){
					alert('很抱歉，您的浏览器不支持该功能。您可以CTRL+C直接复制');
				}
			}
         },


       
        message: function(){
            $('#J_msg_send').live('click', function(){
                var to_id = $(this).attr('data-toid'),
                    content = $('#J_msg_content').val();
                $.ajax({
                    url: FTXIAER.root + '/?m=message&a=publish',
                    type: 'POST',
                    data: {
                        to_id: to_id,
                        content: content
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
                            //列表动态添加
                            $('#J_msg_list').prepend(result.data);
                            $('#J_msg_content').val('');
                        }else{
                            $.ftxia.tip({content:result.msg, icon:'error'});
                        }
                    }
                });
            });
        },
        //短信列表
        message_list: function(){
            //单个删除
            $('#J_msg_list').find('.J_ml_del').live('click', function(){
                var mid = $(this).attr('data-mid');
                $.getJSON(FTXIAER.root + '/?m=message&a=del', {mid:mid}, function(result){
                    if (result.status == '1') {
                        $('#ml_'+mid).slideUp('fast', function(){
                            $(this).remove();
                        });
                    } else {
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                });
            });
            //批量删除
            $('#J_ml_delall').live('click', function(){
                if(!confirm(lang.confirm_del_talk)) return !1;
            });
        },
        //搜索用户
        target: function(){
            $('#J_search_target').live('click', function(){
                var search_uname = $('#J_search_uname').val();
                $.ajax({
                    url: FTXIAER.root + '/?m=message&a=search_target',
                    type: 'POST',
                    data: {
                        search_uname: search_uname
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
                            //列表动态添加
                            $('#J_search_list').html(result.data);
                        }
                    }
                });
            });
        }
    };
    $.ftxia.setting.init();
})(jQuery);