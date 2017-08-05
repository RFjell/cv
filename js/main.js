'use strict';

function xhr(url, data, callback) {
	var request = new XMLHttpRequest();
	request.onreadystatechange = function () {
		// The request is complete and successful
		if (request.readyState == 4 && request.status == 200) { 
			callback(request.responseText);
		} else if( request.readyState == 4 && request.status == 401 ) {
			document.getElementById('upload-message').textContent = 'An error occured. Not logged in.';
			document.getElementById('skill-loader').className = '';
		} else if( request.readyState == 4 && request.status == 500 ) {
			document.getElementById('upload-message').textContent = 'An error occured. '+ request.responseText;
			document.getElementById('skill-loader').className = '';
		} else if( request.readyState == 4 && request.status == 403 ) {
			document.getElementById('upload-message').textContent = 'An error occured. You are not logged in as admin.';
			document.getElementById('loader').className = '';
		}
	};
	request.open('POST', url);
	request.send(data);
}

function fetchVideo(username) {
	var v = document.getElementById('video-container');
	var btn = document.getElementById('fetchVideoBtn');
	btn.disabled = true;
	document.getElementById('uploadBtn').disabled = true;

	// Remove video player if already added
	removeChildren( v );

	// Status message and loading animation
	var msg = document.getElementById('upload-message');
	var loader = document.getElementById('video-loader');

	// Setup video player
	var videoPlayer = document.createElement("video");
	videoPlayer.setAttribute('width',640);
	videoPlayer.setAttribute('height',480);
	videoPlayer.controls = true;

	// Setup video close button
	var p = document.createElement("p");
	p.id = "video-close-button";
	p.textContent = "X";
	p.addEventListener('click', function(){
		removeChildren(v);
	});

	var videoSource = document.createElement("source");
	videoSource.setAttribute('type', 'video/webm');

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
			videoSource.src = xmlHttp.responseText;
			videoPlayer.appendChild(videoSource);
			v.appendChild(videoPlayer);
			msg.textContent = '';
			loader.className = '';
			btn.disabled = false;
			v.appendChild(p);
		} else if( xmlHttp.readyState == 4 && xmlHttp.status == 500 ) {
			msg.textContent = 'No video available';
			loader.className = '';
			btn.disabled = false;
		} else if( xmlHttp.readyState == 4 && xmlHttp.status == 401 ) {
			msg.textContent = 'Not logged in';
			loader.className = '';
			btn.disabled = false;
		}
	}

	msg.textContent = 'Fetching video...';
	loader.className = 'loader';

	// Fetch video
	if(arguments.length>0)
		xmlHttp.open("GET", '../user/get-video.php?username='+username, true); // true for asynchronous
	else
		xmlHttp.open("GET", 'user/get-video.php', true);
	xmlHttp.send(null);
}

function removeChildren(parentElement) {
		while( parentElement.hasChildNodes() ) {
			parentElement.removeChild(parentElement.firstChild);
		}
}

function toggleHeaderMenu() {
	var x = document.getElementById('header');
	x.classList.toggle("open");
}

document.getElementById('container').onclick = function(e) {
	if(e.target != document.getElementById('contents')) {
		document.getElementById('header').classList.remove("open");
	}
}

function minimizeHeader() {
	window.addEventListener('scroll', function(e) {
		var y = window.pageYOffset || document.documentElement.scrollTop;
		if( y > 50 ) {
			let b = document.getElementById("logo");
			b.classList.remove('always-show');
			let c = document.querySelector("#header");
			c.classList.add('minimized');
		} else {
			let b = document.getElementById("logo");
			b.classList.add('always-show');
			let c = document.querySelector("#header");
			c.classList.remove('minimized');
		}
	});
}

window.onload = minimizeHeader;
