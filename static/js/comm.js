(function($) {
	$.fn.JP_hoverDelay = function(options) {
		var defaults = {
			hoverDuring: 300,
			outDuring: 300,
			hoverEvent: function() {
				$.noop()
			},
			outEvent: function() {
				$.noop()
			}
		};
		var sets = $.extend(defaults, options || {});
		var hoverTimer, outTimer;
		return $(this).each(function() {
			var t = this;
			$(this).hover(function() {
				if (sets.outDuring !== 0) {
					clearTimeout(outTimer);
				};
				hoverTimer = setTimeout(function() {
					sets.hoverEvent.apply(t)
				}, sets.hoverDuring)
			}, function() {
				if (sets.hoverDuring !== 0) {
					clearTimeout(hoverTimer);
				};
				outTimer = setTimeout(function() {
					sets.outEvent.apply(t)
				}, sets.outDuring)
			})
		})
	};
})(jQuery);


;(function($){
	//提示
	if('close'==$.ftxia.util.getCookie('leftimg')){
		$('.tips_goods').css('display','none');
	}else{
		$('.tips_goods').css('display','block');
	}
	$('.iknow').live('click', function(){
		$('.tips_goods').css('display','none');
		$.ftxia.util.setCookie('leftimg', 'close');
		$('.tips_goods').remove();
	});

	//报名查询
	$('.chaxun').live('click', function(){
		if(!$.ftxia.dialog.islogin()) return !1;
		$.getJSON(FTXIAER.chaxunurl, function(result){
			if(result.status == 1){
				$.dialog({id:'check_item', title:lang.chaxun, content:result.data, padding:'', fixed:true, lock:true});
				$.ftxia.item.check_form($('#J_check_item'));
			}else{
				$.ftxia.tip({content:result.msg, icon:'error'});
			}
		});
	});
	flag = true;
	$(window).resize(function() {
        $('.white_dh #fold').css('right', ($(window).width() - $(".wrapper").width()) / 2 - 34);
    });
    $(window).bind("scroll", 
    function() {
        if ($('.double_main').size() == 1) {
            var temp = '408';
        } else {
            var temp = '159';
        }
        if (flag == true && $(document).scrollTop() > temp) {
            flag = false;
            $('.bar_line').css('display', 'block');
			
            if ("fold" == $.ftxia.util.getCookie("fold")) {
				$('.white_dh #fold').attr('class', 'filter-unfold');
            } else {
				$('.daohang').css({
                    'position': 'fixed',
                    'top': '0px',
                    'margin-top': '0px'
                });                
            }
            $('.white_dh #fold').css('display', 'block');
            $('.white_dh #fold').css('right', ($(window).width() - $(".wrapper").width()) / 2 - 34);
        }
        if (flag == false && $(document).scrollTop() <= temp) {
            flag = true;
            $('.daohang').css('position', '');
            $('.white_dh #fold').css('display', 'none');
            $('.bar_line').css('display', 'none');
        }
    });
    $('.filter-fold').live('click', 
    function() {
        $('.daohang').css('position', 'static');
        $('.white_dh #fold').attr('class', 'filter-unfold');
        $.ftxia.util.setCookie("fold", 'fold');
    });
    $('.filter-unfold').live('click', 
    function() {
        $('.daohang').css({
            'position': 'fixed',
            'top': '0px',
            'margin-top': '0px'
        });
        $('.white_dh #fold').attr('class', 'filter-fold');
        $.ftxia.util.setCookie("fold", '');
    });
	
	 //order
	$('#stateid').mouseenter(function(){$(this).addClass('hover');});
	$('#stateid').mouseleave(function(){$(this).removeClass('hover');});
	$('#orderid').mouseenter(function(){$(this).addClass('hover');});
	$('#orderid').mouseleave(function(){$(this).removeClass('hover');});
	$('.barmenu').mouseenter(function(){$(this).addClass('menu-hover');});
	$('.barmenu').mouseleave(function(){$(this).removeClass('menu-hover');});

 
	var F_s = function() {
			if (document.getElementById("k")) {
				document.getElementById("k").onclick = function() {
					var val = $("#key").attr("value");
					var defval = $("#key").attr("def-val");
					if ( val == defval) {
						$("#key").focus();
						return false;
					}
					var ekv = FTXIAER.site +'/?m=search&a=etao&key=' + val;
					window.location.href = FTXIAER.site +'/?m=search&a=index&key=' + val;
					return false;
				}
			}
			if (document.getElementById("ek")) {
				document.getElementById("ek").onclick = function() {
					var val = $("#key").attr("value");
					var defval = $("#key").attr("def-val");
					if (val == defval) {
						$("#key").focus();
						return false;
					}
					var ekv = FTXIAER.site +'/?m=search&a=etao&key=' + val;
					window.open(ekv, "_blank");
					return false;
				}
			}
		}
	F_s();


 




	//新增js
	var normal = $("div.normal")[0];
	$(normal).hover(function() {
		$("div.zone-box").show();
		$(this).find("em").addClass("open");
	}, function() {
		$("div.zone-box").hide();
		$(this).find("em").removeClass("open");
	});
	var normal_new = $("div.normal")[1];
	$(normal_new).hover(function() {
		$(this).find(".login-box").show();
		$(this).find("em").addClass("open");
	}, function() {
		$(this).find(".login-box").hide();
		$(this).find("em").removeClass("open");
	});
	var sign = $("div.sign");
	sign.hover(function() {
		$(this).find(".box-sign").show();
	}, function() {
		$(this).find(".box-sign").hide();
	});


 
	F_list_good_hover = function() {
		var $good = $("ul.goods-list li");
		var $good_fill = $("ul.goods-list li.fill");
		$good.JP_hoverDelay({
			hoverDuring: 100,
			outDuring: 0,
			hoverEvent: function() {
				$.browser.msie && ($.browser.version == "6.0") && !$.support.style ? "" : $(this).addClass("active");
				$(this).find(".icon.t").css("display", "inline-block");
			},
			outEvent: function() {
				$(this).removeClass("active");
				$(this).find(".icon.t").css("display", "none");
			}
		});
	}
	F_list_good_hover();


	F_jiu_list = function() {
		var $nav_a = $(".nav-classify li");
		var $nav_app = $nav_a.find('.tips');
		var $nav_app_close = $nav_app.find('.close');
		var F_nav = function() {
			$nav_a.find(".classify-a").hover(function(){
				$nav_a.each(function(index, val) {
					$(val).find("p").css("display", "none");
					$(val).find(".classify-a").removeClass("hover");
				});
				$(this).closest("li").find("p").css("display", "block");
				if(!$(this).closest("li").hasClass('app')) {
					$(this).addClass("hover");
				}else{
					$("div.jiu-nav").css("z-index", "22");
					$(".nav-classify li").find('.close').hide();
				}
			},function(){
				$(this).closest("li").hover(function() {}
					, function() {
						$(this).find("p").css("display", "none");
						if (!$(this).closest("li").hasClass('app')) {
							$(this).find(".classify-a").removeClass("hover");
						} else {
							$("div.jiu-nav").css("z-index", "18");
							$(".nav-classify li").find('.close').show();
						}
					});

			}
		)};
		F_nav();
	}
	F_jiu_list();


	var $navFun = function() {
			var st = $(document).scrollTop(),
				winh = $(window).height(),
				doch = $(document).height(),
				headh = $("div.head").height(),
				navh = $("div.widescreen").height(),
				footh = $("div.foot").height(),
				pageh = $("div.page").height(),
				mainh = $("div.main").height(),
				classh = $("ul.nav-classify").height();
			$nav_classify = $("ul.nav-classify");
			if (st + winh > doch - footh - pageh - 16 && winh < footh + pageh + classh) {
				$nav_classify.removeClass("fixed");
				$nav_classify.addClass("bottom");
				$("ul.nav-classify.bottom").css("top", doch - footh - pageh - 16 - classh - headh - 42 - 92 + 6)
			} else {
				$("ul.nav-classify.bottom").css("top", 42)
				$nav_classify.removeClass("bottom");
				if (st > headh + 42 + 92) {
					$nav_classify.addClass("fixed");
				} else {
					$nav_classify.removeClass("fixed");
				}
			}
			if (!window.XMLHttpRequest) {}
		};
	var F_nav_scroll = function() {
			$(window).bind("scroll", $navFun);
		}
	$navFun();
	F_nav_scroll();


	//End新增



	$('.add_qq').live('click', function(){
		window.open('http://shuqian.qq.com/post?from=3&jumpback=2&noui=1&uri=' + encodeURIComponent(document.location.href) + '&title=' + encodeURIComponent(document.title), '', 'width=930,height=570,left=50,top=50,toolbar=no,menubar=no,location=no,scrollbars=yes,status=yes,resizable=yes');
		void(0);
	});

	$('.add_col').live('click', function(){
		var sURL = document.location.href;
		var sTitle = document.title;
		try{
			window.external.addFavorite(sURL, sTitle);
		}catch(e){
			try{
				window.sidebar.addPanel(sTitle, sURL, "");
			}catch(e){
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		}
	});

})(jQuery);