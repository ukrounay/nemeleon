window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
       var lnk = document.getElementsByClassName('lnk');
        for(i=0; i < lnk.length; i++) {
            lnk[i].style.backgroundColor = "lightslategray";
            lnk[i].style.color = "white";
    }
    var logopath = document.getElementsByClassName('logo');
    logopath[0].style.fill = "lightslategray";


  } else {
        var lnk = document.getElementsByClassName('lnk');
        for(i=0; i < lnk.length; i++) {
            lnk[i].style.backgroundColor = "transparent";
            lnk[i].style.color = "black";
    }
    var logopath = document.getElementsByClassName('logo');
    logopath[0].style.fill = "black";

  }

  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
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
  document.body.scrollTop = 0; 
  document.documentElement.scrollTop = 0; 
}
