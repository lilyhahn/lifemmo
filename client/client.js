var canvas = null;
var scale = 30;
var cells = null;
var drowsyUrl = "http://localhost:9292";
$(document).ready(function(){
	update();
	var data = null;
	canvas = new fabric.Canvas('maincanvas');
	//console.log("test");
	canvas.on('mouse:down', function(e){
		console.log(e);
			if(e.target != undefined){
				$.each(cells, function(i, item){
					//console.log("target.top: " + e.target.top + " cell y: " + item.y);
					//console.log("target.left: " + e.target.left + " cell x: " + item.x);
					if(e.target.top == (item.y * scale) && e.target.left == (item.x * scale)){
						var patch = {"state": 0};
						$.ajax(drowsyUrl + '/lifemmo/cells/' + item._id.$oid, {
							type: 'patch',
							data: patch,
							success: function(item){
								//console.log(item);
								update();
							},
						});
						return;
					}
				});
			}
			else{
				$.each(cells, function(i, item){
					if((e.e.offsetX > item.x * scale && e.e.offsetX < item.x * scale + scale)  && (e.e.offsetY > item.y * scale && e.e.offsetY < item.y * scale + scale)){
					/*console.log("e.e.x: " + e.e.x);
					console.log("item.x: " + item.x * scale);
					console.log("e.e.y: " + e.e.y);
					console.log("item.y: " + item.y * scale);*/
					//if(item.x * scale - e.e.x < 10 && item.x * scale - e.e.x > 0 && item.y * scale - e.e.y < 10 && item.y * scale - e.e.y > 0){
						//console.log(drowsyUrl + '/lifemmo/cells/' + item._id.$oid);
						var patch = {"state": 1};
						$.ajax(drowsyUrl + '/lifemmo/cells/' + item._id.$oid, {
							type: 'patch',
							data: patch,
							success: function(item){
								//console.log(item);
								update();
							},
						});
						return;
					}
				});
			}
	});
});

function update(){
	var selector = {"state": {"$exists":true}};
	$.ajax({
		type: 'GET',
	 	//url: 'http://192.168.1.201:28017/lifemmo/cells/',
	 	url: drowsyUrl + '/lifemmo/cells/',
	 	//dataType: 'jsonp',
	 	data: selector,
	 	success: function(data){
	 		//console.log(data);
	 		cells = data;
	 		updateCells(data);
	 	},
		error: function(){
			console.log("error");
		},
		//jsonp: 'jsonp'
	});
}

function updateCells(data){
	canvas.clear();
	$.each(data, function(i, item){
		if(item.state == 1){
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

setInterval(update, 1000);