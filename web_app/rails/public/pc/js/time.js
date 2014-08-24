(function(){
	$(function(){
		var now = getUnixtime(new Date);
		var a = new Date;
		var query = (String(location.search)).split("-");
		$.ajax("../bus_lines.json", {
			type: "get",
			cache: false,
			dataType: "json",
		})
		.done(function(json){
			$.each(json.times, function(index, val){
				if ( query[1].indexOf(index+1) != -1 ) {
					$("#start").text(val.start);
					$("#end").text(val.end);
					var time = getUnixtime(Date.parse(a.getFullYear() + "/" + (a.getMonth()+1) + "/" + a.getDate() + " " + val.start ));
					var random = ( parseInt(Math.random()*30) - 15 ) * 60;
					time = time + random;
					var limit = parseInt((time - now)/60);

					if ( random > 5 * 60 ) {
						$("#status").css({"color": "#880000"}).text("["+parseInt(random/60)+"分の遅延]");
					}
					else if ( random < -5 * 60 ) {
						$("#status").css({"color": "#1771CA"}).text("["+(parseInt(random/60)*-1)+"分の早着]");
					}

					// 残り時間が60分以上であれば○時間と表示
					if ( limit >= 60 ) {
						limit = parseInt(limit/60);
						$("#limit").text("到着：約"+limit+"時間後");
					}
					// マイナスだったら通過
					else if ( limit < 0 ) {
						$("#limit").text("このバスは通過しました");
						$("#status").css({"display": "none"});
					}
					// 残り時間が60分未満であれば○分と表示
					else {
						$("#limit").text("到着：約"+limit+"分後");
					}
				}
			});
		});

	});

	function getUnixtime(times) {
		return parseInt((times)/1000);
	}
})();
