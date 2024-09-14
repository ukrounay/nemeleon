/*  
//   nemeo slider v0.3
//   by ukrounay, 2023
*/  


class nemeo_slider {

    constructor(name, body, slides, currentSlide, previousButton, nextButton, indicators, numberIndicator, infinite, x, previousX, marginX, busy, dragging, finishTimer) {
        this.name = name;
        this.body = body;
        this.slides = slides;
        this.currentSlide = currentSlide;
        this.previousButton = previousButton;
        this.nextButton = nextButton;
        this.indicators = indicators;
        this.numberIndicator = numberIndicator;
        this.infinite = infinite;
        this.x = x;
        this.previousX = previousX;
        this.marginX = marginX;
        this.busy = busy;
        this.dragging = dragging;
        this.finishTimer = finishTimer;
    }

    moveInit(currentX) {
        if (this.busy) {
            this.busy = false;
            this.body.style.transition = "none";
            clearTimeout(this.finishTimer);
        }
        this.previousX = currentX;
        this.dragging = true;
        this.moving(currentX);
    }
    
    moving(currentX) {
        if (this.dragging) {
            let frameWidth = this.body.getBoundingClientRect().width/this.slides.length;
            this.marginX = currentX - this.previousX;
            if (this.x && this.x > 0 && -frameWidth*this.currentSlide + this.x > 0 && this.marginX > 0) {
                console.log("left");
                this.x = this.x + ((Math.abs(this.marginX) * (frameWidth - this.x)) / (frameWidth * 2));
            } else 
            if(this.x && this.x < 0 && this.currentSlide + Math.ceil(this.x / frameWidth) >= (this.slides.length - 1) && this.marginX < 0) {
                console.log("right");
                this.x = this.x - ((Math.abs(this.marginX) * (frameWidth - this.x)) / (frameWidth * 2));
            } else
            if (this.x) this.x += this.marginX; else this.x = this.marginX;
            this.body.style.left = -frameWidth*this.currentSlide + this.x + "px";
        }
        this.previousX = currentX;
    }
    
    
    autofinish() {
        if(this.dragging) {
            this.dragging = false;
            let frameWidth = this.body.getBoundingClientRect().width/this.slides.length;
            let framesScrolled = -Math.round(this.x/frameWidth);
            this.currentSlide += framesScrolled;
            if (framesScrolled == 0) {
                if (this.x >= 50) this.currentSlide--;
                if (this.x <= -50) this.currentSlide++;
            }
            if (this.currentSlide < 0) this.currentSlide = 0;
            if (this.currentSlide >= this.slides.length) this.currentSlide = this.slides.length - 1;
            this.toSlide(this.currentSlide);
        }
    }
    
    toSlide(slide) {
        if (slide >= 0 && slide < this.slides.length) {
            this.busy = true;
            this.x = 0;
            this.marginX = 0;
            this.previousX = 0;
            this.currentSlide = slide;
            this.numberIndicator.innerHTML = slide + 1 + " / " + this.slides.length;
            if (slide == 0) this.previousButton.classList.add("unavialable"); else this.previousButton.classList.remove("unavialable");
            if (slide == this.slides.length - 1) this.nextButton.classList.add("unavialable"); else this.nextButton.classList.remove("unavialable");
            this.indicators.forEach(indicator => {indicator.classList.remove("active")});
            this.indicators[slide].classList.add("active");
            this.body.style.transition = "300ms";
            this.body.style.left = -slide*100 + "%";
            this.finishTimer = setTimeout(()=>{
                this.body.style.transition = "none";
                this.busy = false;
                clearTimeout(this.finishTimer);
            },310);
        }
    }
    nextSlide() {
        this.toSlide(this.currentSlide + 1);
    }
    previousSlide() {
        this.toSlide(this.currentSlide - 1);
    }

}

let sliders = [];

window.addEventListener("load", () => {
    let htmlSliders = document.getElementsByClassName("nemeo-slider");
    for (let i = 0; i < htmlSliders.length; i++) {

        let slider;
        let htmlSlider = htmlSliders[i];
        
        slider = new nemeo_slider();

        slider.name = "nemeo-slider-" + (i + 1);
        htmlSlider.id = slider.name;

        slider.body = htmlSlider.querySelectorAll(".slider-body")[0];

        slider.slides = htmlSlider.dataset.sliderImages.split("|");

        for (let j = 0; j < slider.slides.length; j++) {
            let slideUrl = slider.slides[j].trim();
            let slideContainer = document.createElement("div");
            let slide = document.createElement("div");
            let slideBg = document.createElement("div");
            let indicator = document.createElement("span");
            slideContainer.classList.add("slide-container");
            slide.classList.add("slide");
            slide.style.backgroundImage = `url(${slideUrl})`
            slideBg.classList.add("slide-bg");
            slideBg.style.backgroundImage = `url(${slideUrl})`;
            slider.body.appendChild(slideContainer);
            slideContainer.appendChild(slide);
            slideContainer.appendChild(slideBg);
            indicator.classList.add("indicator");
            indicator.setAttribute("onclick", `sliders[${i}].toSlide(${j})`)
            htmlSlider.querySelectorAll(".indicators-container")[0].appendChild(indicator);
        }

        slider.slides = htmlSlider.querySelectorAll(".slide");

        slider.body.style.width = slider.slides.length*100 + "%";

        slider.currentSlide = 0;

        slider.previousButton = htmlSlider.querySelectorAll(".previous-btn")[0];
        slider.previousButton.href = `javascript:sliders[${i}].previousSlide()`

        slider.nextButton = htmlSlider.querySelectorAll(".next-btn")[0];
        slider.nextButton.href = `javascript:sliders[${i}].nextSlide()`

        slider.indicators = htmlSlider.querySelectorAll(".indicator");
        slider.indicators[slider.currentSlide].classList.add("active");

        slider.numberIndicator = htmlSlider.querySelectorAll(".number-indicator")[0];
        slider.numberIndicator.innerHTML = slider.currentSlide + 1 + " / " + slider.slides.length;

        slider.x = 0;
        slider.previousX = 0;
        slider.marginX = 0;
        slider.busy = false;
        slider.dragging = false;
        slider.finishTimer = undefined;

        slider.body.addEventListener('mousedown', (e) => {slider.moveInit(e.clientX)});
        slider.body.addEventListener('touchstart', (e) => {slider.moveInit(e.changedTouches[0].pageX)});

        document.addEventListener('mousemove', (e) => {slider.moving(e.clientX)});
        document.addEventListener('touchmove', (e) => {slider.moving(e.changedTouches[0].pageX)});

        document.addEventListener('mouseup', () => {slider.autofinish();});
        document.addEventListener('touchend', () => {slider.autofinish();});
        document.addEventListener('touchcancel', () => {slider.autofinish();});

        sliders[i] = slider;
    }
  
    console.log("sliders initialized", sliders);
});











// this slider without js classes

// const dragItems = document.querySelectorAll(".slider-body");
// let x = 0, previousX = 0, startX, xmargin = 0;
// // let y = 0, previousY = 0, starty, ymargin = 0;
// let busy = false;
// let dragging = false;
// let dragItem, slidesCount;
// let frameCount = 0;
// let finishTimer;


// dragItems.forEach(item => {
//     item.addEventListener('mousedown', (e) => {moveInit(item, e.clientX)});
//     item.addEventListener('touchstart', (e) => {moveInit(item, e.changedTouches[0].pageX)});
//     slidesCount = item.querySelectorAll(".slide").length;
//     item.style.width = slidesCount*100 + "%";
// });

// document.addEventListener('mousemove', (e) => {moving(e.clientX)});
// document.addEventListener('touchmove', (e) => {moving(e.changedTouches[0].pageX)});

// document.addEventListener('mouseup', () => {autofinish(dragItem);});
// document.addEventListener('touchend', () => {autofinish(dragItem);});
// document.addEventListener('touchcancel', () => {autofinish(dragItem);});

// function moveInit(item, currentX) {
//     if (busy) {
//         busy = false;
//         dragItem.style.transition = "none";
//         clearTimeout(finishTimer);
//     }
//     console.log('start', currentX, frameCount);
//     previousX = currentX;
//     dragging = true;
//     dragItem = item;
//     slidesCount = dragItem.querySelectorAll(".slide").length;
//     moving(currentX);
// }

// function moving(currentX) {
//     if (dragging && dragItem) {
//         xmargin = currentX - previousX;
//         x += xmargin;
//         slidesCount = dragItem.querySelectorAll(".slide").length;
//         let frameWidth = dragItem.getBoundingClientRect().width/slidesCount;
//         // if (x > frameWidth) x -= frameWidth;
//         dragItem.style.left = -frameWidth*frameCount + x + "px";
//     }
//     previousX = currentX;
// }


// function autofinish(elem) {
//     if(dragging) {
//         dragging = false;
//         let frameWidth = dragItem.getBoundingClientRect().width/slidesCount;
//         let framesScrolled = -Math.round(x/frameWidth);
//         frameCount += framesScrolled;
//         if (x >= 50) frameCount--;
//         if (x <= -50) frameCount++;
//         if (frameCount < 0) frameCount = 0;
//         if (frameCount >= slidesCount) frameCount = slidesCount - 1;
//         toSlide(elem, frameCount);
//     }
// }

// function toSlide(elem, slide) {
//     if (slide >= 0 && slide < slidesCount) {
//         busy = true;
//         x = 0;
//         xmargin = 0;
//         previousX = 0;
//         frameCount = slide;
//         elem.parentElement.querySelectorAll('.frame-count')[0].innerHTML = slide + 1 + "/" + slidesCount;
//         let previousButton = elem.parentElement.querySelectorAll('.previous-btn')[0];
//         let nextButton = elem.parentElement.querySelectorAll('.next-btn')[0];
//         if (slide == 0) previousButton.classList.add("unavialable"); else previousButton.classList.remove("unavialable");
//         if (slide == slidesCount - 1) nextButton.classList.add("unavialable"); else nextButton.classList.remove("unavialable");
//         let indicators = elem.parentElement.querySelectorAll('.indicator');
//         indicators.forEach(indicator => {indicator.classList.remove("active")});
//         indicators[slide].classList.add("active");
//         elem.style.transition = "300ms";
//         elem.style.left = -slide*100 + "%";
//         finishTimer = setTimeout(()=>{
//             elem.style.transition = "none";
//             elem = undefined;
//             busy = false;
//             clearTimeout(finishTimer);
//         },310);
//     }
// }
// function nextSlide(elem) {
//     toSlide(elem, frameCount + 1);
// }
// function previousSlide(elem) {
//     toSlide(elem, frameCount - 1);
// }

   