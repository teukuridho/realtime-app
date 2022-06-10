<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="js/jquery.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <title>Realtime chat</title>
    <style>
        /* Create two equal columns that floats next to each other */
        #inner {
            display: table;
            margin: 0 auto;
        }

        #outer {
            width:100%
        }

        .row {
            display: flex;
            width: 50vw;
            margin-bottom: 10px;
        }

        /* Create two equal columns that sits next to each other */
        .column1 {
            flex: 75%;
            padding: 10px;
            height: 500px; 
            border: 1px solid black; overflow: scroll; 
        }
        .column2 {
            flex: 25%;
            padding: 10px;
            height: 500px;
            border: 1px solid black; overflow: scroll;
        }
    </style>
</head>
<body>
    <div id="outer">
        <div id="inner">
            <div class="row">
                Room: <input style="margin-left: 10px;" id="input-event" type="text" placeholder="Room Name"/>
                <button id="enter-event">Enter</button>
            </div>

            <div class="row">
                Username: <input style="margin-left: 10px;" id="input-name" type="text" placeholder="Your Name"/>
                <button id="enter-name">Enter</button>
            </div>
            <div class="row">
                <h4 id="connection-status">Not connected</h4>
            </div>

            <div class="row">
                <div class="column1" id='chat-box'>
                    <h3 id='connection-status'>Chat</h3>
                </div>
                <div class="column2" id="member-box">
                    <h3>Members</h3>
                </div>
            </div>

            <div class="row">
                <input id="input-message" type="text" placeholder="Your Message"/>
                <button id="enter-message">Enter</button>
            </div>
        </div>
    </div>
   
    <script>
        var name;
        var pusher;
        var channel = "presence-channel";
        var event;

        $(function () {
            // disable name
            $("#input-name").prop("disabled", true);
            $("#enter-name").prop("disabled", true);

            // disable message
            $("#input-message").prop("disabled", true);
            $("#enter-message").prop("disabled", true);

            // event enter
            $('#enter-event').click(function () {
                var input = $('#input-event').val();
                if(input == "") {
                    return;
                }

                // set event
                event = input;

                // enable name
                $("#input-name").prop("disabled", false);
                $("#enter-name").prop("disabled", false);

                // disable event
                $("#input-event").prop("disabled", true);
                $("#enter-event").prop("disabled", true);
            });

            // name enter
            $('#enter-name').click(function () {
                var input = $('#input-name').val();
                if(input == "") {
                    return;
                }

                // set name
                name = input;

                // disable name
                $("#input-name").prop("disabled", true);
                $("#enter-name").prop("disabled", true);

                // enable message
                $("#input-message").prop("disabled", false);
                $("#enter-message").prop("disabled", false);

                $('#connection-status').html("Connecting...");

                initPusher(function () {
                    // enable chat
                    $("#input-message").prop("disabled", false);
                    $("#enter-message").prop("disabled", false);
                    loadStoredMessages
                    $('#connection-status').html("Connected");

                    loadStoredMessages();
                });
            });

            // message enter
            $('#enter-message').click(function () {
                var inputMessage = $('#input-message').val();
                if(inputMessage !== "") {
                    sendMessage(name, inputMessage, function (data) {
                    });
                    
                }
            });
        });

        function initPusher(success_callback) {
            Pusher.logToConsole = true;

            pusher = new Pusher("82830840dc35127efac7", {
                cluster: 'ap1',
                authEndpoint: '{{ url('/pusher/auth') }}',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'username': $('#input-name').val(),
                    }
                }
            });

            pusher = pusher.subscribe(channel);

            pusher.bind('pusher:subscription_succeeded', function(members) {
                if(success_callback != null)
                {
                    success_callback();
                }

                // load members
                pusher.members.each(function (member) {
                    addToMemberBox(member.id);
                });
                console.log("subscribed ok");
            });
                
            pusher.bind(event, function(data) {
                addToChatBox(data.name, data.message);
            });            

            pusher.bind("pusher:member_added", (member) => {
                addToMemberBox(member.id);
                console.log(member);
            });

            pusher.bind("pusher:member_removed", (member) => {
                deleteFromMemberBox(member.id);
                console.log("removed");
                console.log(member);
            });
        }

        // function createChat(success_callback = null) {
        //     $.ajax({
        //         type: "GET",
        //         url: "{{ url('/advanced-chat/create-chat') }}/" + event,
        //         success: function (data) {
        //             if(success_callback != null) success_callback();
        //         },
        //         error: function (data) {
        //             alert("Gagal: " + data);
        //         }
        //     });
        // }

        function loadStoredMessages() {
            $.ajax({
                type: "GET",
                url: "{{ url('/advanced-chat/get-messages') }}/" + event,
                success: function (data) {
                    for(var i = 0; i < data.length; ++i)
                    {
                        var obj = data[i];
                        addToChatBox(obj.sender, obj.message);
                    }
                    // console.log(data);
                },
                error: function (data) {
                    alert("Gagal: " + data);
                }
            }); 
        }

        function addToMemberBox(member_name) {
            var newName = member_name;
            if(member_name == name) {
                newName += " (you)";
            }
            $('#member-box').append(
                "<p id='user-" + member_name + "' style='text-align: left; font-weight: 300'>" + newName + "</p>"
            );
        }

        function deleteFromMemberBox(member_name) {
            $('#user-' + member_name).remove();
        }

        function sendMessage(name, message, success_callback = null) {
            $.ajax({
                type: "GET",
                url: "{{ url('/advanced-chat/send-message') }}/" + name + "/" + message + "/" + event,
                success: function (data) {
                    if(success_callback != null) {
                            success_callback(data);
                    }
                },
                error: function (data) {
                    alert("Gagal: " + data);
                }
            });
        }

        function addToChatBox(chatName, message) {
            var align = chatName == name ? "right" : "left";

            $('#chat-box').append(
                "<p style='text-align: " + align +"; font-weight: 600'>" + chatName + "</p>"
            );
                
            $('#chat-box').append(
                "<p style='text-align: " + align + "; font-weight: 300'>" + message + "</p>"
            );
        }
    </script>
</body>
</html>