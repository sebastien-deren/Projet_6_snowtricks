import setupRadioChecker from "./mediaChoice.js";

window.addEventListener('DOMContentLoaded' ,( event =>{


    const form = document.querySelector('form[name ="media"]');
    setupRadioChecker(form);

}));

