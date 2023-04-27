const require = (node,form) => {
    const isImage = 'Image' === node.value;
    const video = form.getElementsByClassName('videoField').item(0);
    const image = form.getElementsByClassName('fileField').item(0);
    if (!isImage) {
        video.firstElementChild.required =true
        image.firstElementChild.required =false
    } else {
        image.firstElementChild.required =true
        video.firstElementChild.required =false
    }

};
const listenClick = (element ,form) => element.addEventListener('click',() => require(element,form))

window.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('media');
    const choices = Array.from(form.getElementsByClassName('form-check-input'));
    choices.forEach((node) => listenClick(node, form));

});
