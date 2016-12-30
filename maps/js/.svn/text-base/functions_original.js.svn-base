/*
Empaquetador JS

http://dean.edwards.name/packer/
*/

var gmap;
var Layouts = [];
var ArrayLayouts = [];
var ArrayLayoutsData = [];
var infowindow = new google.maps.InfoWindow();

function initialize() {
	var latlng = new google.maps.LatLng(LatitInicial, LongInicial);
	var myOptions = {
						zoom: ZoomInicial,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
	gmap = new google.maps.Map(document.getElementById("map"), myOptions);
	getData();
}

function getData(){
	$.ajax({url:'js/data.php',
            type:'post',
			success:  function(response) {
				Layouts = jQuery.parseJSON(response);
				setLayouts();
			}
    });

}

function setLayouts(){

	for (var i = 0; i < Layouts.length; i++) {
		setLayout(Layouts[i], i);
	}


}

function setLayout(layout, layoutIndex){

	for (var i = 0; i < layout.length; i++) {

		if(layout[i][0]=='M'){
			setMarker(layout[i], layoutIndex);
		}

		if(layout[i][0]=='P'){
			setPolygon(layout[i], layoutIndex);
		}

		if(layout[i][0]=='PL'){
			setPolyline(layout[i], layoutIndex);
		}

		if(layout[i][0]=='KMZ'){
			setKmz(layout[i],layoutIndex);
		}

		if(layout[i][0]=='G'){
			setGroundOverlay(layout[i], layoutIndex);
		}

	}

}

function setGroundOverlay(obj,layoutIndex){
	var imageBounds = new google.maps.LatLngBounds(
								    new google.maps.LatLng(obj[2][0],obj[2][1]),
								    new google.maps.LatLng(obj[2][2],obj[2][3]));
	var groundOverlay = new google.maps.GroundOverlay(obj[4][0],imageBounds);

	groundOverlay.setOpacity(parseFloat(obj[4][1]));
	groundOverlay.setMap(gmap);

	if(obj[5]==1){
		groundOverlay.setMap(gmap);
	}
	else{
		groundOverlay.setMap(null);
	}

	if(!ArrayLayouts[layoutIndex]){
		ArrayLayouts.push([]);
		ArrayLayoutsData.push([]);
	}

	ArrayLayouts[layoutIndex].push(groundOverlay);
	ArrayLayoutsData[layoutIndex].push();
}

function setKmz(obj,layoutIndex){
	var kmzLayer = new google.maps.KmlLayer(obj[4][0]);

	if(obj[5]==1){
		kmzLayer.setMap(gmap);
	}
	else{
		kmzLayer.setMap(null);
	}

}

function setMarker(obj,layoutIndex){
	var pinColor = obj[4][0];
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));

    var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
        new google.maps.Size(40, 37),
        new google.maps.Point(0, 0),
        new google.maps.Point(12, 35));

	var myLatLng = new google.maps.LatLng(obj[2][0], obj[2][1]);
	var marker = new google.maps.Marker({
					position: myLatLng,
					icon: pinImage,
					title: obj[1],
					shadow: pinShadow
				 });

	if(obj[5]==1){
		marker.setMap(gmap);
	}
	else{
		marker.setMap(null);
	}

	setInfoWindow(marker,obj[1],obj[3]);

	if(!ArrayLayouts[layoutIndex]){
		ArrayLayouts.push([]);
		ArrayLayoutsData.push([]);
	}

	ArrayLayouts[layoutIndex].push(marker);
	ArrayLayoutsData[layoutIndex].push(obj[7]);
	ArrayLayoutsData[layoutIndex].push(obj[8]);
	ArrayLayoutsData[layoutIndex].push(obj[6]);
}

function setPolygon(obj,layoutIndex){
	var points = [];
	var myLatLng;

	for(var i=0; i<obj[2].length/2; i++){
		myLatLng = new google.maps.LatLng(obj[2][i*2],obj[2][(i*2)+1]);
		points.push(myLatLng);
	}

	var polygon = new google.maps.Polygon({
											paths: points,
											strokeWeight: 0,
											fillColor: obj[4][0]
										});

	if(obj[5]==1){
		polygon.setMap(gmap);
	}
	else{
		polygon.setMap(null);
	}

	setInfoWindow(polygon,obj[1],obj[3]);

	if(!ArrayLayouts[layoutIndex]){
		ArrayLayouts.push([]);
	}

	ArrayLayouts[layoutIndex].push(polygon);
}

function setPolyline(obj,layoutIndex){
	var points = [];
	var myLatLng;

	for(var i=0; i<obj[2].length/2; i++){
		myLatLng = new google.maps.LatLng(obj[2][i*2],obj[2][(i*2)+1]);
		points.push(myLatLng);
	}

	var polyline = new google.maps.Polyline({
											path: points,
											strokeWeight: obj[4][1],
											strokeColor: obj[4][0]
										});

	if(obj[5]==1){
		polyline.setMap(gmap);
	}
	else{
		polyline.setMap(null);
	}

	setInfoWindow(polyline,obj[1],obj[3]);

	if(!ArrayLayouts[layoutIndex]){
		ArrayLayouts.push([]);
	}

	ArrayLayouts[layoutIndex].push(polyline);
}

function setInfoWindow(object,title,body){
	var vcontent = '<div class="InfoWindow">'+'<h1>'+title+'</h1>';

	if(body!=''){
		vcontent = vcontent + '<div>'+body+'</div>'
	}

	vcontent = vcontent + '</div>';

	/*object.infowindow = new google.maps.InfoWindow();
	object.infowindow.content = vcontent;

	google.maps.event.addListener(object, 'click', function(event) {
	  object.infowindow.position = event.latLng;
	  object.infowindow.open(gmap);
	});*/

	/*var infowindow = new google.maps.InfoWindow({
	   content: vcontent,
	   zIndex: 1
	});*/

	google.maps.event.addListener(object, 'click', function() {
	   var infowindow = new google.maps.InfoWindow({
		   content: vcontent,
		   zIndex: 1
		});

	   infowindow.content = vcontent;
	   infowindow.open(gmap,object);
	});

}

function hideLayout(layoutIndex){

	if (ArrayLayouts[layoutIndex]) {

		for (i in ArrayLayouts[layoutIndex]) {
			ArrayLayouts[layoutIndex][i].setMap(null);
		}

	}

}

function showLayout(layoutIndex){

	if (ArrayLayouts[layoutIndex]) {

		for (i in ArrayLayouts[layoutIndex]) {
			ArrayLayouts[layoutIndex][i].setMap(gmap);
		}

	}

}

function HideShowLayout(checkbox, layoutIndex){

	if(checkbox.checked){
		showLayout(layoutIndex);
	}
	else{
		hideLayout(layoutIndex);
	}

}

function RefreshData(){
	var i = 4;
	var estado;
	var zona;
	var organismo;

	zonanorte = document.getElementById("zonanorte");
	zonacentro = document.getElementById("zonacentro");
	zonasur = document.getElementById("zonasur");
	zonaconfluencia = document.getElementById("zonaconfluencia");

	while(i<ArrayLayoutsData.length){

		/*if((zonanorte.checked && ArrayLayoutsData[i][2].indexOf('N')>=0) || (zonacentro.checked && ArrayLayoutsData[i][2].indexOf('C')>=0) || (zonasur.checked && ArrayLayoutsData[i][2].indexOf('S')>=0) || (zonaconfluencia.checked && ArrayLayoutsData[i][2].indexOf('F')>=0)){*/
			organismo = document.getElementById("organismo_" + ArrayLayoutsData[i][0]);

			if(!organismo.checked){
				hideLayout(i);
			}
			else{
				estado = document.getElementById("estado_" + ArrayLayoutsData[i][1]);

				if(!estado.checked){
					hideLayout(i);
				}
				else{
					showLayout(i);
				}

			}

		/*}
		else{
			hideLayout(i);
		}*/

		i++;
	}

}