import { gsap } from 'gsap';

class LinkFx {
    constructor(el) {
        this.DOM = {el: el};
        this.options = {
            animation: {
                text: false,
                line: true,
            },
        };
        this.DOM.text = document.createElement('span');
        this.DOM.text.classList = 'menu__link-inner';
        this.DOM.text.innerHTML = this.DOM.el.innerHTML;
        this.DOM.el.innerHTML = '';
        this.DOM.el.appendChild(this.DOM.text);
        this.DOM.line = document.createElement('span');
        this.DOM.line.classList = 'menu__link-deco';
        this.DOM.el.appendChild(this.DOM.line);

        this.DOM.el.dataset.text != undefined && (this.options.animation.text = this.DOM.el.dataset.text === 'false' ? false : true);
        this.DOM.el.dataset.line != undefined && (this.options.animation.line = this.DOM.el.dataset.line === 'false' ? false : true);

        this.initEvents();

        //super(el);
        this.filterId = '#distortionFilter';
        this.DOM.feDisplacementMap = document.querySelector(`${this.filterId} > feDisplacementMap`);
        this.primitiveValues = {scale: 0};

        this.createTimeline();
        this.tl.eventCallback('onUpdate', () => this.DOM.feDisplacementMap.scale.baseVal = this.primitiveValues.scale );
        this.tl.to(this.primitiveValues, {
            duration: 0.1,
            ease: 'Expo.easeOut',
            startAt: {scale: 0},
            scale: 60,
        })
        .to(this.primitiveValues, {
            duration: 0.6,
            ease: 'Back.easeOut',
            //startAt: {scale: 90},
            scale: 0,
        });
    }
    initEvents() {
        this.onMouseEnterFn = () => this.tl.restart();
        this.onMouseLeaveFn = () => this.tl.progress(1).kill();
        this.DOM.el.addEventListener('mouseenter', this.onMouseEnterFn);
        this.DOM.el.addEventListener('mouseleave', this.onMouseLeaveFn);
        this.DOM.el.addEventListener('touchstart', this.onMouseEnterFn, false);
        this.DOM.el.addEventListener('touchend', this.onMouseLeaveFn, false);
    }
    createTimeline() {
        // init timeline
        this.tl = gsap.timeline({
            paused: true,
            onStart: () => {
                if ( this.options.animation.line ) {
                    this.DOM.line.style.filter = `url(${this.filterId}`;
                }
                if ( this.options.animation.text ) {
                    this.DOM.text.style.filter = `url(${this.filterId}`;
                }
            },
            onComplete: () => {
                if ( this.options.animation.line ) {
                    this.DOM.line.style.filter = 'none';
                }
                if ( this.options.animation.text ) {
                    this.DOM.text.style.filter = 'none'
                }
            },
        });
    }
}

var $els_menuItems = $('a.menu__link');
$els_menuItems.each((i) => {
    new LinkFx($els_menuItems[i]);
});
