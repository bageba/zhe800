var cont=document.getElementById('cont');
var tsxx=document.getElementById('tsxx');
function cai(flag){     
      //AJAX部分
	  var xhr = new XMLHttpRequest()||new ActiveXObject("Microsoft.XMLHTTP");
	  xhr.open('get',curlauto_cai+'&flag='+flag ,true);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){ 
            var rz =this.responseText;
            var json = eval('('+rz+')');
            var overtx   = json.msg.nextcate;
            var tx   = json.msg;
            if (json.status==0){
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data+'------>开始采集下个【'+overtx+'】分类';
              setTimeout(cai("ff"),1000);
            }else if (json.status==1) {
	            cont.innerHTML='';
	            tsxx.innerHTML='';
	            cont.style.display='block';
	            cont.innerHTML = json.data; 
	            tsxx.innerHTML='【'+tx+'】分类采集完成！即将添加！';
              setTimeout(add(tx),3000);           	
            }else if(json.status==9){
              cont.innerHTML='';
              tsxx.innerHTML='';
              tsxx.innerHTML=json.data; 
            }else if(json.status==10){
              cont.innerHTML='';
              tsxx.innerHTML='';
              tsxx.innerHTML=tx+json.data; 
            }else if(json.status==11){
              cont.innerHTML='';
              tsxx.innerHTML='';
              tsxx.innerHTML=json.data; 
            }else if(json.status==110){
              cont.innerHTML='';
              tsxx.innerHTML='';
              tsxx.innerHTML=json.data; 
            }else{
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=rz;
            }
	   }
	  }
	  xhr.send();
}
function zhunbei(){
  var tx ='';
  $.ajax({
   url:curlfirst,
   async:false,
   success:function(msg){
       if(msg.status==8) {
        tx = msg.data;
        tsxx.innerHTML='开始采集【'+tx+'】分类';}},
   dataType:'json'
  });
  setTimeout(cai('start'),3000);
}
function begin(){
tsxx.innerHTML='<font color="green">采集准备中...</font>';
cont.innerHTML = '';
setTimeout("zhunbei()",1000);
}
function add(name){
  tsxx.innerHTML='正在添加到【'+name+'】分类';
	setTimeout(addok(),1000);
     
}
function addok(){
	      //AJAX部分
	  var xhr = new XMLHttpRequest()||new ActiveXObject("Microsoft.XMLHTTP");
	  xhr.open('get',curladditems,true);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){ 
            var rz =this.responseText;
            var json = eval('('+rz+')');
            tsxx.innerHTML = '【'+json.msg.cname+'】分类宝贝添加完成------->【'+json.msg.nextcate+'】分类采集中...'; 
            cont.innerHTML = json.data;
            setTimeout("cai('ff')",1000);
	   }else{
	   	tsxx.innerHTML ="添加失败,请从新采集!";
	   }
	  }
	  xhr.send();
}

//绑定对应模块
var windowWidth = $(document).width();
var windowHeight = $(document).height();
$('.bd').click(function(){
$('#bd span:first-child').css('display','block');
$('#bd span:last-child').css('display','none');
	var cid = $(this).attr('name');
   document.cookie='bzfid'+ "=" +escape(cid);
   $('<div class="mask"></div>').appendTo($('body'));
   $('div.mask').css({
       'opacity':0.6,
	   'background':'#000',
	   'position':'absolute',
	   'top':0,
	   'left':0,
       'width':windowWidth,
       'height':windowHeight,
       'z-index':1000				   
   });
   $('#zhe').css({
   	'display':'block',
   	'left':windowWidth/3,
   	'top':windowHeight/4
   });
});
$('#bd span:first-child').click(function(){
	$('#zhe').css('display','none');
	$('.mask').hide();
    bind();
});
function bind(){
var fm = document.getElementById('myForm');
var fd = new FormData(fm);
      //AJAX部分
	  var xhr = new XMLHttpRequest()||new ActiveXObject("Microsoft.XMLHTTP");
	  xhr.open('post',curlbind,false);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){ 
            var rz =this.responseText;
            var json = eval('('+rz+')');
            if (json.status==4) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            window.location.href = curl;
	            return;         	
            }else if(json.status==5){
                cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }
	   }
	  }
	  xhr.send(fd);
}
//修改绑定
$('.xgbd').click(function(){
var cid = $(this).attr('name');
document.cookie='xgfid'+ "=" +escape(cid);
   $.post(
   	curlbindcx,
   	{'bdid':cid},
   	function(msg){
   	  var json = eval('('+msg.data.data+')');
      var temp = new Array();
      var i = 0;
      for(var key in json){
        temp[i] = key;
        i++;
      }
      var j;
      var len = temp.length;
      var inputs = $('input');
       for(j=0;j<len;j++){
          for (var n = inputs.length - 1; n >= 0; n--) {
                var k = $(inputs[n]).attr('name');
                if(k==temp[j]){
                   $(inputs[n]).attr('checked','checked');
               }
            }         
       }
      },
   	'json'
   	);
   $('<div class="mask"></div>').appendTo($('body'));
   $('div.mask').css({
       'opacity':0.6,
	   'background':'#000',
	   'position':'absolute',
	   'top':0,
	   'left':0,
       'width':windowWidth,
       'height':windowHeight,
       'z-index':1000				   
   });

   $('#zhe').css({
   	'display':'block',
   	'left':windowWidth/3,
   	'top':windowHeight/4
   });
$('#bd span:first-child').css('display','none');
$('#bd span:last-child').css('display','block');
});

$('#bd span:last-child').click(function(){
	$('#zhe').css('display','none');
	$('.mask').hide();
	xgbind();
});

function xgbind(){
var fm = document.getElementById('myForm');
var fd = new FormData(fm);
      //AJAX部分
	  var xhr = new XMLHttpRequest()||new ActiveXObject("Microsoft.XMLHTTP");
	  xhr.open('post',curlxgbind,false);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){ 
            var rz =this.responseText;
            var json = eval('('+rz+')');
            if (json.status==6) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            window.location.href = curl;
	            return;         	
            }else if(json.status==7){
                cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }
	   }
	  }
	  xhr.send(fd);
}

/*关闭绑定选择窗口*/
$('#ubd span:first-child').click(function(){
  $('#zhe').css('display','none');
  $('.mask').hide();
  $('input').removeAttr('checked');
});
//取消绑定操作
$('.qxbd').click(function(){
  var qxcate = $(this).attr('name');
  if(confirm('确定要取消？')){
	  $.get(curlqxbd+'&qxcate='+qxcate,function(msg){
		alert(msg);
		window.location.href=curl;
	  });  
  }

});	


function qh(){
   window.location.href=curlonezhe;
}