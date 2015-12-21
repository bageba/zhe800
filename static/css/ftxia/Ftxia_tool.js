$(document).ready(function() {
    $('#key').focus(function() {
        if ($('#key').attr('value') == '输入您想要的宝贝') {
            $('#key').attr('value', '')
        }
    });
    $('#key').focusout(function() {
        if ($('#key').attr('value') == '') {
            $('#key').attr('value', '输入您想要的宝贝')
        }
    });
    $("#searchform").submit(function() {
        if ($('#key').attr('value') == '输入您想要的宝贝') {
            alert('输入您想要的宝贝');
            return false
        }
    });

   $(".buy .buy_bt ,.buy p a,.buy .buy_cen a").click(function(){
			var itemid = $(this).attr('data-item');
			$.get('ajax.php',{type:'click',id:itemid});
	});


	var droplist = function() {
		$(".toolbar .barmenu").hover(function() {
			$(this).addClass("menu-hover");
		},
		function() {
			$(this).removeClass("menu-hover");
		});
	};
	var addQQshortcut = function() {
		$(".add_qq").click(function() {
			window.open('http://shuqian.qq.com/post?from=3&jumpback=2&noui=1&uri=' + encodeURIComponent(document.location.href) + '&title=' + encodeURIComponent(document.title), '', 'width=930,height=570,left=50,top=50,toolbar=no,menubar=no,location=no,scrollbars=yes,status=yes,resizable=yes');
			void(0);
		});
	}
	droplist();
	addQQshortcut();

	Globe_AddCollect = function(sURL, sTitle)
	 {
		try
		 {
			window.external.addFavorite(sURL, sTitle);
		}
		 catch(e)
		 {
			try
			 {
				window.sidebar.addPanel(sTitle, sURL, "");
			}
			 catch(e)
			 {
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		}
	}

	Globe_Collect_Index = function() {

		var href = "http://www.miaoshanet.com";
		var title = "秒杀网";

		$(".add_col").click(function() {
			Globe_AddCollect(href, title);
		});
	}

	Globe_Collect_Index();
		                 
});