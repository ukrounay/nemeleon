// var intro = document.getElementById('intro');
// var docHeight = window.innerHeight;
// var docWidth = window.innerWidth;
// var absolute100 = docHeight + "px";
// var introimg = document.querySelector("#introimg img");
// introResize;
// window.onresize = introResize;
// function introResize() {
//     docHeight = window.innerHeight;
//     absolute100 = docHeight + "px";
//     if () {
        
//     } else {
        
//     }
//     if (introimg.clientHeight < docHeight) {
//         intro.style.height = absolute100;
//     } else {intro.style.height = 'auto';}
// }


var loader = document.querySelector('.loader');
var loadlogopath = document.querySelectorAll('.loadlogo')[0];
var loadlogo = document.querySelector('.loader svg');
var i = 0;
var navSpace = document.getElementById('nav-space');
window.onload = function() {
	if (getCookie('theme') == "dark") { themeToggle('dark'); } else {
	if (getCookie('theme') == "light") { themeToggle('light'); } else { themeToggle('auto'); }}
    loadlogo.style.animationIterationCount = "1";
    if (getCookie('first') != "false") {
        setTimeout(sayHello, 2000);    
        document.cookie = "first=false; secure"
    } else {
        document.getElementById('hoverbox').style.display = "none"; 
        try {
            navSpace.classList.add('darknav');
            scrollFunction;
        } catch (e) {console.log(e);}
    }   
}

function sayHello() {
    
    loadlogopath.setAttribute('style', 'fill: var(--accent)');
    loadlogo.setAttribute('style', '');
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
    setTimeout(function(){document.getElementById('hoverbox').setAttribute('style', 'background-color: var(--accent); left: 50%; width: 0px; height: 0px; top:50%; transform: rotate(180deg); border-radius:100%; opacity: 0.5'); navSpace.classList.add("darknav");}, 500);
}




isSidanavOpen = false;
var prevScrollpos = window.pageYOffset;
var navbg = document.getElementById("nav-bg");
var nav = document.getElementById("navigation-top");
window.onscroll = scrollFunction;
function scrollFunction() {
	if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {
		navbg.classList.add("shadow");    
	} else {
	    navbg.classList.remove("shadow");
	}
    try {
        //
        var introHeight = document.getElementById('intro').clientHeight;
        if (document.body.scrollTop > introHeight || document.documentElement.scrollTop > introHeight) {
            navSpace.classList.remove('darknav');
        } else {
            navSpace.classList.add('darknav');
        }
        //
        var themesHeight = introHeight + document.getElementById('themes').clientHeight;
        if (document.body.scrollTop > themesHeight || document.documentElement.scrollTop > themesHeight) {
            navSpace.classList.remove('bluenav');
        } else {
            navSpace.classList.add('bluenav');
        }
        //
    } catch (e) {}
}
// var currentScrollPos = window.pageYOffset;
// if (prevScrollpos > currentScrollPos) {
//     nav.style.top = "0";
//     navbg.style.top = "0";
// } else {
//     nav.style.top = "-60px";
//     navbg.style.top = "-60px";
// }
// prevScrollpos = currentScrollPos;

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
    searchContCond.style.borderColor = 'var(--accent)'; 
}
function searchCondBlur() { 
    searchContCond.style.borderColor = 'transparent';
}
var searchInput = document.querySelector('#search-cont-cond input');
function searchClear() {
    searchInput.value = "";
}


var hovernav = document.getElementById("hovernav");
var hovernavVisible = false;
var bar = document.getElementsByClassName("bar");
try {
    hovernav.setAttribute('onmouseover', barsWiggleOn);
    hovernav.setAttribute('onmouseleave', barsWiggleOff);
    hovernav.setAttribute('onclick', hoverNavToggle);
} catch (e) {console.log(e);}

function barsWiggleOn() {
    bar[0,1].width = '24px';
}