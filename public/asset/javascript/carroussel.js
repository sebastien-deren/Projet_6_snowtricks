window.addEventListener("DOMContentLoaded", (event) => {

    const items = document.querySelectorAll('.carousel-item')
    console.log(items)
    items.forEach((el) => {
        const minPerSlide = 3
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
        console.log(next);
    })
});
