<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="js/jquery.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <title>Realtime chat</title>
</head>
<body>
    <center>
        <div id="intro-box">
            <input id="input-name" type="text" placeholder="Your Name"/>
            <button id="enter-name">Enter</button>
        </div>
        <div id="chat-box" style="margin-top: 5px; margin-bottom: 5px; width: 500px; height: 700px; border: 1px solid black; overflow: scroll;">
            <h3 id='connection-status'>You're not connected</h3>
        </div>
        <div id="send-chat-box">
            <input id="input-message" type="text" placeholder="Your Message"/>
            <button id="enter-message">Enter</button>
        </div>
    </center>
    <script>
        var name;
        var pusher;
        var channel;

        $(function () {
            $("#input-message").prop("disabled", true);
            $("#enter-message").prop("disabled", true);

            $('#enter-name').click(function () {
                var inputName = $('#input-name').val();
                if(inputName !== "") {

                    name = inputName;
                    $("#enter-name").prop("disabled", true);
                    $("#input-name").prop("disabled", true);

                    $("#input-message").prop("disabled", false);
                    $("#enter-message").prop("disabled", false);
                    
                    $('#connection-status').html("Connecting...");

                    Pusher.logToConsole = true;

                    pusher = new Pusher('82830840dc35127efac7', {
                        cluster: 'ap1'
                    });

                    channel = pusher.subscribe('notification');

                    channel.bind('pusher:subscription_succeeded', function(members) {
                        $('#connection-status').html("You're connected");

                        sendMessage(name, "(Connected)");
                    });


                    channel.bind('App\\Events\\MessageNotification', function(data) {
                        addToChatBox(data.name, data.message);
                    });
                }
            });

            function addToChatBox(name, message) {
                $('#chat-box').append(
                    "<p style='text-align: left; font-weight: 600'>" + name + "</p>"
                );
                
                $('#chat-box').append(
                    "<p style='text-align: left; margin-left: 20px;'>" + message + "</p>"
                );
            }

            $('#enter-message').click(function () {
                var inputMessage = $('#input-message').val();
                if(inputMessage !== "") {
                    sendMessage(name, inputMessage, function (data) {
                    });
                    
                }
            });

            function sendMessage(name, message, success_callback = null) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('send-message') }}/" + name + "/" + message,
                    success: function (data) {
                        if(success_callback != null) {
                            success_callback(data);
                        }
                    },
                    error: function (data) {
                            alert("Gagal simpan data: " + data);
                    }
                });
            }
        });

        
    </script>
</body>
</html>