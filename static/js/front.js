
;(function($){
    $.ftxia.init = function(){
        $.ftxia.ui.init();
        $.ftxia.tool.sendmail();
        //$.ftxia.tool.msgtip();
    }
    $.ftxia.ui = {
        init: function() {
            $.ftxia.ui.input_init();
            $.ftxia.ui.return_top();
            $.ftxia.ui.decode_img($(document));
            $.ftxia.ui.captcha();
        },
        //返回顶部
        return_top: function() {
            $('#goTopBtn')[0] && $('#goTopBtn').returntop();
        },
        //刷新验证码
        captcha: function() {
            $('#J_captcha_img').click(function(){
                var timenow = new Date().getTime(),
                    url = $(this).attr('data-url').replace(/js_rand/g,timenow);
                $(this).attr("src", url);
            });
            $('#J_captcha_change').click(function(){
                $('#J_captcha_img').trigger('click');
            });
        },
        input_init: function() {
            $('input[def-val],textarea[def-val]').live('focus', function(){
                var self = $(this);
                $.trim(self.val()) == $.trim(self.attr('def-val')) && self.val("");
                self.css("color", "#484848")
            });
            $('input[def-val],textarea[def-val]').live('blur', function(){
                var self = $(this);
                $.trim(self.val()) == "" && (self.val(self.attr('def-val')), self.css("color", "#999999"));
            });
        },
        decode_img: function(context) {
            $('.J_decode_img', context).each(function(){
                var uri = $(this).attr('data-uri')||"";
                $(this).attr('src', $.ftxia.util.base64_decode(uri));  
            });
        }
    },
    $.ftxia.tool = {
        //发送邮件
        sendmail: function() {
            return FTXIAER.async_sendmail ? ($.get(FTXIAER.root + '/?a=send_mail'), !0) : !1;
        },
        //信息提示
        msgtip: function() {
            if(FTXIAER.uid){
                var is_update = !1;
                var update = function() {
                    is_update = !0;
                    $.getJSON(FTXIAER.root + '/?m=user&a=msgtip', function(result){
                        if(result.status == 1){
                            var fans = parseInt(result.data.fans),
                                atme = parseInt(result.data.atme),
                                msg = parseInt(result.data.msg),
                                system = parseInt(result.data.system),
                                msgtotal = fans + atme + msg + system;
                            fans > 0 && $('#J_fans').html('(' + fans + ')');
                            atme > 0 && $('#J_atme').html('(' + atme + ')');
                            msg > 0 && $('#J_msg').html('(' + msg + ')');
                            system > 0 && $('#J_system').html('(' + system + ')');
                            msgtotal > 0 && $('#J_msgtip').html('(' + msgtotal + ')');
                            is_update = !1;
                            setTimeout(function(){update()}, 5E3);
                        }
                    });
                };
                !is_update && update();
            }
        }
    }
    $.ftxia.init();
})(jQuery);