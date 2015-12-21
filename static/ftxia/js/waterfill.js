$(document).ready(function(){
	var $container = $('#container');
	$container.imagesLoaded(function(){
		$container.fadeIn();
		$container.masonry({
			itemSelector: '.item',
			columnWidth: 1 //每两列之间的间隙为5像素
		});
	});
	
 


	var loading = $("#loading").data("on", false);
	var ajaxpage = 2;
	$(window).scroll(function(){
		if(loading.data("on")) return;
		if($(document).scrollTop() > 
			$(document).height()-$(window).height()-$('.foot').height()-100){
			//加载更多数据
			loading.data("on", true).fadeIn();
			$.get( ajaxurl, 
				{"ajaxpage" : ajaxpage},
				function(data){
					if(data.status == 1){
						var $newElems = $(data.data).appendTo($container);;
							
						$newElems.imagesLoaded(function(){
							$container.masonry( 'appended', $newElems, true ); 
						});
						ajaxpage++;
						loading.data("on", false);
						
					}
					loading.fadeOut();
				},
				"json"
			);
		}
	});
	
 
	
});
 