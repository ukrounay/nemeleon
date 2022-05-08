article = document.querySelector("#article");
dotCont = document.querySelector("#dot-cont");
pages = document.querySelectorAll(".article-page");
for (let i = 0; i < pages.length; i++) {
    let page = pages[i];
    let dot = document.createElement("i");
    dot.classList = "fa-solid fa-circle-small dot";
    dot.onclick = goToPage(i);
    dotCont.appendChild(dot);
}
var curPage = 0;
goToPage(0);
dots = document.querySelectorAll('.dot');
function goToPage(pagenum) {
    for (let i = 0; i < pages.length; i++) {
        let dot = dots[i];
        let page = pages[i]
        if (i < pagenum) {page.classList.add('watched');}
        if (i > pagenum) {page.classList.add('unwatched');}
        if (i = pagenum) {dot.classList = "fa-solid fa-circle-dot dot";}
        if (i != pagenum) {dot.classList = "fa-solid fa-circle-small dot";}
    }
    pages[pagenum].classList.add('watching');
}