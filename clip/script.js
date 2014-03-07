var app;
var iframe;
var overlay;
var input;
var cont;
var loading;
var bar;

set = function () {
	app = new DeskApp();
	iframe = document.getElementsByTagName("iframe")[0];
	overlay = document.getElementById("overlay");
	input = document.getElementsByTagName("input")[0];
	cont = document.getElementById("cont");
	loading = document.getElementsByClassName("loading")[0];
	bar = document.getElementById("bar");
}

window.onload = function() {
	set();
	app.get("url", function(data) {
		if (data != null) {
			toggle();
			loading.style.display = "none";
		}
		var url = data.replace("http://holalabs.com/getUrl.php?url=", "");
		input.value = url;
		load(url);
	});
	cont.onscroll = function() {
		app.set("y", cont.scrollTop);
		app.set("x", cont.scrollLeft);
	}
}

resize = function() {
	if (typeof app === 'undefined')
		return;
	console.log(bar.style.display);
	if (bar.style.display != "")
		cont.style.overflow = "auto";
	var h = iframe.contentWindow.document.body.scrollHeight;
	iframe.style.height = h + "px";
	overlay.style.height = h + "px";
	app.get("y", function(data) {
		cont.scrollTop = data;
	});
	app.get("x", function(data) {
		cont.scrollLeft = data;
	});
	loading.style.display = "none";
}

load = function() {
	var url = "http://holalabs.com/getUrl.php?url=" + document.getElementsByTagName("input")[0].value;
	loading.style.display = "block";
	cont.scrollTop = 0;
	cont.scrollLeft = 0;
	cont.style.overflow = "hidden";
	iframe.src = url;
	app.set("url", url);
}

toggle = function() {
	var b = document.getElementById("b");
	if (bar.style.display == "none" || bar.style.display == "") {
	  bar.style.display = "block";
	  cont.style.overflow = "auto";
	  b.innerHTML = "-";
	} else {
	  bar.style.display = "none";
	  cont.style.overflow = "hidden";
	  b.innerHTML = "+";
	}
}

/*
m = function(d, p) {
	var iframe = document.getElementsByTagName("iframe")[0];
	var overlay = document.getElementById("overlay");
	var i = parseFloat(iframe.style[d].replace("px", ""));
	iframe.style[d] = (parseFloat(iframe.style[d].replace("px", "")) + p) + "px";
	overlay.style[d] = (parseFloat(overlay.style[d].replace("px", "")) + p) + "px";
}

mLeft = function() {
	m("left", 10);
}

mRight = function() {
	m("left", -10);
}

mTop = function() {
	m("top", 10);
}

mBottom = function() {
	m("top", -10);
}*/