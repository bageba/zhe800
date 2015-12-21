var cont=document.getElementById('cont');
var tsxx=document.getElementById('tsxx');
function cai(){
var fm = document.getElementById('myForm');
var fd = new FormData(fm);
      //AJAX部分
	  var xhr = new XMLHttpRequest();
	  xhr.open('post',curlbegin,true);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){ 
            var rz =this.responseText;
            var json = eval('('+rz+')');
            if (json.status==0) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }else if(json.status==2){
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }else if (json.status==3) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }else if (json.status==4) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }else if (json.status==5) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }else if (json.status==1) {
	            cont.innerHTML='';
	            tsxx.innerHTML='';
	            cont.style.display='block';
	            cont.innerHTML = json.data; 
	            tsxx.innerHTML='采集完成！请选择是否添加！';           	
            }
	   }
	  }
	  xhr.send(fd);
}

function begin(){
tsxx.innerHTML='采集中....请稍后....';
cont.innerHTML = '';
setTimeout('cai()',1000);
}
function add(){
	 setTimeout('addok()',1000);
     tsxx.innerHTML='正在添加...请稍后...';
}
function addok(){
	      //AJAX部分
	  var xhr = new XMLHttpRequest();
	  xhr.open('get',curladditems,true);
	  xhr.onreadystatechange =function(){
	   if((this.readyState==4)&&(this.status==200)){
            var rz =this.responseText;
            var json = eval('('+rz+')');
		   if(json.status==1){
            var rz =this.responseText;
            var json = eval('('+rz+')');
            tsxx.innerHTML = '添加完成'; 
            cont.innerHTML = json.data;
		   }else if (json.status==11) {
            	cont.innerHTML='';
	            tsxx.innerHTML='';
	            tsxx.innerHTML=json.data;
	            return;
            }

	   }else{
	   	tsxx.innerHTML ="添加失败,请从新采集!"
	   }
	  }
	  xhr.send();
}