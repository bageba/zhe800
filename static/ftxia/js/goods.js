/**
 * @name 宝贝管理
 * @author Ftxia
 */
;(function($){
    $.ftxia.goods = {
        init: function(options){
            $.ftxia.goods.goods_add();
			$.ftxia.goods.mygoods();
        },

		mygoods: function(){
			$("#mygoods_id").click(function() {
				 
			});

		},
        goods_add: function(){
             $("#good_link").focusout(function() {
				  var inform_html = '<li id="detail">' 
					+ '<label></label>' 
					+ '<div class="goodsdetail">' 
					+ '<div class="pic">' 
					+ '<a href="{detail_url}" target="_blank">' 
					+ '<img src="{pic_url}_100x100.jpg" /></a>' 
					+ '</div>' 
					+ '<div class="detail">' 
					+ '<p class="title"><input id="taobao_title" name="taobao_title" type="text" class="txt" value="{title}" maxlength="35"/></p>' 
					+ '<p class="red t_title">请注意修改标题，标题内不允许出现其他活动名称、包邮和新品等字样</p>' 
					+ '<p class="seller">' 
					+ '<span>{nick}</span>&nbsp;<input id="nick" name="nick" type="hidden" class="txt" value="{nick}" />' 
					+ '</p>' 
					+ '<p>' 
					+ '<span class="price"><em>￥</em>{price}</span>' 
					+ '</p>' 
					+ '</div>' 
					+ '</div>' 
					+ '</li>';
					var link = $("#good_link").attr('value');
					if (link != '') {
						 $.getJSON(FTXIAER.root + '/?m=goods&a=ajaxgetid', {url:link}, function(result){
							if(result.status == 1){
								if ($('#detail').size() != 0) {
									$('#detail').remove();
								}
								$('#img_link').val(result.data.item.pic_url);
								$('#price').val(result.data.item.price);
								$('#good_price').val(result.data.item.coupon_price);
								$('#iid').val(result.data.item.num_iid);
								$('#volume').val(result.data.item.volume);
								$('#coupon_start_time').val(result.data.item.coupon_start_time);
								$('#coupon_end_time').val(result.data.item.coupon_end_time);
								$('#shop_type').val(result.data.item.tmall);
								$('#good_inventory').val(result.data.item.quantity);
								inform_html = inform_html.replace(/{title}/g, result.data.item.title).replace(/{auction_point}/g, result.data.item.auction_point).replace(/{pic_url}/g, result.data.item.pic_url).replace(/{price}/g, result.data.item.price).replace(/{nick}/g, result.data.item.nick).replace(/{num_iid}/g, result.data.item.num_iid).replace(/{detail_url}/g, result.data.item.detail_url);
                                $('#tipss').after(inform_html);
                                $('#good_link_error').html('').hide();
							}else if(result.status == 0){
								 $('#good_link_error').show().html(result.msg);
								 $('#detail').remove();
							}
						});
					}
			 }); 





			 $("#smt").click(function() {
				 var number = /^\d+$/;
				 var reg = /^(([1-9]\d*)|\d)(\.\d{1,2})?$/;
				 var url = /http\:\/\/[a-zA-Z0-9.\/]+\.taobaocdn.*\.com\/.*\.(jpg|png|gif)$/;
				 var urls = /http\:\/\/[a-zA-Z0-9.\/]+\.alicdn.*\.com\/.*\.(jpg|png|gif)$/;
				 var img_link = $("#img_link").val();
				 var cate_id = $("#J_cate_id").val();
				 var good_link = $("#good_link").val();
				 var title = $("#taobao_title").val();
				 var price = $("#price").val();
				 var nick = $("#nick").val();
				 var good_price = $("#good_price").val();
				 var intro = $('#intro').val();
				 var iid = $('#iid').val();
				 var volume = $('#volume').val();
				 var ems = $('input:radio[name=ems]:checked').val();
				 var shop_type = $('#shop_type').val();
				 var good_inventory = $("#good_inventory").val();
				 var coupon_start_time = $("#coupon_start_time").val();
				 var coupon_end_time = $("#coupon_end_time").val();
				 var textnum = $.ftxia.util.getStrLength(intro);
				 if ($("#good_link").size() > 0) {
					if (good_link == '') {
						$('#good_link_error').show().html('宝贝链接不能为空');
						$(document).scrollTop($('#good_link').offset().top);
						return false;
					}
					if ($("#good_link_error").html() != '') {
						$(document).scrollTop($('#good_link').offset().top);
						return false;
					}
				}
				if (title == '') {
					$('p.t_title').text('请输入商品标题');
					$(document).scrollTop($('#taobao_title').offset().top);
					return false;
				} else {
					$('p.t_title').text('')
				}
				if (cate_id == '-1') {
                    $('#fenlei_error').html('请选择分类').show();
                    $(document).scrollTop($('#J_cate_select').offset().top);
                    return false;
                }

				if (img_link == '') {
					$('#pic_error').html('图片链接不能为空').show();
					$(document).scrollTop($('#img_link').offset().top);
					return false;
				}
				
				if (good_inventory == '') {
					$('#good_inventorys').html('库存不能为空').show();
					$(document).scrollTop($('#good_inventory').offset().top);
					return false;
				} else {
					if (number.test(good_inventory)) {
						if (good_inventory < 200) {
							$('#good_inventorys').html('库存数量应该大于200').show();
							$(document).scrollTop($('#good_inventory').offset().top);
							return false;
						}
					} else {
						$('#good_inventorys').html('请输入数字').show();
						$(document).scrollTop($('#good_inventory').offset().top);
						return false;
					}
				}

				if (price == '' || price == 0) {
					$('#price_err').html('请输入宝贝价格').show();
					$(document).scrollTop($('#price').offset().top);
					return false;
				} else {
					if (!reg.test(price)) {
						$("#price_err").html('请输入数字').show();
						$(document).scrollTop($('#price').offset().top);
						return false;
					} 
				}

				if (good_price == '' || good_price == 0) {
					$('#good_price_err').html('请输入报名价格').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				} else {
					if (!reg.test(good_price)) {
						$("#good_price_err").html('请输入数字').show();
						$(document).scrollTop($('#good_price').offset().top);
						return false;
					} 
				}

				if (parseInt(good_price) > parseInt(price)) {
					$("#good_price_err").html('报名价格不能高于原价').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				}

				if (volume == '' ) {
					$('#volume_err').html('请输入当前销量').show();
					$(document).scrollTop($('#volume').offset().top);
					return false;
				} else {
					if (!reg.test(volume)) {
						$("#volume_err").html('请输入数字').show();
						$(document).scrollTop($('#volume').offset().top);
						return false;
					} 
				}

				if (intro == '' || textnum < 10) {
					$("#intros").html('推荐理由要求10个字以上').show();
					$(document).scrollTop($('#intro').offset().top);
					return false;
				} else if (intro == '请务必以客观的角度评价您的宝贝，否则会影响到您宝贝的审核。') {
					$("#intros").html('建议填写推荐理由').show();
					$(document).scrollTop($('#intro').offset().top);
					return false;
				}

				$.ajax({
                    url: FTXIAER.root + '/?m=goods&a=ajaxadd',
                    type: 'POST',
                    data: {
                        iid: iid,
                        cate_id: cate_id,
						title: title,
						nick: nick,
						price: price,
						good_price: good_price,
						good_inventory: good_inventory,
						volume:volume,
						ems:ems,
						pic_url: img_link,
						shop_type: shop_type,
                        intro: intro
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
							$.dialog({id:'goods_add_success', title:lang.tips, content:result.data, padding:'', fixed:true, lock:true});
                        }else{
                            $.ftxia.tip({content:result.msg, icon:'error'});
                        }
                    }
                });

			 });







			 $("#smt_edt").click(function() {
				 var number = /^\d+$/;
				 var reg = /^(([1-9]\d*)|\d)(\.\d{1,2})?$/;
				 var url = /http\:\/\/[a-zA-Z0-9.\/]+\.taobaocdn.*\.com\/.*\.(jpg|png|gif)$/;
				 var urls = /http\:\/\/[a-zA-Z0-9.\/]+\.alicdn.*\.com\/.*\.(jpg|png|gif)$/;
				 var img_link = $("#img_link").val();
				 var cate_id = $("#J_cate_id").val();
				 var good_link = $("#good_link").val();
				 var title = $("#title").val();
				 var price = $("#price").val();
				 var nick = $("#nick").val();
				 var good_price = $("#good_price").val();
				 var intro = $('#intro').val();
				 var iid = $('#iid').val();
				 var volume = $('#volume').val();
				 var ems = $('input:radio[name=ems]:checked').val();
				 var id = $('#id').val();
				 var shop_type = $('#shop_type').val();
				 var good_inventory = $("#good_inventory").val();
				 var textnum = $.ftxia.util.getStrLength(intro);
				 if ($("#good_link").size() > 0) {
					if (good_link == '') {
						$('#good_link_error').show().html('宝贝链接不能为空');
						$(document).scrollTop($('#good_link').offset().top);
						return false;
					}
					if ($("#good_link_error").html() != '') {
						$(document).scrollTop($('#good_link').offset().top);
						return false;
					}
				}
				if (title == '') {
					$('p.t_title').text('请输入商品标题');
					$(document).scrollTop($('#taobao_title').offset().top);
					return false;
				} else {
					$('p.t_title').text('')
				}
				if (cate_id == '-1') {
                    $('#fenlei_error').html('请选择分类').show();
                    $(document).scrollTop($('#J_cate_select').offset().top);
                    return false;
                }

				if (img_link == '') {
					$('#pic_error').html('图片链接不能为空').show();
					$(document).scrollTop($('#img_link').offset().top);
					return false;
				}
				
				if (good_inventory == '') {
					$('#good_inventorys').html('库存不能为空').show();
					$(document).scrollTop($('#good_inventory').offset().top);
					return false;
				} else {
					if (number.test(good_inventory)) {
						if (good_inventory < 200) {
							$('#good_inventorys').html('库存数量应该大于200').show();
							$(document).scrollTop($('#good_inventory').offset().top);
							return false;
						}
					} else {
						$('#good_inventorys').html('请输入数字').show();
						$(document).scrollTop($('#good_inventory').offset().top);
						return false;
					}
				}

				if (price == '' || price == 0) {
					$('#price_err').html('请输入宝贝价格').show();
					$(document).scrollTop($('#price').offset().top);
					return false;
				} else {
					if (!reg.test(price)) {
						$("#price_err").html('请输入数字').show();
						$(document).scrollTop($('#price').offset().top);
						return false;
					} 
				}

				if (good_price == '' || good_price == 0) {
					$('#good_price_err').html('请输入报名价格').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				} else {
					if (!reg.test(good_price)) {
						$("#good_price_err").html('请输入数字').show();
						$(document).scrollTop($('#good_price').offset().top);
						return false;
					} 
				}

				if (parseInt(good_price) > parseInt(price)) {
					$("#good_price_err").html('报名价格不能高于原价').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				}

				if (volume == '' ) {
					$('#volume_err').html('请输入当前销量').show();
					$(document).scrollTop($('#volume').offset().top);
					return false;
				} else {
					if (!reg.test(volume)) {
						$("#volume_err").html('请输入数字').show();
						$(document).scrollTop($('#volume').offset().top);
						return false;
					} 
				}

				if (intro == '' || textnum < 10) {
					$("#intros").html('推荐理由要求10个字以上').show();
					$(document).scrollTop($('#intro').offset().top);
					return false;
				} else if (intro == '请务必以客观的角度评价您的宝贝，否则会影响到您宝贝的审核。') {
					$("#intros").html('建议填写推荐理由').show();
					$(document).scrollTop($('#intro').offset().top);
					return false;
				}

				$.ajax({
                    url: FTXIAER.root + '/?m=goods&a=ajaxedit',
                    type: 'POST',
                    data: {
                        iid: iid,
						id: id,
                        cate_id: cate_id,
						title: title,
						volume: volume,
						nick: nick,
						price: price,
						good_price: good_price,
						good_inventory: good_inventory,
						ems: ems,
						pic_url: img_link,
						shop_type: shop_type,
                        intro: intro
                    },
                    dataType: 'json',
                    success: function(result){
                        if(result.status == 1){
							$.dialog({id:'goods_add_success', title:lang.tips, content:result.data, padding:'', fixed:true, lock:true});
                        }else{
                            $.ftxia.tip({content:result.msg, icon:'error'});
                        }
                    }
                });
			 });


			 $('#good_link').focusout(function() {
				if ($('#good_link').attr('value') == '') {
					$('#good_link_error').show().html('输入宝贝链接后，下方会显示宝贝部份信息。');
				}
			});

			$('#img_link').focusout(function() {
				if ($('#img_link').attr('value') == '') {
					$('#pic_error').show().html('宝贝图片不能为空');
				}
				
				if ($('#img_link').attr('value') == $('#img_link').attr('def-val')) {
					$('#pic_error').show().html('宝贝图片不能为空'); 
				}
			});


			$('#price').focusout(function() {
				if ($('#price').attr('value') == '') {
					$('#price_err').show().html('输入原价');
				}
			});

			$('#good_price').focusout(function() {
				if ($('#good_price').attr('value') == '') {
					$('#good_price_err').show().html('输入报名价格');
				}
			});

			$('#good_inventory').focusout(function() {
				if ($('#good_inventory').attr('value') == '') {
					$('#good_inventory_err').show().html('请填写库存宝贝');
				}
			});

			$('#volume').focusout(function() {
				if ($('#volume').attr('value') == '') {
					$('#volume_err').show().html('请输入当前销量');
				}
			});

			$('#intro').focusout(function() {
				if ($('#intro').attr('value') == '') {
					$('#intros').show().html('请输入推荐理由');
				}
			});


			$('#J_cate_select').change(function() {

				if ($(this).children('option:selected').val() == '-1') {
					$('#cate_id_err').show().html('请选择分类');
				}else{
					$('#cate_id_err').hide();
				}
			});

			$("#J_cate_select").focus(function() {
				$('#fenlei_error').html('').hide();
			});
			$("#good_link").focus(function() {
				$('#good_link_error').hide().html("");
			});
			$("#img_link").focus(function() {
				$('#pic_error').hide().html('');
			});
			$("#good_inventory").focus(function() {
				$('#good_inventory_err').html('').hide();
			});
			$("#price").focus(function() {
				$('#price_err').html('').hide();
			});
			$("#good_price").focus(function() {
				$('#good_price_err').html('').hide();
			});
			$("#volume").focus(function() {
				$('#volume_err').html('').hide();
			});
			$("#intro").focus(function() {
				$("#intros").html("").hide();
			});
			$("#intro").focus(function() {
				if ($("#intro").val() == "请务必以客观的角度评价您的宝贝，否则会影响到您宝贝的审核。") {
					$("#intro").val("");
					$("#intro").css("color", "#535353");
				}
			});
			$("#intro").blur(function() {
				if ($("#intro").val() == "") {
					$("#intro").val("请务必以客观的角度评价您的宝贝，否则会影响到您宝贝的审核。");
					$("#intro").css("color", "#cccccc");
				}
			});
        }
    };
    $.ftxia.goods.init();
})(jQuery);