const searchInput = document.querySelector('#search_user')
const searchInputForTicket = document.querySelector('#search_user_ticket')
const request = new XMLHttpRequest();

function encodeForAjax(data) {
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

if(searchInput != null){
    searchInput.addEventListener("input", (e) =>{
        const value = e.target.value;
        
        request.open('get',"search_users.php?" + encodeForAjax({value:value}),true)
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

if(searchInputForTicket != null){
    searchInputForTicket.addEventListener("input", (e) =>{
        const value = e.target.value;
        
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