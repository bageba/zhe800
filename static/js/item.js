;(function($){
    $.ftxia.item = {
		 //获取商品信息
        fetch: function(result){
            $.dialog.get('share_item').title(lang.share_title).content(result.html);
            $.ftxia.item.fetch_form($('#J_fetch_item'), result.item);
        },
        fetch_form: function(form, item){
            form.find('.J_pub_btn').die('click').live('click', function(){
                $.dialog.get('share_item').title(false).content('<div class="d_loading">'+lang.wait+'</div>');
                var album_id = $('.J_album_id').val(),
                    ac_id = $('.J_df_cate').val(),
                    intro = form.find('.J_intro').val();
                $.ajax({
                    url: FTXIAER.root + '/?m=item&a=publish_item',
                    type: 'POST',
                    data: {
                        item: item,
                        album_id: album_id,
                        intro: intro
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
                            $.dialog.get('share_item').close();
                            $.ftxia.tip({content:result.msg});
                        }else{
                            $.ftxia.tip({content:result.msg, icon:'error'});
                        }
                    }
                });
            });
        },
		//查询
		check_form: function(form){
			form.find('.J_check_btn').die('click').live('click', function(){
                var item_url = $.trim($('.item_url').val());
                if(!$.ftxia.util.isURl(item_url)) return $.ftxia.tip({content:lang.please_input+lang.correct_itemurl, icon:'alert'}), !1;
                $.dialog.get('check_item').title(false).content('<div class="d_loading">'+lang.wait+'</div>');
                $.getJSON(FTXIAER.root + '/?m=ajax&a=fetch_item', {url:item_url}, function(result){
                    if(result.status == 1){
						$.dialog.get('check_item').close();
						$.dialog({id:'check_item_result', title:lang.chaxun, content:result.data.html, padding:'', fixed:true, lock:true});
                    }else{
                        $.dialog.get('check_item').close();
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                });
            });

		},



		report: function(form){
			form.find('.J_check_btn').die('click').live('click', function(){
                var item_url = $.trim($('.item_url').val());
                $.dialog.get('check_item').title(false).content('<div class="d_loading">'+lang.wait+'</div>');
                $.getJSON(FTXIAER.root + '/?m=ajax&a=report', {url:item_url}, function(result){
                    if(result.status == 1){
						$.dialog.get('check_item').close();
						$.dialog({id:'check_item_result', title:lang.chaxun, content:result.data.html, padding:'', fixed:true, lock:true});
                    }else{
                        $.dialog.get('check_item').close();
                        $.ftxia.tip({content:result.msg, icon:'error'});
                    }
                });
            });

		}

    };
})(jQuery);