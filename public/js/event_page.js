document.getElementById("search-attendees-teste").addEventListener("keyup", function(e) {
    fetch("searchUsersAdmin" + "?" + new URLSearchParams({
        search: e.target.value
    }), {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": '{{csrf_token()}}'
        },
        method: "get",
        credentials: "same-origin",
    }).then(function(data) {
        return data.json();
    }).then(function(data) {
        let container = document.getElementById("search-attendees-response");
        container.innerHTML = "";
        data.forEach(function(user) {
            let tr = document.createElement("tr");
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");
            let td3 = document.createElement("td");
            let td4 = document.createElement("td");

            td4.style.textAlign = "center";

            let btn = document.createElement("button");
            btn.setAttribute("class", "btn btn-success");
            btn.innerHTML = "View Page";
            btn.addEventListener("click", function() {
                window.location.href = "user" + user.userid
            });

            td1.innerHTML = user.userid;
            td2.innerHTML = user.name;
            td3.innerHTML = user.email;

            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(td4);
            container.appendChild(tr);
        });

    }).catch(function(error) {
        console.log(error);
    });
});

let currentChosen = document.getElementById("currentChosen");

currentChosen.value = 0;

// select a row of table
document.getElementById("search-attendees-response").addEventListener("click", function(e) {
    // reset all rows colors
    let rows = document.getElementById("search-attendees-response").getElementsByTagName("tr")
    for (let i = 0; i < rows.length; i++) {
        (i % 2 == 0) ? rows[i].style.backgroundColor = 'rgba(255, 255, 255, 0.5)' : rows[i].style.backgroundColor = 'rgba(202, 160, 229, 0.7)'
    }
    e.target.parentElement.style.backgroundColor = "rgba(138,238,130, 0.8";
    currentChosen.value = e.target.parentElement.children[0].innerHTML
    console.log(currentChosen.value)
});

// submit transfer ownership

let formTO = document.getElementById("tranferOwnershipForm");
formTO.addEventListener("submit", function(e) {
    e.preventDefault();

    if (currentChosen.value == 0) {
        alert("Please select a user to transfer ownership")
        return
    }
    else {
        formTO.submit();
    }
});
