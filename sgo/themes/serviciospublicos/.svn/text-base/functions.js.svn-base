function OpenWindow(url, width, height, name){
	var options = "location=1, status=1, width=" + width + ", height=" + height;
	window.open(url, name, options);
}

function Imprimir(file,name){
	window.open(file);
}

function imposeMaxLength(Event, Object, MaxLen)
{
    return (Object.value.length <= MaxLen)||(Event.keyCode == 8 ||Event.keyCode==46||(Event.keyCode>=35&&Event.keyCode<=40))
}

function ShowHide(div, show){

	if(show){
		//$(div).appear();
		$(div).style.display='';
	}
	else{
		//$(div).fade();
		$(div).style.display='none'
	}
	
}

function OpenParent(url){
	window.opener.location.href = url;
	window.close();
}

function CloseWindow(){
	window.close();
}

function RefreshParent(button){
	var btn = window.opener.$(button);
	btn.click();
	window.close();
}