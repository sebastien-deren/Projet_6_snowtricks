window.addEventListener("DOMContentLoaded", (event) => {
    const items = document.querySelectorAll('.carousel-item')
    const carousel = document.getElementById("carouselMedia")
    const minPerSlide = 3
    if(items.length<=minPerSlide && carousel){
        carousel.classList.remove("carousel");
        const carouselInner =document.getElementById("carousel-inner");
        carouselInner.classList.remove("carousel-inner")
        carouselInner.classList.add("d-flex");
        carouselInner.classList.add('flex-col')
        items.forEach((el)=>{
            el.classList.remove('carousel-item');
            el.classList.add('carousel-disable');
        });
    }
    else{
    items.forEach((el) => {

        let next = el.nextElementSibling
        for (let i=1; i<minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })
    }
});
