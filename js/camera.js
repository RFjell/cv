'use strict';

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

if( getBrowser() == "Chrome" ) {
	var constraints = {"audio": true, "video": { "mandatory": { "minWidth": 640, "maxWidth": 640, "minHeight": 480,"maxHeight": 480 }, "optional": [] } };//Chrome
} else if( getBrowser() == "Firefox" ) {
	var constraints = {"audio": true, "video": { width: { min: 640, ideal: 640, max: 640 }, height: { min: 480, ideal: 480, max: 480 }}}; //Firefox
}

var videoContainer = document.getElementById('video-container');
var videoElement;
var timer;

var mediaRecorder;
var chunks = [];
var blob;

function init() {
	removeChildren( videoContainer );
	videoElement = document.createElement('video');
	timer = document.createElement('p');
	timer.id = 'timer';

	videoContainer.appendChild(videoElement);

	videoElement.controls = false;
}

function errorCallback(error) {
	console.log('navigator.getUserMedia error: ', error);	
	document.getElementById('upload-message').textContent = "Error";
}

function startRecording(stream) {
	if( typeof MediaRecorder.isTypeSupported == 'function' ) {
//		if( MediaRecorder.isTypeSupported('video/webm;codecs=h264') ) {
//			var options = { mimeType: 'video/webm;codecs=h264' };
//		} else if( MediaRecorder.isTypeSupported('video/webm;codecs=vp9') ) {
//			var options = { mimeType: 'video/webm;codecs=vp9' };
//		} else if( MediaRecorder.isTypeSupported('video/webm;codecs=vp8') ) {
//			var options = { mimeType: 'video/webm;codecs=vp8' };
//		}
		var options = { mimeType: 'video/webm;codecs=vp8' };
		mediaRecorder = new MediaRecorder( stream, options );
	} else {
		mediaRecorder = new MediaRecorder( stream );
	}

	mediaRecorder.start(10);
	videoContainer.appendChild(timer);

	var url = window.URL || window.webkitURL;
	videoElement.src = url ? url.createObjectURL(stream) : stream;
	videoElement.play();
	var startTime = Date.now();

	mediaRecorder.ondataavailable = function(e) {
		chunks.push(e.data);
		var t = (Date.now()-startTime)/1000;
		if( t > 60 ) {
			document.getElementById('stopBtn').click();
		} else {
			timer.textContent = Math.floor(60 - t);
			if(60 - t < 10)
				timer.style.color='red';
		}
	};

	mediaRecorder.onerror = function(e) {
		console.log('Error: ', e);
		document.getElementById('upload-message').textContent = "Error";
	};

	mediaRecorder.onstop = function() {
		blob = new Blob(chunks, {type: "video/webm"});
		chunks = [];
		//init();
		timer.parentElement.removeChild(timer);

		var videoURL = window.URL.createObjectURL(blob);
		videoElement.src = videoURL;
		videoElement.controls = true;

		document.getElementById('upload-message').textContent = "Video recorded. Don't forget to press upload.";

		// Workaround for firefox bug
		videoElement.onended = function() {
			videoElement.pause();
			videoElement.src = videoURL;
		}
	};

	mediaRecorder.onwarning = function(e){
		console.log('Warning: ' + e);
	};
}

function onBtnRecordClicked () {
	if( typeof MediaRecorder === 'undefined' || !navigator.getUserMedia ) {
		alert('MediaRecorder not supported on your browser, use Firefox or Chrome instead.');
	} else {
		init();
		navigator.getUserMedia( constraints, startRecording, errorCallback );

		document.getElementById('recordBtn').disabled = true;
		document.getElementById('stopBtn').disabled = false;
		document.getElementById('uploadBtn').disabled = true;

		videoElement.setAttribute('width',640);
		videoElement.setAttribute('height',480);
	}
}

function onBtnStopClicked() {
	mediaRecorder.stop();

	document.getElementById('recordBtn').disabled = false;
	document.getElementById('stopBtn').disabled = true;
	document.getElementById('uploadBtn').disabled = false;
}

//browser ID
function getBrowser() {
	var nVer = navigator.appVersion;
	var nAgt = navigator.userAgent;
	var browserName = navigator.appName;
	var fullVersion = ''+parseFloat(navigator.appVersion);
	var majorVersion = parseInt(navigator.appVersion,10);
	var nameOffset,verOffset,ix;

	// In Opera, the true version is after "Opera" or after "Version"
	if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
		browserName = "Opera";
		fullVersion = nAgt.substring(verOffset+6);
		if ((verOffset=nAgt.indexOf("Version"))!=-1)
			fullVersion = nAgt.substring(verOffset+8);
	}
	// In MSIE, the true version is after "MSIE" in userAgent
	else if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
		browserName = "Microsoft Internet Explorer";
		fullVersion = nAgt.substring(verOffset+5);
	}
	// In Chrome, the true version is after "Chrome"
	else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
		browserName = "Chrome";
		fullVersion = nAgt.substring(verOffset+7);
	}
	// In Safari, the true version is after "Safari" or after "Version"
	else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
		browserName = "Safari";
		fullVersion = nAgt.substring(verOffset+7);
		if ((verOffset=nAgt.indexOf("Version"))!=-1)
			fullVersion = nAgt.substring(verOffset+8);
	}
	// In Firefox, the true version is after "Firefox"
	else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
		browserName = "Firefox";
		fullVersion = nAgt.substring(verOffset+8);
	}
	// In most other browsers, "name/version" is at the end of userAgent
	else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) <
		(verOffset=nAgt.lastIndexOf('/')) )
	{
		browserName = nAgt.substring(nameOffset,verOffset);
		fullVersion = nAgt.substring(verOffset+1);
		if (browserName.toLowerCase()==browserName.toUpperCase()) {
			browserName = navigator.appName;
		}
	}
	// trim the fullVersion string at semicolon/space if present
	if ((ix=fullVersion.indexOf(";"))!=-1)
		fullVersion=fullVersion.substring(0,ix);
	if ((ix=fullVersion.indexOf(" "))!=-1)
		fullVersion=fullVersion.substring(0,ix);

	majorVersion = parseInt(''+fullVersion,10);
	if (isNaN(majorVersion)) {
		fullVersion = ''+parseFloat(navigator.appVersion);
		majorVersion = parseInt(navigator.appVersion,10);
	}

	return browserName;
}

function upload() {
	var fileName = 	'video-file';

	// Setup video close button
	var p = document.createElement("p");
	p.id = "video-close-button";
	p.textContent = "X";
	p.addEventListener('click', function(){
		removeChildren(videoContainer);
	});

	var videoLoader = document.getElementById('skill-loader');

	var formData = new FormData();
	formData.append('video-filename', fileName);
	formData.append('video-blob', blob);
	document.getElementById('upload-message').textContent = "Uploading...";
	videoLoader.className = 'loader';

	xhr('user/upload-video.php', formData, function (srvRes) {
		document.getElementById('upload-message').textContent = "Upload completed!";
		videoLoader.className = '';
		videoContainer.appendChild(p);
	});

}
