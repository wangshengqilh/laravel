var WebSocketServer = require('websocket').server;
var http = require('http');
var server = http.createServer(function(request, response) {
    console.log((new Date()) + ' Received request for ' + request.url);
    response.writeHead(404);
    response.end('hello');
});

server.listen(8080, function() {
    console.log((new Date()) + ' Server is listening on port 8080');
});
