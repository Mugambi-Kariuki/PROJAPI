<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Chat with Agent</h2>
    <div id="chatBox" class="border p-3" style="height: 300px; overflow-y: scroll;"></div>
    <input type="text" id="messageInput" class="form-control mt-2" placeholder="Type your message">
    <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let receiverId = 2;  // Example agent ID, can be dynamic

function fetchMessages() {
    $.ajax({
        url: "fetch_messages.php",
        method: "GET",
        data: { receiver_id: receiverId },
        success: function(data) {
            let messages = JSON.parse(data);
            $("#chatBox").empty();
            messages.forEach(msg => {
                $("#chatBox").append(`<p><strong>${msg.sender_id == receiverId ? 'Agent' : 'You'}:</strong> ${msg.message}</p>`);
            });
        }
    });
}

function sendMessage() {
    let message = $("#messageInput").val();
    if (message.trim() === "") return;

    $.ajax({
        url: "send_message.php",
        method: "POST",
        data: { receiver_id: receiverId, message: message },
        success: function() {
            $("#messageInput").val("");
            fetchMessages();
        }
    });
}

setInterval(fetchMessages, 3000);  // Refresh chat every 3 seconds
fetchMessages();
</script>
</body>
</html>
