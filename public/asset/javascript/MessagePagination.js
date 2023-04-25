import  paginator from "./Paginator.js"


function getPost() {
    const messageTemplate = ({user,date,content,photo},section) =>{
        const html = `        
        <tr>
            <td><img src="${photo}"alt="${user}"/></td>
            <th>${user} </th>
            <td>${date}</td>
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
