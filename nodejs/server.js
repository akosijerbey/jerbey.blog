var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var redis = require('redis');

 
server.listen(8890);
io.on('connection', function (socket) {
  console.log("client connected");
  socket.join('some room');
  var redisClient = redis.createClient();
      redisClient.subscribe('message');
      redisClient.on("message", function(channel, data) {
        console.log("new message add in queue "+ data + " channel");
        console.log(channel);
        socket.emit(channel, data);
      });
      
  
  socket.on('notifyUser', function(user){
    io.emit('notifyUser', user);
  });

  socket.on('disconnect', function() {
    console.log("client disconnected");
    redisClient.quit();
  });

}); 