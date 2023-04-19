import  paginator from "./Paginator.js"


function getPost() {
    const messageTemplate = ({user,date,content},section) =>{
        const html = `        
        <tr>
            <th>${user} ${date}</th>
            <td>${content}</td>
        </tr>`;
        section.insertAdjacentHTML("beforeend", html);
    }
    document.addEventListener('DOMContentLoaded', function () {
        const messagePagination = document.querySelector('.js-messages-paginator');
        const messageSection = document.querySelector(".js-messages-display");
        const paginationSection = document.querySelector(".message-pagination");
        const messagesPages = JSON.parse(messagePagination.dataset.messages);

        if(!messageSection || !messagePagination || !paginationSection){
            return;
        }
        paginator(messagesPages,messageTemplate,messageSection,paginationSection);
    });
}
getPost()