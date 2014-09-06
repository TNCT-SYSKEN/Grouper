// Generated by CoffeeScript 1.7.1
$(function() {
  var channel, dispatcher, group_id;
  dispatcher = new WebSocketRails("ws://localhost:3000/websocket");
  group_id = $('#gid').text();
  channel = dispatcher.subscribe(group_id);
  return channel.bind("new_message", function(message) {
    return $(".talk").append("" + message.name + " 「" + message.talk + "」<br>");
  });
});