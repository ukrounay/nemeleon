var themeButton = document.getElementById('theme');
var themeLabel = document.getElementById('theme-label');

if (getCookie('theme') == "dark") { themeToggle('dark'); } else {
if (getCookie('theme') == "light") { themeToggle('light'); } else { themeToggle('auto'); }}

function themeToggle(theme) {
	document.body.classList = theme;
    themeButton.classList = theme + " switch";
    themeLabel.innerText = theme;
    themeButton.title = theme;
	document.cookie = "theme=" + theme + "; secure"

    if (theme == 'light') {
        themeButton.setAttribute('onclick', "themeToggle('dark')");
    } else {
        if (theme == 'dark') {
            themeButton.setAttribute('onclick', "themeToggle('auto')");
        } else {
            themeButton.setAttribute('onclick', "themeToggle('light')");
        }
    }

    changeColor();
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




changeColor(0);
setInterval(changeColor, 2000);

function getRandomInt(min, max) {
    const minCeiled = Math.ceil(min);
    const maxFloored = Math.floor(max);
    return Math.floor(Math.random() * (maxFloored - minCeiled) + minCeiled); // The maximum is exclusive and the minimum is inclusive
}

function changeColor(sat = 50) {
    let randomColorHue = Math.floor(Math.random()*360).toString();

    // --cl-bg-body: white;

    // --cl-bg-primary: #f1f1f1;
    // --cl-fg-primary: #1f1f1f;

    // --cl-bg-secondary: #d6e6ee;
    // --cl-fg-secondary: #5f5b74;

    // --cl-bg-containers: #bcdbee;
    // --cl-fg-containers: #171717;

    // --cl-accent-light: #90ccef;
    // --cl-accent: #48CAE4;
    // --cl-accent-mid-dark: #0096C7;
    // --cl-accent-dark: #083167;

    let level = 0;
    if (document.body.classList == "dark" || document.body.classList == "auto" && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {level = 100}

    document.querySelector(':root').style.setProperty('--cl-bg-body', "hsl(" + randomColorHue + " " + 0.5 * sat + "% " + Math.abs(level - 100) + "%)");

    document.querySelector(':root').style.setProperty('--cl-bg-primary', "hsl(" + randomColorHue + " " + 0.3 * sat + "% " + Math.abs(level - 95) + "%)");
    document.querySelector(':root').style.setProperty('--cl-fg-primary', "hsl(" + randomColorHue + " " + 0.3 * sat + "% " + Math.abs(level - 15) + "%)");

    document.querySelector(':root').style.setProperty('--cl-bg-secondary', "hsl(" + randomColorHue + " " + 0.5 * sat + "% " + Math.abs(level - 95) + "%)");
    document.querySelector(':root').style.setProperty('--cl-fg-secondary', "hsl(" + randomColorHue + " " + 0.5 * sat + "% " + Math.abs(level - 15) + "%)");

    document.querySelector(':root').style.setProperty('--cl-bg-containers', "hsl(" + randomColorHue + " " + 0.5 * sat + "% " + Math.abs(level - 85) + "%)");
    document.querySelector(':root').style.setProperty('--cl-fg-containers', "hsl(" + randomColorHue + " " + 0.5 * sat + "% " + Math.abs(level - 15) + "%)");

    document.querySelector(':root').style.setProperty('--cl-accent-light', "hsl(" + randomColorHue + " " + 0.9 * sat + "% " + Math.abs(level - 70) + "%)");
    document.querySelector(':root').style.setProperty('--cl-accent', "hsl(" + randomColorHue + " " + sat + "% " + Math.abs(level - 50) + "%)");
    document.querySelector(':root').style.setProperty('--cl-accent-mid-dark', "hsl(" + randomColorHue + " " + 0.9 * sat + "% " + Math.abs(level - 40) + "%)");
    document.querySelector(':root').style.setProperty('--cl-accent-dark', "hsl(" + randomColorHue + " " + 0.8 * sat + "% " + Math.abs(level - 15) + "%)");
}