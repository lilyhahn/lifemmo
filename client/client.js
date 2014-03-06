var canvas = null;
var scale = 10;
$(document).ready(function(){
	var data = null;
	canvas = new fabric.Canvas('maincanvas');
	//console.log("test");
	canvas.on('mouse:down', function(e){
		console.log(e.target.top);
		//if(data == null)
		//	return;
	});
	canvas.add(cell);
});

function update(){
	$.ajax({
		type: 'GET',
	 	//url: 'http://192.168.1.201:28017/lifemmo/cells/',
	 	url: 'http://10.85.207.212:28017/lifemmo/cells/',
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
	canvas.clear();
	$.each(data.rows, function(i, item){
		if(item.state == 1){
			console.log("drawing!");
			var cell = new fabric.Rect({
				left: item.x * scale,
				top: item.y * scale,
				fill: 'red',
				width: scale,
				height: scale
			});
			cell.set('selectable', false);
			canvas.add(cell);
		}
	});
}

setInterval(update, 500);

/*setTimeout(function(){
	console.log(data);
}, 5000);*/