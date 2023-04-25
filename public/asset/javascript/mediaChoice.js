
const showElement = (node, {video, image}) => {

    const isImage = 'Image' === node.value;
    image.style.display = isImage ? "block" : "none";
    video.style.display = isImage ? "none" : "block";

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



