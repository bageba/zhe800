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
				  alert('asdg');
			});

		},
        goods_add: function(){
             $(".txt:first").focusout(function() {
				  var inform_html = '<li id="detail">' 
					+ '<label></label>' 
					+ '<div class="goodsdetail">' 
					+ '<div class="pic">' 
					+ '<a href="{detail_url}" target="_blank">' 
					+ '<img src="{pic_url}" width="100" height="100"/></a>' 
					+ '</div>' 
					+ '<div class="detail">' 
					+ '<p class="title"><input id="taobao_title" name="taobao_title" type="text" class="txt" value="{title}" maxlength="35"/></p>' 
					+ '<p class="red t_title">请注意修改标题，标题内不允许出现其他活动名称、包邮和新品等字样</p>' 
					+ '<p class="seller">' 
					+ '<span></span>&nbsp;<input id="nick" name="nick" type="hidden" class="txt" value="{nick}" />' 
					+ '</p>' 
					+ '<p>' 
					+ '<span class="price"><em>￥</em>{price}</span>' 
					+ '</p>' 
					+ '</div>' 
					+ '</div>' 
					+ '</li>';
					var link = $(".txt:first").attr('value');
					if (link != '') {
						 $.getJSON(FTXIAER.root + '/?m=goods&a=ajaxGetItem', {url:link}, function(result){
							if(result.status == 1){
								if ($('#detail').size() != 0) {
									$('#detail').remove();
								}
								if(result.data.freight_payer == 'buyer'){
									$('#ems').val(0);	
								}else{
									$('#ems').val(1);	
								}
								if(result.data.price > 0 && result.data.coupon_price > 0){
									$('#coupon_rate').val((result.data.coupon_price/result.data.price).toFixed(2)*10000);
									//$('#coupon_rate').val(Math.round(result.data.coupon_price/result.data.price*100)/10);
								}
								if(/taobao\.com/.test(link)){
									$('#shop_type option').get(0).selected = true;
									$('#shop_type option').get(1).selected = false;
								}else{
									$('#shop_type option').get(0).selected = false;
									$('#shop_type option').get(1).selected = true;	
								}
								//alert(result.data.title);
								good_inventory:
								$('#good_inventory').val(result.data.volume);
								$('#img_link').val(result.data.pic_url);
								$('#price').val(result.data.price);
								$('#iid').val(result.data.num_iid);
								//$('#good_inventory').val(result.data.num);
								$('#price').val(result.data.price);
								$('#good_price').val(result.data.coupon_price);
								$('#coupon_start_time').val(result.data.coupon_start_time);
								$('#coupon_end_time').val(result.data.coupon_end_time);
								inform_html = inform_html.replace(/{title}/g, result.data.title).replace(/{auction_point}/g, result.data.auction_point).replace(/{pic_url}/g, result.data.pic_url).replace(/{price}/g, result.data.coupon_price).replace(/{nick}/g, result.data.nick).replace(/{num_iid}/g, '').replace(/{detail_url}/g, link);
                                $('#tipss').after(inform_html);
                                
							}else if(result.status == 1005){
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
				 var img_link = $("#img_link").val();
				 var cate_id = $("#J_cate_id").val();
				 var good_link = $("#good_link").val();
				 var title = $("#taobao_title").val();
				 var price = $("#price").val();
				 var nick = $("#nick").val();
				 var good_price = $("#good_price").val();
				 var coupon_rate = $("#coupon_rate").val();
				 var intro = desc;
				 var iid = $('#iid').val();
				 var shop_type = $('#shop_type').val();
				 var good_inventory = $("#good_inventory").val();
				 var textnum = $.ftxia.util.getStrLength(intro);
				 var coupon_start_time = $('#coupon_start_time').val();
				 var coupon_end_time = $('#coupon_end_time').val();
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
				} else if (!url.test(img_link)) {
					//$('#pic_error').html('请输入正确的淘宝图片链接').show();
					//$(document).scrollTop($('#img_link').offset().top);
					//return false;
				}

				if (good_inventory == '') {
					$('#good_inventorys').html('销量不能为空').show();
					$(document).scrollTop($('#good_inventory').offset().top);
					return false;
				} else {
					if (number.test(good_inventory)) {
						if (good_inventory < 1) {
							$('#good_inventorys').html('销量数量应该大于200').show();
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
					$('#prices').html('请输入宝贝价格').show();
					$(document).scrollTop($('#price').offset().top);
					return false;
				} else {
					if (!reg.test(price)) {
						$("#prices").html('请输入数字').show();
						$(document).scrollTop($('#price').offset().top);
						return false;
					} 
				}

				if (good_price == '' || good_price == 0) {
					$('#good_prices').html('请输入报名价格').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				} else {
					if (!reg.test(good_price)) {
						$("#good_prices").html('请输入数字').show();
						$(document).scrollTop($('#good_price').offset().top);
						return false;
					} 
				}

				if (parseInt(good_price) > parseInt(price)) {
					$("#good_prices").html('活动价格不能高于商品原价').show();
					$(document).scrollTop($('#good_price').offset().top);
					return false;
				}
				var	data={
                        iid: iid,
                        cate_id: cate_id,
						title: title,
						nick: nick,
						price: price,
						good_price: good_price,
						coupon_rate: coupon_rate,
						good_inventory: good_inventory,
						pic_url: img_link,
						shop_type: shop_type,
                        intro: intro
                    };
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
						coupon_rate:coupon_rate,
						good_inventory: good_inventory,
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


			$("#J_cate_select").focus(function() {
				$('#fenlei_error').html('').hide();
			});
			$("#good_link").focus(function() {
				$('#good_link_error').html('').hide();
			});
			$("#img_link").focus(function() {
				$('#pic_error').hide().html('');
			});
			$("#good_inventory").focus(function() {
				$('#good_inventorys').html('').hide();
			});
			$("#price").focus(function() {
				$('#prices').html('').hide();
			});
			$("#good_price").focus(function() {
				$('#good_prices').html('').hide();
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