var themebtn = document.getElementById('theme-btn');

if (getCookie('theme') == "dark") { themeToggle('dark'); } else {
if (getCookie('theme') == "light") { themeToggle('light'); } else { themeToggle('auto'); }}

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

var docHeight = document.documentElement.clientHeight;
var docWidth = document.documentElement.clientWidth;
var absolute100 = docHeight + "px";
var introimg = document.querySelector("#introimg img");
var loader = document.querySelector('.loader');
var loadlogopath = document.querySelectorAll('.loadlogo')[0];
var loadlogo = document.querySelector('.loader svg');
var i = 0;
var navSpace = document.getElementById('nav-space');
window.onload = function() {
    loadlogo.style.animationIterationCount = "1";
    if (getCookie('first') != "false") {
        setTimeout(sayHello, 2000);    
        document.cookie = "first=false; secure"
    } else {
        try {
            navSpace.classList.add('darknav');
            scrollFunction();
        } catch (e) {console.log(e);}
        document.getElementById('hoverbox').style.opacity = "0"; 
        setTimeout(function(){
            document.getElementById('hoverbox').style.display = "none"; 
        }, 700);
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
    setTimeout(function(){
        try {
            navSpace.classList.add('darknav');
            scrollFunction();
        } catch (e) {console.log(e);}
        document.getElementById('hoverbox').setAttribute('style', 'background-color: var(--accent); left: 50%; width: 0px;  opacity: 0'); 
    }, 500);
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

isSidanavOpen = false;
var prevScrollpos = window.pageYOffset;
var navbg = document.getElementById("nav-bg");
var nav = document.getElementById("navigation-top");
// window.onscroll = scrollFunction;
// function scrollFunction() {
//     try {
//         if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {
//             navbg.classList.add("shadow");   
//         } else {
//             navbg.classList.remove("shadow");
//         }
//     } catch (e) {}
// }


var drop = document.getElementsByClassName("dropbox");
var navlinkicon = document.querySelectorAll(".hover-drop .menulink i");
function dropboxShow(num) { drop[num].style.display = "block"; navlinkicon[num].setAttribute('style', 'transform: rotate(180deg);')}
function dropboxHide(num) { drop[num].style.display = "none"; navlinkicon[num].setAttribute('style', '')}

function toTop() {
	document.body.scrollTop = "0px";
	document.documentElement.scrollTop = "0px";
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

var menuBtn = document.getElementById("menu-btn");
var hovernav = document.getElementById("hovernav");
var hovertoggled = false;
var bars = document.getElementsByClassName("bar");

function hoverNavToggle() {
    if (hovertoggled) {
        hovertoggled = false;
        menuBtn.classList.remove('cross');
        navbg.setAttribute("style", "");
        nav.setAttribute("style", "");
        document.body.style.overflow = "scroll";
    } else {
        hovertoggled = true;
        menuBtn.classList.add('cross');
        navbg.setAttribute("style", "height: 100%;;");
        nav.setAttribute("style", "height: 100%; grid-template-rows: auto 1fr");
        document.body.style.overflow = "hidden";
    }
}