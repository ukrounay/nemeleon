function debounce(func, wait, immediate) {
    let timeout;

    return function executedFunction() {
        const context = this;
        const args = arguments;

        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };

        const callNow = immediate && !timeout;

        clearTimeout(timeout);

        timeout = setTimeout(later, wait);

        if (callNow) func.apply(context, args);
    };
};

// dragging

const dragItems = document.querySelectorAll(".dragitem");
var x, clX = 0, xmargin;
// var y, clY = 0, ymargin;
var first = true, dragging = false, dragItem;

dragItems.forEach(item => {item.addEventListener('mousedown', () => {dragging = true; dragItem = item;});});
document.addEventListener('mouseup', () => {dragging = false; dragItem = undefined;});
document.addEventListener('mousemove', (e) => {
    if(!first && dragging && dragItem) {
        xmargin = e.clientX - clX;
        // ymargin = e.clientY - clY;
        let x = dragItem.getBoundingClientRect().left + xmargin;
        // let y = dragItem.getBoundingClientRect().top + ymargin;
        dragItem.style.left = x + "px";
        // dragItem.style.top = y + "px";
    }
    clX = e.clientX;
    // clY = e.clientY;
    first = false;
});


// paralax effect

// if (window.innerWidth > 600) {
// 	let dragbox = document.querySelectorAll('.dragitem');
// 	window.addEventListener('mousemove', function(e) {
// 		let x = e.clientX / window.innerWidth;
// 		let y = e.clientY / window.innerHeight;  
// 		if (x != 0){
// 			for (const box of dragbox) {
//                 box.style.transform = 'translate(-' + x * 50 + 'px, -' + y * 50 + 'px)';
//             }
// 		}
// 	});
// }      


var busy = false;

class slider {

    constructor(slidername, sliderbody, slideritem, slidenumber, leftbtn, rightbtn, dots, number, infinite, heightManage) {
        this.slidername = slidername;
        this.sliderbody = sliderbody;
        this.slideritem = slideritem;
        this.slidenumber = slidenumber;
        this.leftbtn = leftbtn;
        this.rightbtn = rightbtn;
        this.dots = dots;
        this.number = number;
        this.infinite = infinite;
        this.heightManage = heightManage;
    }

    checkWatched() {
        if (!busy) {
            for (let i = 0; i < this.slideritem.length; i++) {
                if (i < this.slidenumber) {
                    this.slideritem[i].classList.add('hidden');
                    this.slideritem[i].classList.add('watched');
                    this.slideritem[i].classList.remove('unwatched');
                } else { if (i > this.slidenumber) {
                    this.slideritem[i].classList.add('hidden');
                    this.slideritem[i].classList.add('unwatched');
                    this.slideritem[i].classList.remove('watched');
                } else {
                    this.slideritem[i].classList.remove('hidden');
                    this.slideritem[i].classList.remove('unwatched');
                    this.slideritem[i].classList.remove('watched');
                }}
            }
        }
    }

    checkButtons() {
        switch (this.slidenumber) {
            case 0:
                this.leftbtn.classList.add('unable');
                this.rightbtn.classList.remove('unable');
                break;

            case this.slideritem.length - 1:
                this.leftbtn.classList.remove('unable');
                this.rightbtn.classList.add('unable');
                break; 
        
            default: 
                this.leftbtn.classList.remove('unable');
                this.rightbtn.classList.remove('unable');
                break;
        }
    }

    toSlide(number, reverse) {
        if (!busy) {
            if (this.slidenumber < number && !reverse || number < this.slidenumber && reverse) {
                busy = true;
                if (this.dots) {
                    this.dots[this.slidenumber].classList.remove('active');
                    this.dots[number].classList.add('active');
                }
                if (this.number) {
                    let max = this.slideritem.length;
                    let current = number + 1;
                    this.number.innerHTML = current + "/" + max;
                }
                this.slideritem[this.slidenumber].classList.add('watched');
                setTimeout(() => {
                    if (reverse) {
                        this.slideritem[number].classList.remove('watched');
                        this.slideritem[number].classList.add('unwatched');
                    }

                    let tempHeight = this.slideritem[this.slidenumber].clientHeight;
                    if(this.heightManage) this.sliderbody.style.height = tempHeight + "px";

                    this.slideritem[number].classList.remove('hidden');
                    this.slideritem[this.slidenumber].classList.add('hidden');

                    tempHeight = this.slideritem[number].clientHeight;
                    if(this.heightManage) this.sliderbody.style.height = tempHeight + "px";

                    setTimeout(() => {
                        this.slideritem[number].classList.remove('unwatched');
                        this.slidenumber = number;
                        setTimeout(() => {
                            if(this.heightManage) this.sliderbody.style.height = "auto";
                            busy = false;
                            this.checkWatched();
                            if (!this.infinite) this.checkButtons();
                        }, 200);
                    }, 200);
                }, 200);
            } else { if (number < this.slidenumber && !reverse || this.slidenumber < number && reverse) {
                busy = true;
                if (this.dots) {
                    this.dots[this.slidenumber].classList.remove('active');
                    this.dots[number].classList.add('active');
                }
                if (this.number) {
                    let max = this.slideritem.length;
                    let current = number + 1;
                    this.number.innerHTML = current + "/" + max;
                }
                this.slideritem[this.slidenumber].classList.add('unwatched');
                setTimeout(() => {
                    if (reverse) {
                        this.slideritem[number].classList.remove('unwatched');
                        this.slideritem[number].classList.add('watched');
                    }

                    let tempHeight = this.slideritem[this.slidenumber].clientHeight;
                    if(this.heightManage) this.sliderbody.style.height = tempHeight + "px";

                    this.slideritem[number].classList.remove('hidden');
                    this.slideritem[this.slidenumber].classList.add('hidden');

                    tempHeight = this.slideritem[number].clientHeight;
                    if(this.heightManage) this.sliderbody.style.height = tempHeight + "px";

                    setTimeout(() => {
                        this.slideritem[number].classList.remove('watched');
                        this.slidenumber = number;
                        setTimeout(() => {
                            busy = false;
                            this.checkWatched();
                            if (!this.infinite) this.checkButtons();
                        }, 200);
                    }, 200);
                }, 200);
            } else {
                this.checkWatched();
                if (!this.infinite) this.checkButtons();
                if (this.dots) {
                    this.dots[this.slidenumber].classList.remove('active');
                    this.dots[number].classList.add('active');
                }
                if (this.number) {
                    let max = this.slideritem.length;
                    let current = this.slidenumber + 1;
                    this.number.innerHTML = current + "/" + max;
                }
            }}
        }
    }

    next() {
        if (!busy && (this.slidenumber + 1) < this.slideritem.length) {
            this.toSlide(this.slidenumber + 1);
        } else {
            if (this.infinite) this.toSlide(0, true);
        }
    }

    previous() {
        if (!busy && (this.slidenumber - 1) >= 0) {
            this.toSlide(this.slidenumber - 1);
        } else {
            if (this.infinite) this.toSlide(this.slideritem.length - 1, true);
        }
    }

}