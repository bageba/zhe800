/**
 * @name 商品评论
 * @author andery@foxmail.com
 * @url http://www.ftxia.com
 */
;(function($){
    $.ftxia.comment = {
        settings: {
            container: '#pub_area',
            page_list: '#note_comment_list',
            page_bar: '#J_cmt_page',
            pub_content: '#pub_content',
            pub_btn: '#pub_submit'
        },
        init: function(options){
            options && $.extend($.ftxia.comment.settings, options);
            $.ftxia.comment.list();
            $.ftxia.comment.publish();
        },
        //列表
        list: function(){
            var s = $.ftxia.comment.settings;
            $('li', $(s.page_list)).live({
                mouseover: function(){
                    $(this).addClass('hover');
                },
                mouseout: function(){
                    $(this).removeClass('hover');
                }
            });
            $('a', $(s.page_bar)).live('click', function(){
                var url = $(this).attr('href');
                $.getJSON(url, function(result){
                    if(result.status == 1){
                        $(s.page_list).html(result.data.list);
                        $(s.page_bar).html(result.data.page);
                    }else{
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                });
                return false;
            });
        },
        //发表评论
        publish: function(){
            var s = $.ftxia.comment.settings;
            $(s.pub_btn).live('click', function(){
                if(!$.ftxia.dialog.islogin()) return !1;
                var id = $(s.container).attr('data-id'),
                    dv = $(s.pub_content).attr('def-val'),
                    content = $(s.pub_content).val();
                if(content == dv){
                    $(s.pub_content).focus();
                    return false;
                }
                $.ajax({
                    url: FTXIAER.root + '/?m=item&a=comment',
                    type: 'POST',
                    data: {
                        id: id,
                        content: content
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
                            $(s.pub_content).val('');
                            $(s.page_list).prepend(result.data);
                        }else{
                            $.ftxia.tip({content:result.msg, icon:'error'});
                        }
                    }
                });
            });
        }
    };
    $.ftxia.comment.init();
})(jQuery);