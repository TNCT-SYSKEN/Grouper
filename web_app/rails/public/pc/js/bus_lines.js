(function(){
	$(function(){
		var path  = String(location.pathname);
		var query = (String(location.search)).split("-");
		$.ajax("../bus_lines.json", {
			type: "get",
			cache: false,
			dataType: "json",
		})
		.done(function(json){
			$.each(json.bus_lines, function(index, val){
				$("#line_list").append("<tr onclick=\"document.location = './timeselect.html?"+(index+1)+"'\"><td>"+val.from+"</td><td>"+val.to+"</td></tr>");
				if ( query[0].indexOf(index+1) != -1 ) {
					$("#from").text(val.from);
					$("#to").text(val.to);
				}
			});
			$.each(json.times, function(index, val){
				$("#time_list").append("<tr onclick=\"document.location = './results.html"+query[0]+"-"+(index+1)+"'\"><td>"+val.start+"</td><td>"+val.end+"</td></tr>");
				// resultのときだけ
				if ( path.indexOf("results.html") != -1 ) {
					if ( query[1].indexOf(index+1) != -1 ) {
						$("#start").text(val.start);
						$("#end").text(val.end);
					}
				}
			});
		})
		.fail(function(data, status, errorThrow){
			console.log("!?"+status);
		});

	});

	function getUnixtime(times) {
		return parseInt((times)/1000);
	}
})();
