import setupRadioChecker from "./mediaChoice.js";
const setupForEachMedium = (mediumForm)=>{

    //need to listen to the button!
    const mediaForms = mediumForm.getElementsByTagName("li");
    const mediaArray = Array.from(mediaForms);
    mediaArray.forEach((child)=>setupRadioChecker(child));
}

const MediaFormElement =(event) =>{
    const mediumForm = document.getElementById("medium_form_js")
    setupForEachMedium(mediumForm)
    return mediumForm;
}
window.addEventListener('DOMContentLoaded', _ =>{
    const mediumForm = MediaFormElement()

    const observer = new MutationObserver(MediaFormElement);

    observer.observe(mediumForm,{subtree:true,childList:true})

})