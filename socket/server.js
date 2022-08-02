var io = require('socket.io');
var http = require('http');
var moment = require('moment-timezone');
moment.locale('ru');
moment.tz.setDefault("Europe/Moscow");
var app = http .createServer();
var io = io.listen(app);

app.listen(8888);

io.sockets.on('connection', function (socket) {

	// Обновление FT TABLE смена приоритета
	socket.on('eventPriority', function (data) {
		io.sockets.emit('reloadFrTable', data);
		io.sockets.emit('reloadFrTableUsers', data);
	});

	// Обновление DASHBORD при обновлении статусов в FR TABLE
	socket.on('eventChangeStatusFRTABLE', function (data) {
		io.sockets.emit('reloadDASHBORD', data);
 	});




	socket.on('disconnect', function () {
		//console.log('user disconnected');
	});




});
