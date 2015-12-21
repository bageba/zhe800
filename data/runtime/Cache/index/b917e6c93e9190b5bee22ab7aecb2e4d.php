<?php if (!defined('THINK_PATH')) exit();?><html><head><title>正在努力跳转到【<?php echo ($item["title"]); ?>】所在店铺</title><script type="text/javascript" src="__STATIC__/js/jquery/jquery.js"></script></head><body style="background: url(__STATIC__/images/background_bg.png) repeat;"><div style="width:100%; text-align:center; margin-top:15%;"><span style="font-family:微软雅黑; font-size:16px; color:#919191;">正在努力跳转到【<?php echo ($item["title"]); ?>】所在店铺</span><br><center><img src="<?php echo attach(get_thumb($item['pic_url'], '_b'),'item');?>"></center></div><a id="cturl" href="http://item.taobao.com/item.htm?id=<?php echo ($item["num_iid"]); ?>"></a><div style="height:1px;width:1px;overflow:hidden;"><a target="_blank" data-tmpl="230x312" data-style="2" biz-itemid="<?php echo ($item["num_iid"]); ?>" data-type="0" data-rd=1></a></div><script type="text/javascript">    (function(win,doc){
        var s = doc.createElement("script"), h = doc.getElementsByTagName("head")[0];
        if (!win.alimamatk_show) {
            s.charset = "gbk";
            s.async = true;
            s.src = "http://a.alimama.cn/tkapi.js";
            h.insertBefore(s, h.firstChild);
        };
        var o = {
            pid: "<?php echo C('ftx_taojindian_html');?>",/*推广单元ID，用于区分不同的推广渠道*/
            appkey: "",/*通过TOP平台申请的appkey，设置后引导成交会关联appkey*/
            unid: "",/*自定义统计字段*/
			plugins:[{name:"keyword"}]/*需要加载的插件：内文关键字插件*/
        };
        win.alimamatk_onload = win.alimamatk_onload || [];
        win.alimamatk_onload.push(o);
    })(window,document);
</script><script language="javascript">function get_et()
{
  var s = new Date(),
  l = +s / 1000 | 0,
  r = s.getTimezoneOffset() * 60,
  p = l + r,
  m = p + (3600 * 8),
  q = m.toString().substr(2, 8).split(""),
  o = [6, 3, 7, 1, 5, 2, 0, 4],
  n = [];
  for (var k = 0; k < o.length; k++) {
	  n.push(q[o[k]])
  }
  n[2] = 9 - n[2];
  n[4] = 9 - n[4];
  n[5] = 9 - n[5];
  return n.join("")
}

function setCookie(j, k)
{
    document.cookie = j + "=" + encodeURIComponent(k.toString()) + "; path=/"
}

function getCookie(l)
{
	var m = (" " + document.cookie).split(";"),
	j = "";
	for (var k = 0; k < m.length; k++) {
		if (m[k].indexOf(" " + l + "=") === 0) {
			j = decodeURIComponent(m[k].split("=")[1].toString());
			break
		}
	}
	return j
}

function get_pgid()
{
  var l = "",
  k = "",
  n,
  o,
  t,
  u,
  s = location,
  m = "",
  q = Math;
  function r(x, z) {
	  var y = "",
	  v = 1,
	  w;
	  v = Math.floor(x.length / z);
	  if (v == 1) {
		  y = x.substr(0, z)
	  } else {
		  for (w = 0; w < z; w++) {
			  y += x.substr(w * v, 1)
		  }
	  }
	  return y
  }
  
 n = (" " + document.cookie).split(";");
  for (o = 0; o < n.length; o++) {
	  if (n[o].indexOf(" cna=") === 0) {
		  k = n[o].substr(5, 24);
		  break
	  }
  }
  
  if (k === "") {
	  cu = (s.search.length > 9) ? s.search: ((s.pathname.length > 9) ? s.pathname: s.href).substr(1);
	  n = document.cookie.split(";");
	  for (o = 0; o < n.length; o++) {
		  if (n[o].split("=").length > 1) {
			  m += n[o].split("=")[1]
		  }
	  }
	  if (m.length < 16) {
		  m += "abcdef0123456789"
	  }
	  k = r(cu, 8) + r(m, 16)
  }
  for (o = 1; o <= 32; o++) {
	  t = q.floor(q.random() * 16);
	  if (k && o <= k.length) {
		  u = k.charCodeAt(o - 1);
		  t = (t + u) % 16
	  }
	  l += t.toString(16)
  }
  setCookie('amvid', l);
  var p = getCookie('amvid');
  if (p) {
	  return p
  }
  return l
}
	
var click_url = '';
var pid = '<?php echo C('ftx_taojindian_html');?>';
var wt = '0';
var ti = '625';
var tl = '230x45';
var rd = '1';
var ct = encodeURIComponent('itemid=<?php echo ($item["num_iid"]); ?>');
var st = '2';
var rf = encodeURIComponent(document.URL);
var et = get_et();
var pgid = get_pgid();
var v = '2.0';
$(function(){
	$.ajax({
 		url: 'http://g.click.taobao.com/display?cb=?',
    	type: 'GET',    
     	dataType: 'jsonp',
    	jsonp: 'cb', 
    	data: 'pid='+pid+'&wt='+wt+'&ti='+ti+'&tl='+tl+'&rd='+rd+'&ct='+ct+'&st='+st+'&rf='+rf+'&et='+et+'&pgid='+pgid+'&v='+v,
    	success: function(msg) {
			if(msg.code == 200)
			{
				document.location.href = msg.data.items[0].ds_item_click;
			}
			
		},    
		error: function(msg){    
        	document.location.href = click_url;
		}    
	});  
});

</script></body></html>