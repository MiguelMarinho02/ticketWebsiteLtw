const searchInput = document.querySelector('#search_user')
const searchInputForTicket = document.querySelector('#search_user_ticket')
const showMoreButtonUsers = document.querySelector('#show-more-user')
const showMoreButtonTickets = document.querySelector('#show-more-tickets')
const searchTags = document.querySelector('#tag')
const searchTickets = document.querySelector('#search-tickets')
let limitForSearches = 10;
let limitForTickets = 10;
const request = new XMLHttpRequest();

function encodeForAjax(data) {
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

function loadResultsForTickets(limitForSearches){
    const value = searchTickets.value;
    
    request.open('get',"search_tickets.php?" + encodeForAjax({value:value,limit:limitForTickets}),true)
        request.onload = function() {
            if (request.status === 200) {
                // the response was successful, update the HTML with the new content
                document.getElementById('ticket-results').innerHTML = request.responseText;
            } else {
                // there was an error, log it to the console
            console.error('Request failed.  Returned status of ' + request.status);
            }
        };
    request.send();
}

if(searchTickets){
    if(!(searchTickets.value)){
        loadResultsForTickets(limitForTickets);
    }
}

if(showMoreButtonTickets != null){
    showMoreButtonTickets.addEventListener("click", function(){
        limitForTickets += 10;
        loadResultsForTickets(limitForTickets);
    })
}

if(searchTickets != null){
    searchTickets.addEventListener("input", (e) =>{
       loadResultsForTickets(limitForTickets);
    });
}



function loadResultsForUsers(limitForSearches){
    const value = searchInput.value;
    request.open('get',"search_users.php?" + encodeForAjax({value:value,limit:limitForSearches}),true)
        request.onload = function() {
            if (request.status === 200) {
                // the response was successful, update the HTML with the new content
                document.getElementById('search-result').innerHTML = request.responseText;
            } else {
                // there was an error, log it to the console
            console.error('Request failed.  Returned status of ' + request.status);
            }
        };
    request.send();
}

if(showMoreButtonUsers && searchInput){
    if(!(searchInput.value)){
        loadResultsForUsers(limitForSearches);
    }
}

if(showMoreButtonUsers != null){
    showMoreButtonUsers.addEventListener("click", function() {
        limitForSearches += 10;
        loadResultsForUsers(limitForSearches)
    });
}

if(searchInput != null){
    searchInput.addEventListener("input", (e) =>{
       loadResultsForUsers(limitForSearches);
    });
}

if(searchInputForTicket != null){
    searchInputForTicket.addEventListener("input", (e) =>{
        const value = e.target.value;
        console.log(1);
        
        request.open('get',"search_users_ticket.php?" + encodeForAjax({value:value}),true)
        request.onload = function() {
            if (request.status === 200) {
                // the response was successful, update the HTML with the new content
                document.getElementById('search-result').innerHTML = request.responseText;
            } else {
                // there was an error, log it to the console
            console.error('Request failed.  Returned status of ' + request.status);
            }
        };
        request.send();
    })
}

function sendDataUser(username) {
    window.location.href = "user_profile.php?username=" + encodeURIComponent(username);
}

function sendDataTicket(ticket_id) {
    window.location.href = "ticket_page.php?ticket_id=" + encodeURIComponent(ticket_id);
}

function sendDataTicketList(ticket_id) {
    window.location.href = "list_of_changes.php?ticket_id=" + encodeURIComponent(ticket_id);
}

function indexPage(){
    window.location='index.php';
}

function ticketsPage(){
    window.location='tickets.php';
}

function faqsPage(){
    window.location='faqs.php';
}

function usersPage(){
    window.location='users.php';
}

function adminPage(){
    window.location='admin_page.php';
}

function logoutPage(){
    window.location='logout.php';
}
