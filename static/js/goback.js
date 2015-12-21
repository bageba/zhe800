(function($) {
	var $backToTopEle = $('<div class="backToTop"></div>');
	$backToTopEle.appendTo($("body"));
	var $backToTop = $(".backToTop");
	var F_backTo_hover = function() {
			$backToTop.hover(function() {
				$backToTop.css("background-position", "0px -185px")
			}, function() {
				$backToTop.css("background-position", "0px -20px")
			})
		}
	F_backTo_hover();
	var F_backTo_click = function() {
			$backToTopEle.click(function() {
				$backToTop.css("background-position", "0px -385px");
				var native = function() {
						$backToTop.css("background-position", "0px -20px");
					}
				$("html, body").animate({
					scrollTop: 0
				}, 220, native);
			});
		}
	var F_backTo_click_tip = function() {
			$backToTopEle.click(function() {
				$backToTop.css("background-position", "0px -385px");
				var native = function() {
						$backToTopEle.unbind();
						F_backTo_hover();
						F_backTo_click();
						$backToTop.css("background-position", "0px -20px");
					}
				$("html, body").animate({
					scrollTop: 0
				}, 220, native);
			});
		}
	F_backTo_click_tip();
	var $backToTopFun = function() {
			var st_back = $(document).scrollTop(),
				winh_back = $(window).height(),
				footh = $("div.foot").height(),
				doch_back = $(document).height();
			(st_back > winh_back) ? $backToTopEle.fadeIn() : $backToTopEle.hide();
			if (!window.XMLHttpRequest) {
				$backToTopEle.css("top", st_back + winh_back - 166);
			}
		};
	$(window).bind("scroll", $backToTopFun);
	$backToTopFun();
})(jQuery);;

(function($) {
	var funcIndexSlide = function() {
			var scrollClock = null;
			var curPic = 1;
			var lock = false;
			var ptype = null;
			var price = 0;
			var num = 0;
			var total = 0;
			var picBox = $('#pics ul');
			var goNext = function(n) {
					lock = true;
					if (n > 4) {
						n = 1;
					}
					var steps = n - curPic;
					if (steps < 0) {
						steps += 4;
					}
					var t_a = $('#free_title a');
					var p_t = $('#free_title');
					var t_s_d = $(".top_slide_desc:first");
					var step = function() {
							var during = 0;
							var marl = -(n - 1) * 230 + "px";
							var mart = -(n - 1) * 16 + "px";
							picBox.animate({
								'marginLeft': marl
							}, 300, function() {
								steps -= 1;
								if (steps > 0) {
									step();
								} else {
									var spans = $('.switch li');
									spans.removeClass('active');
									spans.eq(n - 1).addClass('active');
									t_a.hide();
									t_a.eq((n - 1)).show();
									t_s_d.find("p").hide();
									t_s_d.find(".c_p_" + n).show();
									lock = false;
								}
							});
						};
					step();
					curPic = n;
					scrollClock = setTimeout(function() {
						goNext(curPic + 1)
					}, 4E3);
				};
			$('.switch li').click(function() {
				if (true) {
					clearTimeout(scrollClock);
					var picNum = $(".switch li").index($(this)) + 1;
					if (picNum != curPic) {
						goNext(picNum);
					}
				}
			});
			$("#pics").mouseover(function() {}).mouseout(function() {});
			scrollClock = setTimeout(function() {
				goNext(curPic + 1)
			}, 5E3);
		};
	funcIndexSlide();
})(jQuery);


(function($) {

	$(".like-state .like").live('click', function() {
		var pid = $(this).attr("data-pid");
		if(!$.ftxia.dialog.islogin()) return ;
		$.ajax({
			url: FTXIAER.root + '/?m=ajax&a=like',
				type: 'POST',
				data: {
				pid: pid
			},
			dataType: 'json',
			success: function(result){
				if(result.status == 1){
					$.ftxia.tip({content:result.msg, icon:'success'});
				}else if(result.status == 2){
					$.ftxia.tip({content:result.msg, icon:'error'});
				}else{
					$.ftxia.tip({content:result.msg, icon:'error'});
				}
			}
		});
		  
	});

})(jQuery);