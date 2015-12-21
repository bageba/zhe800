/* *
 * 给定一个剩余时间（s）动态显示一个剩余时间.
 * 当大于一天时。只显示还剩几天。小于一天时显示剩余多少小时，多少分钟，多少秒。秒数每秒减1 *
 */

// 初始化变量
var auctionDate = Array;
var _GMTEndTime = 0;
var showTime = "leftTime";
var _day = 'day';
var _hour = 'hour';
var _minute = 'minute';
var _second = 'second';
var _end = 'end';

var cur_date = new Date();
var startTime = cur_date.getTime();
var Temp;
var timerID = null;
var timerRunning = false;

function showtime(i)
{
	now = new Date();
	var ts = parseInt((startTime - now.getTime()) / 1000) + auctionDate[i];
	var dateLeft = 0;
	var hourLeft = 0;
	var minuteLeft = 0;
	var secondLeft = 0;
	var hourZero = '';
	var minuteZero = '';
	var secondZero = '';
	var end_texts = '';
	if (gmt_text[i]){
		end_texts = '剩余：';
	}else{
		end_texts = '剩余：';
	}
	if (ts < 0){
		ts = 0;
		CurHour = 0;
		CurMinute = 0;
		CurSecond = 0;
	}else{
		dateLeft = parseInt(ts / 86400);
		ts = ts - dateLeft * 86400;
		hourLeft = parseInt(ts / 3600);
		ts = ts - hourLeft * 3600;
		minuteLeft = parseInt(ts / 60);
		secondLeft = ts - minuteLeft * 60;
	}
	if (hourLeft < 10)
		hourZero = '0';
	if (minuteLeft < 10)
		minuteZero = '0';
	if (secondLeft < 10)
		secondZero = '0';
	if (dateLeft > 0){
		Temp = end_texts+dateLeft + _day + hourZero + hourLeft + _hour + minuteZero + minuteLeft + _minute + secondZero + secondLeft + _second;
	}else{
		if (hourLeft > 0){
			Temp = end_texts+hourLeft + _hour + minuteZero + minuteLeft + _minute + secondZero + secondLeft + _second;
		}else{
			if (minuteLeft > 0){
				Temp = end_texts+minuteLeft + _minute + secondZero + secondLeft + _second;
			}else{
				if (secondLeft > 0){
					Temp = end_texts+secondLeft + _second;
				}else{
					Temp = '还有机会哦！';
				}
			}
		}
	}
	if (auctionDate <= 0 || Temp == ''){
		if (!document.getElementById('group_butn').disabled){  
			document.getElementById('group_butn').disabled='true';
			window.location.reload();
		}
		Temp = "<strong>" + _end + "</strong>";
		stopclock();
	}
	if (document.getElementById(showTime+i)){
		document.getElementById(showTime+i).innerHTML = Temp;
	}
}

var timerID = null;
var timerRunning = false;
function stopclock(){
	if (timerRunning){
		clearTimeout(timerID);
	}
	timerRunning = false;
}

function macauclock(i){
	//stopclock();
	showtime(i);
}

function onload_leftTime(now_time,count){
	_day = day;
	_hour = hour;
	_minute = minute;
	_second = second;
	_end = end;
	stopclock();
	for(i=1;i<=count;i++){
		/* 第一次运行时初始化语言项目 */
		try{
			_GMTEndTime = gmt_end_time[i];
		}catch (e){}
		if (_GMTEndTime > 0){
			//alert(parseInt(_GMTEndTime) - now_time);
			if (now_time == undefined){
				var tmp_val = parseInt(_GMTEndTime) - parseInt(cur_date.getTime() / 1000 + cur_date.getTimezoneOffset() * 60);
			}else{
				var tmp_val = parseInt(_GMTEndTime) - now_time;
			}
			if (tmp_val > 0){
				auctionDate[i] = tmp_val;
			}
		}
	
		showtime(i);
		try{
			initprovcity();
		}
		catch (e){}
	}
	timerID = setTimeout("onload_leftTime("+now_time+","+count+")", 1000);
	timerRunning = true;
}
