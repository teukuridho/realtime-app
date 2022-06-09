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
                Your name: <input style="margin-left: 10px;" id="input-name" type="text" placeholder="Your Name"/>
                <button id="enter-name">Enter</button>
            </div>

            <div class="row">
                <div class="column1">
                    <h4 id='connection-status'>Chat</h4>
                </div>
                <div class="column2">
                    <h4 id='connection-status'>Members</h4>
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
        var channel;

        $(function () {

            $("#input-name").prop("disabled", true);
            $("#enter-name").prop("disabled", true);

            $("#input-message").prop("disabled", true);
            $("#enter-message").prop("disabled", true);

            $('#enter-event').click(function () {
                $("#input-name").prop("disabled", false);
                $("#enter-name").prop("disabled", false);

                $("#input-event").prop("disabled", true);
                $("#enter-event").prop("disabled", true);
            });
        });
        
    </script>
</body>
</html>