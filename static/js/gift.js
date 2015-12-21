/**
 * @name 礼物中心
 * @author Ftxia
 */
;(function($){
    $.ftxia.gift = {
        settings: {
            gift_btn: '.J_gift_btn'
        },
        init: function(options){
            options && $.extend($.ftxia.gift.settings, options);
            //详细信息切换
            $('ul.J_desc_tab').tabs('div.J_desc_panes > div');
            $.ftxia.gift.ec();
        },
        ec: function(){
            var s = $.ftxia.gift.settings;
            $(s.gift_btn).live('click', function(){
                if(!$.ftxia.dialog.islogin()) return !1;
                var id = $(this).attr('data-id'),
                    num_input = $(this).attr('data-num'),
                    num = $(num_input).val();
                $.getJSON(FTXIAER.root + '/?m=gift&a=ec', {id:id, num:num}, function(result){
                    if(result.status == 1){
                        $.ftxia.tip({content:result.msg});
                    }else if(result.status == 2){
                        $.dialog({id:'gift_daifu', title:result.msg, content:result.data, width:350, padding:'', fixed:true, lock:true});
                        $.ftxia.gift.daifu_form($('#J_daifu_form'));
                    }else{
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                });
            });
        },
        //代付表单
        daifu_form: function(form){
            form.ajaxForm({
                success: function(result){
                    if(result.status == 1){
                        $.dialog.get('gift_daifu').close();
                        $.ftxia.tip({content:result.msg});
                        window.location.reload();
                    } else {
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                },
                dataType: 'json'
            });
        }
    };
    $.ftxia.gift.init();
})(jQuery);