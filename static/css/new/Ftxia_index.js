var timeout         = 500;
var closetimer		= 0;
var ddmenuitem      = 0;
function nav_open(){	
	nav_canceltimer();
	nav_close();
	ddmenuitem = $(this).find('ul').eq(0).css('visibility', 'visible');
}

function nav_close(){	
	if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');
}

function nav_timer(){	
	closetimer = window.setTimeout(nav_close, timeout);
}

function nav_canceltimer(){	
	if(closetimer){	
		window.clearTimeout(closetimer);
		closetimer = null;
	}
}

$(document).ready(function(){	
	$('#nav > li').bind('mouseover', nav_open);
	$('#nav > li').bind('mouseout',  nav_timer);
	$('#stateid').mouseenter(function(){$(this).addClass('hover');});
	$('#stateid').mouseleave(function(){$(this).removeClass('hover');});
	$('#orderid').mouseenter(function(){$(this).addClass('hover');});
	$('#orderid').mouseleave(function(){$(this).removeClass('hover');});

	$('<div style="display:none" id=goTopBtn><a class="b_img"  title="»Øµ½¶¥²¿" ></a></div>').appendTo('body');

	var obj=document.getElementById("goTopBtn");
	function getScrollTop(){
		return document.documentElement.scrollTop+document.body.scrollTop;
	}
	function setScrollTop(value){
		document.body.scrollTop=value;
		document.documentElement.scrollTop=value;
	}    
	window.onscroll=function(){getScrollTop()>0?obj.style.display="":obj.style.display="none";}
	obj.onclick=function(){
		var goTop=setInterval(scrollMove,10);
		function scrollMove(){
			setScrollTop(getScrollTop()/1.8);
			if(getScrollTop()<1)clearInterval(goTop);
			}
	}
	
	});

document.onclick = nav_close;
$(window).scroll(function () {
		if($(document).scrollTop()>220){
			$('.daohang').css({'position':'fixed','top':'0px','margin-top':'0px'});
			$('.bar_line').css({'display':'block'});
		}
		if($(document).scrollTop()<=220){
			$('.daohang').css('position','');
			$('.bar_line').css({'display':'none'});
		}

});

 $(function(){
	var nav_li = $('#nav li');
	nav_li.click(function(){
		$(this).addClass('selected')
			   .siblings().removeClass('selected');
	});
});

