$(window).on('load', function() {
$('.hoverbox').fadeOut().end().delay(2000).fadeOut('slow');
});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
    document.getElementById("navbar").style.padding = "10px";
    document.getElementById("navbar").style.backgroundColor = "#202020";
    document.getElementById("logo").setAttribute("src", "styles/images/logo.white.svg");
    var lnk = document.getElementsByClassName('lnk');
    for(i=0; i < lnk.length; i++) {
        lnk[i].style.backgroundColor = "lightslategray";
        lnk[i].style.color = "white";
    }
    var logopath = document.getElementsByClassName('logopath');
    for(i=0; i < logopath.length; i++) {
        logopath[i].style.fill = "lightslategray";
    }

  } else {
    document.getElementById("navbar").style.padding = "25px 10px";
    document.getElementById("navbar").style.backgroundColor = "transparent";
    document.getElementById("logo").setAttribute("src", "styles/images/logo.svg");
    var lnk = document.getElementsByClassName('lnk');
    for(i=0; i < lnk.length; i++) {
        lnk[i].style.backgroundColor = "transparent";
        lnk[i].style.color = "black";
    }
    var logopath = document.getElementsByClassName('logopath');
    for(i=0; i < logopath.length; i++) {
        logopath[i].style.fill = "black";
    }
  }

  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
  	topbtn = document.getElementById("topbtn");
    topbtn.style.display = "block";
  } else {
  	topbtn = document.getElementById("topbtn");
    topbtn.style.display = "none";
  }

  var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var scrollheight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / scrollheight) * 100;
  document.getElementById("myBar").style.width = scrolled + "%";

}

function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
