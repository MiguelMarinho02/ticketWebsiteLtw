const searchInput = document.querySelector('#search_user')
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

function sendData(username) {
    window.location.href = "user_profile.php?username=" + encodeURIComponent(username);
}