//Получаем объекты

//Плеер
var videoContainer = document.getElementById('videocontainer');
var videoControls = document.getElementById('video');
var videoPlayer = document.getElementById('player');

//Время
var progressBar = document.getElementById('progressbar');
var currTime = document.getElementById('currtime');
var durationTime = document.getElementById('duration');

//Кнопки
var actionButton = document.getElementById('action');
var muteButton = document.getElementById('mute');
var volumeScale = document.getElementById('volume');
var speedSelect = document.getElementById('speed');


function videoAct() {
	if(videoPlayer.paused) {
		videoPlayer.play();
		actionButton.setAttribute('class','action play');
	} else {
		videoPlayer.pause();
		actionButton.setAttribute('class','action pause');
	}
		if(durationTime.innerHTML == '00:00') {
		durationTime.innerHTML = videoTime(videoPlayer.duration); 
	}
}

//Запуск, пауза
if (actionButton) {actionButton.addEventListener('click',videoAct);}
if (videoPlayer) {videoPlayer.addEventListener('click',videoAct);}

function videoTime(time) { //Рассчитываем время в секундах и минутах
	time = Math.floor(time);
	var minutes = Math.floor(time / 60);
	var seconds = Math.floor(time - minutes * 60);
	var minutesVal = minutes;
	var secondsVal = seconds;
	if(minutes < 10) {
		minutesVal = '0' + minutes;
	}
	if(seconds < 10) {
		secondsVal = '0' + seconds;
	}
	return minutesVal + ':' + secondsVal;
}

function videoProgress() { //Отображаем время воспроизведения
	progress = (Math.floor(videoPlayer.currentTime) / (Math.floor(videoPlayer.duration) / 100));
	progressBar.value = progress;
	currTime.innerHTML = videoTime(videoPlayer.currentTime);
}

function videoChangeTime(e) { //Перематываем
	var mouseX = Math.floor(e.pageX - progressBar.offsetLeft);
	var progress = mouseX / (progressBar.offsetWidth / 100);
	videoPlayer.currentTime = videoPlayer.duration * (progress / 100);
}

//Отображение времени
if (videoPlayer) {videoPlayer.addEventListener('timeupdate',videoProgress);}

//Перемотка
if (progressBar) {progressBar.addEventListener('click',videoChangeTime);}

function videoChangeVolume() { //Меняем громкость
	var volume = volumeScale.value / 100;
	videoPlayer.volume = volume;
	if(videoPlayer.volume == 0) {
		muteButton.setAttribute('class','mute mutetrue');
	} else {
		muteButton.setAttribute('class','mute mutefalse');
	}
}

function videoMute() { //Убираем звук
	if(videoPlayer.volume == 0) {
		videoPlayer.volume = volumeScale.value / 100;
		muteButton.setAttribute('class','mute mutefalse');
	} else {
		videoPlayer.volume = 0;
		muteButton.setAttribute('class','mute mutetrue');
	}
}

//Звук
if (muteButton) {muteButton.addEventListener('click',videoMute);}
if (volumeScale) {volumeScale.addEventListener('change',videoChangeVolume);}
