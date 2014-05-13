var idx = 0;
var nodes = [];
var edges = [];
var options = {
        edges: {
          length: 50
        },
        stabilize: false,
        dataManipulation: true,
        onAdd: function(data,callback) {
          var span = document.getElementById('operation');
          var idInput = document.getElementById('node-id');
          var labelInput = document.getElementById('node-label');
          var saveButton = document.getElementById('saveButton');
          var cancelButton = document.getElementById('cancelButton');
          var div = document.getElementById('graph-popUp');
          span.innerHTML = "Add Node";
          idInput.value = data.id;
          labelInput.value = data.label;
          saveButton.onclick = saveData.bind(this,data,callback);
          cancelButton.onclick = clearPopUp.bind();
          div.style.display = 'block';
        },
        onEdit: function(data,callback) {
          var span = document.getElementById('operation');
          var idInput = document.getElementById('node-id');
          var labelInput = document.getElementById('node-label');
          var saveButton = document.getElementById('saveButton');
          var cancelButton = document.getElementById('cancelButton');
          var div = document.getElementById('graph-popUp');
          span.innerHTML = "Edit Node";
          idInput.value = data.id;
          labelInput.value = data.label;
          saveButton.onclick = saveData.bind(this,data,callback);
          cancelButton.onclick = clearPopUp.bind();
          div.style.display = 'block';
        },
        onConnect: function(data,callback) {
          if (data.from == data.to) {
            var r=confirm("Do you want to connect the node to itself?");
            if (r==true) {
              callback(data);
            }
          }
          else {
            callback(data);
          }
        }
      };
/* function
 * @object obj
 * @string entity
 * @string option['color']
 */
function addNodes(obj, entity, option) {
	list = obj[entity];
	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var aId = item[entity]["ID"];
		var aName = item[entity]["name"];
		var lId = item["Link"]["ID"];
		var lLFrom = item["Link"]["LFrom"];
		var lLTo = item["Link"]["LTo"];
		var tName = item["taglink"]["name"];
		//nodes.push({ id: <?php echo $id; ?>, label: "管理者" });

		var isExists = false;
		for (var j = 0; j < nodes.length; j++) {
			if (nodes[j]["id"] == aId) isExists = true;
		}
		if (!isExists) {
			//alert('aId=' + aId + ' lId=' + lId + ' aName=' + aName + ' lLFrom=' + lLFrom + ' lLTo=' + lLTo);
			nodes.push({ id: aId, label: aName, color:option });
		}

		var isFrom = false;
		for (var j = 0; j < nodes.length; j++) {
			if (nodes[j]["id"] == lLFrom) isFrom = true;
		}
		if (!isFrom) {
			nodes.push({ id: lLFrom, label:tName, color:option });
		}

		var isTo = false;
		for (var j = 0; j < nodes.length; j++) {
			if (nodes[j]["id"] == lLTo) isTo = true;
		}
		if (!isTo) {
			nodes.push({ id: lLTo, label:tName, color:option });
		}

		var isEdge = false;
		for (var j = 0; j < edges.length; j++) {
			if (edges[j]["id"] == lId) isEdge = true;
		}
		if (!isEdge) {
			edges.push({ id: lId, from: lLFrom, to: lLTo, label: tName, style: 'line', length: Math.random()*200+40 });

		}
		//edges.push({ from: lLFrom, to: lLTo });
	}
}
function getInfo(id){
	$.getJSON('/cakephp/tagusers/map?id='+ id,
		null,//{ id: <?php echo $id; ?> },
		function(obj) {
			if(obj !== null) {
				nodes = [];
				edges = [];
				addNodes(obj, "Article");
				addNodes(obj, "Tag", "#FF6666");

				var container = document.getElementById('mygraph');
				var data = {
					nodes: nodes,
					edges: edges
				};

				//select eventlistner from sample code 07 selection
				//cklick で　jsonを取得
				//graph.on('select',function(){checkGet(properties)}
				graph.on('select', function(properties) {
	    			document.getElementById('info').innerHTML += 'selection: ' + JSON.stringify(properties['nodes'][0]) + '<br>';
	    			getInfo(JSON.stringify(properties['nodes'][0]))

					});

				var options = {
    			        edges: {
    			          length: 50
    			        },
    			        stabilize: false,
    			        dataManipulation: true,
    			        onAdd: function(data,callback) {
    			          var span = document.getElementById('operation');
    			          var idInput = document.getElementById('node-id');
    			          var labelInput = document.getElementById('node-label');
    			          var saveButton = document.getElementById('saveButton');
    			          var cancelButton = document.getElementById('cancelButton');
    			          var div = document.getElementById('graph-popUp');
    			          span.innerHTML = "Add Node";
    			          idInput.value = data.id;
    			          labelInput.value = data.label;

    			          div.style.display = 'block';
    			        }
    			      };
				graph = new vis.Graph(container, data, options);
				idx++;
			}
		}
	);
}
graph.on("resize", function(params) {console.log(params.width,params.height)});

      function clearPopUp() {
        var saveButton = document.getElementById('saveButton');
        var cancelButton = document.getElementById('cancelButton');
        saveButton.onclick = null;
        cancelButton.onclick = null;
        var div = document.getElementById('graph-popUp');
        div.style.display = 'none';

      }

      function saveData(data,callback) {
        var idInput = document.getElementById('node-id');
        var labelInput = document.getElementById('node-label');
        var div = document.getElementById('graph-popUp');
        data.id = idInput.value;
        data.label = labelInput.value;
        callback(data);
      }
function checkGet(properties) {
    				document.getElementById('info').innerHTML += 'selection: ' + JSON.stringify(properties) + '<br>';
    				getInfo(JSON.stringify(properties)['nodes']); //二回目取ってくる専用の
  		}

$(document).ready(function(){
getInfo(<?php echo $id; ?>);
	$('input:button').click(getInfo(<?php echo $id; ?>)
			);
}

		);