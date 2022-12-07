@extends('layouts.app')

@section('content')

@include('partials.toast')

<div class="container" id="event-content">
    <div hidden id="token_event_id">{{$event->eventid}}</div>
    <img src="{{$event -> photos[0]->path}}" style="border-radius: 5%; height:45rem;">
    <div class="wrapper-res">
        <div id="event-name"> {{$event->name}} </div>
        <div id="event-date"> {{date('Y-m-d', strtotime($event->date))}} </div>
    </div>

    @if (Auth::user() != NULL && !Auth::user()->attendingEvents->contains($event->eventid))
    <button onclick="ajax_selfAddUser('{{Auth::user()->userid}}', '{{$event->eventid}}')" class="btn btn-info"> <a> <i class="fa fa-layer-group fa-fw"></i>
            Enroll Event </a></button>
    @else
    @if ((Auth::user() != NULL))
    <button onclick="ajax_selfRemoveUser('{{Auth::user()->userid}}', '{{$event->eventid}}')" class="btn btn-danger"> <a> <i class="fa fa-layer-group fa-fw"></i>
            Leave Event </a></button>
    @endif
    @endif
    <nav>
        <ul id="menu-info">
            <li class="menu-info-item text-center">
                <div> Location </div>
                <p> {{$event->city->country->name}}, {{$event->city->name}} </p>
            </li>
            <li class="menu-info-item text-center">
                <div> Capacity </div>
                <p> {{$event->capacity}} places </p>
            </li>
            <li class="menu-info-item text-center">
                <div> Address </div>
                <p> {{$event->address}} </p>
            </li>
        </ul>
    </nav>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</div>

<div class="navigation">
    <ul>
        <li class="list active">
            <a href="#" onclick="showUserDiv()">
                <span class="icon">
                    <ion-icon name="home-outline"></ion-icon>
                </span>
                <span class="title">Event Host</span>
            </a>
        </li>
        @if (Auth::user() != NULL &&Auth::user()->userid == $host->userid)
        <li class="list">
            <a href="#" onclick="showOutroDiv()">
                <span class="icon">
                    <ion-icon name="person-outline"></ion-icon>
                </span>
                <span class="title">Attendees</span>
            </a>
        </li>
        @endif
        <li class="list">
            <a href="#">
                <span class="icon">
                    <ion-icon name="call-outline"></ion-icon>
                </span>
                <span class="title">Contact</span>
            </a>
        </li>
        <li class="list">
            <a href="#" onclick="showInviteDiv()">
                <span class="icon">
                    <ion-icon name="mail-outline"></ion-icon>
                </span>
                <span class="title">Send Invite</span>
            </a>
        </li>
    </ul>
</div>

<div id="info-navbar-container">

    <div id="userDiv" style="display:none; text-align:center;" class="answer_list">
        <button id="close-modal-button"></button>
        <div class="svg-background"></div>
        <div class="svg-background2"></div>
        <div class="circle"></div>

        <img class="profile-img" src="{{$host->profilepic}}">
        <div class="text-container">
            <p class="title-text"> {{$host->name}}</p>
            <p class="info-text">Event Host</p>
            <p class="desc-text"> {{count($host->hostedEvents)}} events hosted </p>
        </div>

    </div>

    @if (Auth::user() != NULL && Auth::user()->userid == $host->userid)
    <div id="outroDiv" data-mdb-animation="slide-in-right" style="display:none;" class="answer_list">

        <input type="text" class="form-controller" id="search-users" name="search"></input>
        <table class="table table-bordered table-hover" style="margin-top:1rem;">
            <thead>
                <tr>
                    <th>UserName</th>
                    <th>Email</th>
                    <th>ACTION </th>
                </tr>
            </thead>
            <tbody id="table-user-res">
            </tbody>
        </table>
        <button id="close-modal-button"></button>
    </div>
    @endif

    <div id="inviteDiv" data-mdb-animation="slide-in-right" style="display:none;" class="answer_list">
        Please enter the email of the user you wish to invite
        <button class="skrr" id="close-modal-button"></button>
        <div id="emailInvite">
            <input type="text" class="form-controller" id="sendInvite" name="email"></input>
            <button href="#" class="btn btn-success btn-sm btn-edit-event" type="submit" onClick="createInvite({{$event->eventid}})">Send Invite</button>
        </div>

    </div>
</div>


<div class="mx-auto col-lg-8" id="comment-section-container">
@if ($event->comments() !== null)
    @if (Auth::user() != NULL)
    <div class="p-4 mb-2" id="new-comment">
        <!-- New Comment //-->
        <div class="">
            <img class="rounded-circle me-4" style="width:5rem;height:5rem; float:left;" src="{{Auth::user()->profilepic}}">
            <div class="flex-grow-1">
                <div class="gap-2">
                    <p href="#" class="fw-bold">{{Auth::user()->name}}</p>
                </div>
                <form action="" class="form-floating" style="margin-top: 5rem;">
                    <textarea class="form-control w-100" placeholder="Leave a comment here" id="my-comment" style="height:5rem;"></textarea>
                    <label for="my-comment">Leave a comment here</label>

                    <div class="hstack justify-content-end gap-2">
                        <button class="btn btn-sm btn-primary text-uppercase mt-3" type="submit">comment</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @endif
    <div class="shadow-sm p-4" id="comments-section">
        <h4 class="mb-4">7 Comments</h4>
        <div class="">
            <!-- Comment //-->
            <div class="py-3">
                @foreach ($event->comments()->get() as $comment)
                <div class="d-flex comment">
                    <img class="rounded-circle comment-img" src="{{$comment->user->profilepic}}" />
                    <div class="flex-grow-1 ms-3">
                        <div class="mb-1"><a href="#" class="fw-bold link-dark me-1">{{$comment->user->name}}</a>
                            <span class="text-muted text-nowrap"> {{$comment->date}}</span>
                        </div>
                        <div class="mb-2"> {{$comment->text}} </div>
                        <div class="hstack align-items-center mb-2">
                            <a class="link-primary me-2" href="#"><i class="fas fa-thumbs-up"></i></a>
                            <span class="me-3 small">55</span>
                            <a class="link-danger small ms-3" href="#">delete</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    @endif
</div>
<!-- ////////////////////////////////// END OF AJAX REQUESTS ////////////////////////////////////// -->

<script type="text/javascript">
    document.querySelector('#new-comment button').addEventListener('click', function(e) {
        e.preventDefault();

        var comment = document.querySelector('#my-comment');

        if (comment.value == '') {
            let error = document.querySelector('#new-comment label');
            error.innerHTML = 'Error: Comment cannot be empty';
            error.style.color = 'red';
            document.querySelector('#new-comment form').classList.add("apply-shake");
            setTimeout(function() {
                document.querySelector('#new-comment form').classList.remove("apply-shake");
            }, 500);
            return;
        } else {
            fetch("{{route('storeComment')}}", {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": '{{ csrf_token() }}'
                },
                method: "post",
                credentials: "same-origin",
                body: JSON.stringify({
                    userid: "{{Auth::user()->userid}}",
                    eventid: "{{$event->eventid}}",
                    text: document.querySelector('#my-comment').value,
                })
            }).then(function(data) {
                document.location.reload();
            }).catch(function(error) {
                console.log(error);
            });
        }

    });
</script>

<script type="text/javascript" defer>
    function ajax_selfRemoveUser(userid, eventid) {
        fetch("selfRemoveUser", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                userid: userid,
                eventid: eventid
            })
        }).then(function(data) {
            document.querySelector('#event-content button').remove();
            
            let button = document.createElement('button');
            button.setAttribute('class', 'btn btn-info');
            
            let a = document.createElement('a');
            let i = document.createElement('i');
            button.appendChild(a);

            a.innerHTML = 'Please Wait...';
            i.setAttribute('class', 'fa fa-layer-group fa-fw')
            a.parentElement.insertBefore(i, a);

            button.setAttribute('disabled', true);
            showAlert("leave");
            // button.onClick = ajax_selfRemoveUser(userid, eventid);
            setTimeout(function() {
                button.removeAttribute('disabled');
                button.addEventListener('click', function() {
                    ajax_selfAddUser(userid, eventid);
                });
                a.innerHTML = 'Enroll Event';
            }, 5000);

            

            document.querySelector('#event-content').appendChild(button);
        }).catch(function(error) {
            console.log(error);
        });
    };

    function ajax_selfAddUser(userid, eventid) {
        fetch("{{route('selfAddUser')}}", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                userid: userid,
                eventid: eventid
            })
        }).then(function(data) {
            document.querySelector('#event-content button').remove();
            
            let button = document.createElement('button');
            button.setAttribute('class', 'btn btn-danger');
            
            let a = document.createElement('a');
            let i = document.createElement('i');
            button.appendChild(a);

            a.innerHTML = 'Please Wait...';
            i.setAttribute('class', 'fa fa-layer-group fa-fw')
            a.parentElement.insertBefore(i, a);

            button.setAttribute('disabled', true);
            showAlert("enroll");

            // button.onClick = ajax_selfRemoveUser(userid, eventid);
            setTimeout(function() {
                button.removeAttribute('disabled');
                button.addEventListener('click', function() {
                    ajax_selfRemoveUser(userid, eventid);
                });
                a.innerHTML = 'Leave Event';
            }, 5000);

            document.querySelector('#event-content').appendChild(button);
        }).catch(function(error) {
            console.log(error);
        });
    };

    function ajax_addUser(userid, eventid) {
        fetch("addEventUsers", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                userid: userid,
                eventid: eventid
            })
        }).then(function(data) {
            refreshDiv();
        }).catch(function(error) {
            console.log(error);
        });
    };

    function ajax_remUser(userid, eventid) {
        fetch("removeEventUsers", {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "post",
            credentials: "same-origin",
            body: JSON.stringify({
                userid: userid,
                eventid: eventid
            })
        }).then(function(data) {
            refreshDiv();
        }).catch(function(error) {
            console.log(error);
        });
    };
</script>

<!-- ONLY AVAILABLE FOR HOST -->
@if (Auth::user() != NULL && Auth::user()->userid == $host->userid)
<script type="text/javascript" defer>
    document.getElementById("search-users").addEventListener("keyup", function(e) {
        if (document.getElementById("search-users").value == '') return;
        fetch("{{route('searchUsers')}}" + "?" + new URLSearchParams({
            search: document.getElementById("search-users").value,
            event_id: '{{$event->eventid}}'
        }), {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "get",
            credentials: "same-origin",

        }).then(function(data) {
            return data.json();
        }).then(function(data) {


            let eventid = document.querySelector("#token_event_id").innerHTML;
            console.log(eventid);
            let container = document.getElementById("table-user-res");
            container.innerHTML = "";
            console.log(data);
            data.forEach(function(user) {

                let tr = document.createElement("tr");
                let td1 = document.createElement("td");
                let td2 = document.createElement("td");
                let td3 = document.createElement("td");

                td3.style.textAlign = "center";
                let btn = document.createElement("button");

                if (user.attending_event == true) {
                    btn.setAttribute("class", "btn btn-danger");
                    btn.innerHTML = "Remove";
                    btn.addEventListener('click', function(e) {
                        ajax_remUser(user.userid, eventid);
                        refreshDiv();
                    })
                } else {
                    btn.setAttribute("class", "btn btn-success");
                    btn.innerHTML = "Add to Event";
                    btn.addEventListener('click', function(e) {
                        ajax_addUser(user.userid, eventid);
                        refreshDiv();
                    })
                }
                td1.innerHTML = user.name;
                td2.innerHTML = user.email;
                td3.appendChild(btn);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                container.appendChild(tr);

            });
        }).catch(function(error) {
            console.log(error);
        });
    });

    document.querySelector('#outroDiv button').addEventListener('click', function() {
        let d = document.getElementById('outroDiv');
        d.classList.add("animate-out");
        setTimeout(function() {
            d.classList.remove("animate-out");
        }, 500);
        setTimeout(function() {
            d.style.display = "none";
        }, 450);
    })

    function showOutroDiv() {
        document.getElementById("info-navbar-container").querySelectorAll('#info-navbar-container > div').forEach(n => n.style.display = 'none');
        let d = document.getElementById('outroDiv');
        d.classList.add("animate");
        setTimeout(function() {
            d.classList.remove("animate");
        }, 500);
        d.style.display = "block";
    }
</script>
@endif

<script type="text/javascript">
    function refreshDiv() {
        fetch("{{route('searchUsers')}}" + "?" + new URLSearchParams({
            search: document.getElementById("search-users").value,
            event_id: '{{$event->eventid}}'
        }), {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            method: "get",
            credentials: "same-origin",

        }).then(function(data) {
            return data.json();
        }).then(function(data) {


            let eventid = document.querySelector("#token_event_id").innerHTML;
            console.log(eventid);
            let container = document.getElementById("table-user-res");
            container.innerHTML = "";
            console.log(data);
            data.forEach(function(user) {

                let tr = document.createElement("tr");
                let td1 = document.createElement("td");
                let td2 = document.createElement("td");
                let td3 = document.createElement("td");

                td3.style.textAlign = "center";
                let btn = document.createElement("button");

                if (user.attending_event == true) {
                    btn.setAttribute("class", "btn btn-danger");
                    btn.innerHTML = "Remove";
                    btn.addEventListener('click', function(e) {
                        ajax_remUser(user.userid, eventid);
                        refreshDiv();
                    })
                } else {
                    btn.setAttribute("class", "btn btn-success");
                    btn.innerHTML = "Add to Event";
                    btn.addEventListener('click', function(e) {
                        ajax_addUser(user.userid, eventid);
                        refreshDiv();
                    })
                }
                td1.innerHTML = user.name;
                td2.innerHTML = user.email;
                td3.appendChild(btn);

                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                container.appendChild(tr);

            });
        }).catch(function(error) {
            console.log(error);
        });
    };

    //////////////////////////////// END OF AJAX REQUESTS //////////////////////////////////////
</script>


<script type="text/javascript" defer>
    const list = document.querySelectorAll('.list')

    function activeLink() {
        list.forEach((item) => item.classList.remove('active'))
        this.classList.add('active')
    }

    list.forEach((item) => item.addEventListener('click', activeLink))

    function showUserDiv() {
        document.getElementById("info-navbar-container").querySelectorAll('#info-navbar-container > div').forEach(n => n.style.display = 'none');
        let d = document.getElementById('userDiv');
        d.classList.add("animate");
        setTimeout(function() {
            d.classList.remove("animate");
        }, 500);
        d.style.display = "block";
    }

    function showInviteDiv() {
        document.getElementById("info-navbar-container").querySelectorAll('#info-navbar-container > div').forEach(n => n.style.display = 'none');
        let d = document.getElementById('inviteDiv');
        d.classList.add("animate");
        setTimeout(function() {
            d.classList.remove("animate");
        }, 500);
        d.style.display = "block";
    }
    document.querySelector('#userDiv > button').addEventListener('click', function() {

        let d = document.getElementById('userDiv');
        d.classList.add("animate-out");
        setTimeout(function() {
            d.classList.remove("animate-out");
        }, 500);
        setTimeout(function() {
            d.style.display = "none";
        }, 500);
    })
    document.querySelector('#inviteDiv > .skrr').addEventListener('click', function() {
        let d = document.getElementById('inviteDiv');
        d.classList.add("animate-out");
        setTimeout(function() {
            d.classList.remove("animate-out");
        }, 500);
        setTimeout(function() {
            d.style.display = "none";
        }, 500);
    })

    function isEmpty(obj) {
        for (var prop in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                return false;
            }
        }
        return JSON.stringify(obj) === JSON.stringify({});
    }
</script>

@endsection