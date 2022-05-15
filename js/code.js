var loader = document.querySelector('.loader');
var loadlogopath = document.querySelectorAll('.loadlogo')[0];
var loadlogo = document.querySelector('.loader svg');
var i = 0;
window.onload = function() {
	if (getCookie('theme') == "dark") { themeToggle('dark'); } else {
	if (getCookie('theme') == "light") { themeToggle('light'); } else { themeToggle('auto'); }}

    loadlogo.style.animationIterationCount = "1";
    if (getCookie('first') != "false") {
        setTimeout(sayHello, 2000);    
        document.cookie = "first=false; secure"
    } else {document.getElementById('hoverbox').style.display = "none";}

    
}

function sayHello() {
    
    loadlogopath.setAttribute('style', 'fill: #10aaaa');
    loadlogo.setAttribute('style', '')
    loader.style.height = "200px";
    setTimeout(writeHello, 1000);
    setTimeout(hideLoader, 2500);
    
}

function writeHello() {
    var txt = 'привіт';
    if (i < txt.length) {
        document.getElementById("hellotext").innerHTML += txt.charAt(i);
        i++;
        setTimeout(writeHello, 150);
    }
}

function hideLoader() {
    loader.style.opacity = '0.0';
    setTimeout(function(){document.getElementById('hoverbox').setAttribute('style', 'background-color: var(--accent); left: 50%; width: 0px; height: 0px; top:50%; transform: rotate(180deg); border-radius:100%; opacity: 0.5');}, 500);
    
}




isSidanavOpen = false;
var prevScrollpos = window.pageYOffset;
var nav = document.getElementById("nav-bg");
var navColorSpace = document.getElementById('nav-color-space');
window.onscroll = function() {
	if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {
		nav.classList.add("shadow");    
	} else {
	    nav.classList.remove("shadow");
	}
    try {
        var introHeight = document.getElementById('intro').clientHeight;
        if (document.body.scrollTop > introHeight || document.documentElement.scrollTop > introHeight) {
            navColorSpace.classList.remove('darknav');
        } else {
            navColorSpace.classList.add('darknav');
        }
    } catch (e) {}
    // if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
	//     var currentScrollPos = window.pageYOffset;
	//     if (prevScrollpos > currentScrollPos) {
	//         nav.style.top = "0";
	//     } else {
	//         nav.style.top = "-60px";
	//     }
	//     prevScrollpos = currentScrollPos;
    // }
	
}


var drop = document.getElementsByClassName("dropbox");
var navlinkicon = document.querySelectorAll(".hover-drop .menulink i");
function dropboxShow(num) { drop[num].style.display = "block"; navlinkicon[num].setAttribute('style', 'transform: rotate(180deg);')}
function dropboxHide(num) { drop[num].style.display = "none"; navlinkicon[num].setAttribute('style', '')}

function topFunction() {
	document.body.scrollTop = "0px";
	document.documentElement.scrollTop = "0px";
}


var themebtn = document.getElementById('theme-btn');
function themeToggle(theme) {

	document.body.classList = theme;
	document.cookie = "theme=" + theme + "; secure"

    if (theme == 'light') {
        themebtn.setAttribute('onclick', "themeToggle('dark')");
        themebtn.classList = "fa-solid fa-circle";
        themebtn.title = "Світла";
    } else {
        if (theme == 'dark') {
            themebtn.setAttribute('onclick', "themeToggle('auto')");
            themebtn.classList = "fa-solid fa-moon";
            themebtn.title = "Темна";
        } else {
            themebtn.setAttribute('onclick', "themeToggle('light')");
            themebtn.classList = "fa-solid fa-circle-dot";
            themebtn.title = "Авто";
        }
    }
}



function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

var article = document.querySelector("#article");
var dotCont = document.querySelector("#dot-cont");
var pages = document.getElementsByClassName("article-page");

var curPage = 0;

for (let i = 0; i < pages.length; i++) {
    var dot = document.createElement('i');
    dot.classList = "fa-solid fa-circle dot";
    let goto = 'goToPage(' + i + ')';
    dot.setAttribute('onclick', goto); 
    dotCont.appendChild(dot);
}

var dots = document.getElementsByClassName("dot");

function goToPage(pagenum) {
    try {
        for (let i = 0; i < pages.length; i++) {
            let cdot = dots[i];
            let page = pages[i];
            if (i < pagenum) {page.classList.add('watched');}
            if (i > pagenum) {page.classList.add('unwatched');}
            if (i == pagenum) {cdot.classList = "fa-solid fa-circle dot";};
            if (i != pagenum) {cdot.classList = "fa-solid fa-circle-dot dot";};
        }
        pages[pagenum].classList.add('current');
    } catch (e) {
        console.log(e);
    }
}
goToPage(0);
var searchContCond = document.getElementById('search-cont-cond');
function searchCondFocus() { 
    searchContCond.style.border = '3px solid var(--accent)'; 
    searchContCond.style.padding = '4px';
}
function searchCondBlur() { 
    searchContCond.style.border = '1px solid transparent';
    searchContCond.style.padding = '6px'; 
}
var searchInput = document.querySelector('#search-cont-cond input');
function searchClear() {
    searchInput.value = "";
}