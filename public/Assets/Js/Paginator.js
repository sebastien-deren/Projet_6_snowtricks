function displayPagination(numberOfPages,section) {
    if (1 === numberOfPages) {
        return;
    }
    for (let i = 0; i < numberOfPages; i++) {
        const html = `
        <li class="page-item"><a class="page-link" data-page="${i}" href="#js-messages-display">${i + 1}</a></li>`;
        section.insertAdjacentHTML("beforeend", html);
    }

}
function paginate(pageItems,template,section , currentPage = 0) {

    const itemsToDisplay = pageItems[currentPage];
    section.innerHTML="";
    itemsToDisplay.forEach(message => template(message,section));


}
 function changePagesBtn(pagesItems,template,section) {
    const pageLinks = document.querySelectorAll(".page-link");
    if(!pageLinks){
        return;
    }
    pageLinks.forEach((pageLink) => {
        pageLink.addEventListener('click', _ => {
            paginate(pagesItems,template,section , Number(pageLink.dataset.page));
        });

    });
}

/**
 *
 * @param pages = jsonData (the json data)
 * @param template = callable (the template of the data displayed)
 * @param section  = Element (the section where the data is displayed)
 * @param paginationSection = Element (the section where the pages are displayed)
 */
export default function (pages,template,section,paginationSection){
    paginate(pages, template,section,0);
    displayPagination(pages.length,paginationSection);
    changePagesBtn(pages,template,section);
}
