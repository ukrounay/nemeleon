window.onload = function() {
	document.getElementById('topbtn').style.opacity = "0.0";
	if (getCookie('theme') == "dark") {
		document.body.classList = "dark";
	} else {
		if (getCookie('theme') == "light") {
			document.body.classList = "light";
		} else {
			document.body.classList = "auto";
		}
	}
}

isSidanavOpen = false;
var prevScrollpos = window.pageYOffset;
var nav = document.getElementById("navigation-top");
window.onscroll = function() {
	if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1) {
		document.getElementById('navigation-top').classList.add("shadow");
	} else {
	    document.getElementById('navigation-top').classList.remove("shadow");
	}

    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
	    var currentScrollPos = window.pageYOffset;
	    if (prevScrollpos > currentScrollPos) {
	        nav.style.top = "0";
	    } else {
	        nav.style.top = "-60px";
	    }
	    prevScrollpos = currentScrollPos;
		document.getElementById('topbtn').style.opacity = "0.7 !important";
    }
	
}


var drop = document.getElementsByClassName("dropbox");
function dropboxShow(num) { drop[num].style.display = "block"; }
function dropboxHide(num) { drop[num].style.display = "none"; }

function topFunction() {
	document.body.scrollTop = "0px";
	document.documentElement.scrollTop = "0px";
}

function setTheme(theme) {
	document.body.classList = theme;
	document.cookie = "theme=" + theme + "; secure"
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


// sidenav
// sidenavbg
// iconmenu
// function openhideSidenav() {
// 	if (isSidanavOpen == false) {
// 		sidenav.style.left = "0px";
// 		sidenavbg.style.display = "block";
// 		sidenavbg.style.opacity = "0.5";
// 		iconmenu.style.transform = "rotate(90deg)"
// 		isSidanavOpen = true;
// 	} else {
// 		sidenav.style.left = "-310px";
// 		sidenavbg.style.opacity = "0.0";
// 		sidenavbg.style.display = "none";
// 		iconmenu.style.transform = "rotate(0deg)"
// 		isSidanavOpen = false;
// 	}
// }