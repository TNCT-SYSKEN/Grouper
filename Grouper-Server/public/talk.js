// Generated by CoffeeScript 1.7.1
$(function() {
  var channel, dispatcher, group_id;
  dispatcher = new WebSocketRails("ws://localhost:3000/websocket");
  group_id = $('#gid').text();
  channel = dispatcher.subscribe(group_id);
  return channel.bind("new_message", function(message) {
    // ユーザ画像が帰ってくるようにしたい
    return $(".talk_tbody").append('<tr class="talk_user_name"><th colspan="3">' + message.name + '</tr><tr class="talk_body"><th class="user"><p></p></th><th class="content"><p>' + message.talk + '</p></th></tr>');
  });
});
