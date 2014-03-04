function update(){
	$.ajax({
		type: 'GET',
	 	url: 'http://192.168.1.201:28017/lifemmo/cells/',
	 	dataType: 'jsonp',
	 	success: function(data){
	 		updateCells(data);
	 	},
		error: function(){
			console.log("error");
		},
		jsonp: 'jsonp'
	});
}

function updateCells(data){
	var c = document.getElementById("maincanvas");
	var canvas = c.getContext("2d")
	$.each(data.rows, function(i, item){
		if(item.state == 1){
			canvas.fillStyle = "#FF0000";
			canvas.fillRect(item.x * 10, item.y * 10, 10, 10);
		}
	});
}

setInterval(update, 500);