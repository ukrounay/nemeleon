var article = document.querySelector("#article");
var dotCont = document.querySelector("#dot-cont");
var pages = document.getElementsByClassName("article-page");
for (let i = 0; i < pages.length; i++) {
    var dot = document.createElement('i');
    dot.classList = "fa-solid fa-circle dot";
    let goto = 'goToPage(' + i + ')'
    dot.setAttribute('onclick', goto); 
    dotCont.appendChild(dot);
}
var curPage = 0;
var dots = document.getElementsByClassName("dot");
function goToPage(pagenum) {
    for (let i = 0; i < pages.length; i++) {
        let cdot = dots[i];
        let page = pages[i];
        if (i < pagenum) {page.classList.add('watched');};
        if (i > pagenum) {page.classList.add('unwatched');};
        if (i == pagenum) {cdot.classList = "fa-solid fa-circle dot";};
        if (i != pagenum) {cdot.classList = "fa-solid fa-circle-dot dot";};
    }
    pages[pagenum].classList.add('watching');
}
goToPage(0);