$ ->
  dispatcher = new WebSocketRails("ws://localhost:3000/websocket")
  group_id = $('#gid').text()
  channel = dispatcher.subscribe(group_id)
  channel.bind "new_message", (message) ->
    $(".talk").append("#{message.name} 「#{message.talk}」<br>")
