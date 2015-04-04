<div class="row-fluid">
  <div id="footer" class="span12">  </div>
</div>

<!--end-Footer-part-->
<script type="text/javascript">
    $(function(){

    });

    function GetTime() {
	var mon, day, now, hour, min, ampm, time, str, tz, end, beg, sec;
	/*
	mon = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
			"Sep", "Oct", "Nov", "Dec");
	*/
	mon = new Array("1", "2", "3", "4", "5", "6", "7", "8","9", "10", "11", "12");
	/*
	day = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	*/
	day = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
	now = new Date();
	hour = now.getHours();
	min = now.getMinutes();
	sec = now.getSeconds();
	if (hour < 10) {
		hour = "0" + hour;
	}
	if (min < 10) {
		min = "0" + min;
	}
	if (sec < 10) {
		sec = "0" + sec;
	}
	$("#Timer").html(
			"<nobr>" + now.getFullYear() + "年" + mon[now.getMonth()] + "月"+ now.getDate() + "日，" + day[now.getDay()] + "，" + hour+ ":" + min + ":" + sec + "</nobr>");
    $('#Timer').addClass('animated bounceInRight');
	
}
function logout() {
    var ru = '';
    $.ajax({
        url : '/login/logout/',
        type : 'post',
        success : function() {
          location.href = ""+ru;
        }
    });
}
</script>