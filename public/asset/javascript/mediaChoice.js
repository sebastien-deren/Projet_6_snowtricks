
const showElement = (node ,{video, image})=> {
    if('Image' === node.value) {
        image.style.display="block";
        video.style.display="none";
    }
    if('Video'=== node.value) {
        image.style.display="none";
        video.style.display="block";
    }
};
const listenClick = (element ,fields) => element.addEventListener('click',() => showElement(element,fields))

export default function setupRadioChecker(form ) {
    const radioChoice = form.querySelector('.choice');
    const videoField = form.querySelector('.videoField');
    const imageField = form.querySelector('.fileField');
    const fields ={"video":videoField,"image":imageField};
    const choices = Array.from(radioChoice.getElementsByClassName('form-check-input'));
    choices.forEach((node) => listenClick(node,fields));

};



