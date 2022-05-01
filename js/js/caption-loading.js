var captions = document.querySelectorAll("#text h2");
var captiontop = [];
var slide = document.querySelectorAll("#article h2");

[].forEach.call(captions, function(elem) {
    // alert(elem.innerHTML);
    
    let caption = document.createElement("a");
    caption.title = elem.innerHTML;
    let num = elem.id - 1;
    let text = 'makeActive('+ num +')';
    caption.setAttribute('onclick', text);
    let captionid = "#" + elem.id;
    caption.setAttribute("href", captionid);
    document.getElementById("dot-container").appendChild(caption);
    let dot = document.createElement("div");
    dot.classList = "dot";
    caption.appendChild(dot);
    let rawtop = elem.getBoundingClientRect().top + document.body.scrollTop;
    let top = Math.round(rawtop);
    captiontop.push(top);
    let captiontext = document.createElement("p");
    captiontext.innerHTML = elem.innerHTML;
    caption.appendChild(captiontext);
    let incaptionli = document.createElement("li");
    let incaption = document.createElement("a");
    incaption.setAttribute("href", captionid);
    incaption.innerHTML = elem.innerHTML;
    incaptionli.appendChild(incaption);
    document.getElementById("inner-contentnav").appendChild(incaptionli);
    elem.setAttribute('onclick', "location.href='" + captionid + "'")
});
var dot = document.getElementsByClassName("dot");
dot[0].classList.add("active");
var adot = 0;
$('window').on('scroll', function(){
    for (let i = 0; i < captiontop.length; i++) {
        if (document.body.scrollTop > captiontop[i] || document.documentElement.scrollTop > captiontop[i]) {   
            dot[i].classList.add("active");
        } 
    }
});

function makeActive(dotn) {
    dot[dotn].classList.add("active");
    adot = dotn;
}

var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
    [].forEach.call(captions, function(elem) {
        try {
            let top = elem.getBoundingClientRect().top;
            alert
        } catch (error) {}
    });
}