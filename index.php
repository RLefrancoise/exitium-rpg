<!DOCTYPE html>
<?php
    require_once (__DIR__ . '/server/vendor/autoload.php');
    require_once (__DIR__ . '/server/bootstrap.php');
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Golden Eternity</title>
		<link rel="stylesheet" type="text/css" href="public/css/style.css" />

		<script>
			var socket = undefined;
		</script>
		<script type="text/javascript" src="public/js/jquery-1.11.1.js"></script>
		<script type="text/javascript" src="public/js/prototype.js"></script>

        <script type="text/javascript" src="public/js/client/Packets.js.php"></script>

		<script type="text/javascript" src="public/js/Key.js"></script>
		<script type="text/javascript" src="public/js/classes/MapEntity.js"></script>

		<script type="text/javascript" src="public/js/classes/Monster.js"></script>
		<script type="text/javascript" src="public/js/classes/Character2.js"></script>
		<script type="text/javascript" src="public/js/classes/Map3.js"></script>
		<script type="text/javascript" src="public/js/map.js"></script>
		<!--script type="text/javascript" src="/socket.io/socket.io.js"></script-->
	</head>
	<body>
		<div id="main_div">
			<div id="login_div" style="float:left"><label for="login_field">Login</label><input id="login_field" name="login_field" type="text"></div>
			<div id="login_button_block" style="float:right"><input id="login_button" type="submit" value="Log in"></div>
			<div id="logout_button_block" style="float:right;display:none;"><input id="logout_button" type="submit" value="Logout"></div>
			<div id="password_div"><label for="password_field">Password</label><input id="password_field" name="password_field" type="password"></div>

			<canvas id="canvas" width="640" height="480">Votre navigateur ne supporte pas HTML5, veuillez le mettre à jour pour jouer.</canvas>
			<div id="msgbox" name="msgbox"></div>
			<form>
				<input id="chatbox_input" type="text">
				<input id="chatbox_send_button" type="submit" value="Send" style="float:right">
			</form>
		</div>

		<script type="text/javascript">
			init_scene();

			jQuery('#login_button').click(function(event) {
				event.preventDefault();

				var login = jQuery('#login_field').val();
				var password = jQuery('#password_field').val();

				if(!login || !password) {
					return;
				} else{
                    new CM_LOGIN_REQUEST({
                        login: login,
                        password: password,
                    }, socket).send();
					//socket.emit('login', login, password);
					jQuery('#login_field').val('');
					jQuery('#password_field').val('');
					jQuery('#login_div').css('display', 'none');
					jQuery('#password_div').css('display', 'none');
					jQuery('#login_button_block').css('display', 'none');
					jQuery('#logout_button_block').css('display', 'block');
				}
			});

			jQuery('#logout_button').click(function(event) {
				event.preventDefault();
				//socket.emit('logout');
                socket.close();

                writeMessage('You have logged out.', 'rgb(255,128,0)');

                jQuery('#login_div').css('display', 'block');
                jQuery('#password_div').css('display', 'block');
                jQuery('#login_button_block').css('display', 'block');
                jQuery('#logout_button_block').css('display', 'none');

                map = null;
			});

			jQuery('#chatbox_send_button').click(function(event) {
				sendMessage();
				event.preventDefault();
			});

			//bind enter key to chatbox input to send message
			jQuery('#chatbox_input').keydown(function(event) {
				if(event.which == Key.Enter) {
					sendMessage();
					event.preventDefault();
				}
			});

			jQuery('#chatbox_input').focus(function(event) {
				canvas.receiveInput = false;
			});

			jQuery('#chatbox_input').blur(function(event) {
				canvas.receiveInput = true;
			});

			function sendMessage() {
				var msg = jQuery('#chatbox_input').val();
				console.log('send message : ' + msg);
				jQuery('#chatbox_input').val('');

                new CM_CHAT_MESSAGE({
                    msg: msg,
                }, socket).send();
				//socket.emit('chatbox_msg', msg);
			}

			function writeMessage(msg, color) {
				if(color == undefined) color = 'rgb(255,255,255)';

				var box = document.getElementById('msgbox');
				var text = '<span style="color:' + color + '">' + msg + '</span><br>';
				box.innerHTML += text;
				//box.scrollTop += 9999;
			}

            function initProtocol() {
                socket.onmessage = function(ev) {
                    var data = JSON.parse(ev.data);
                    console.log(data);
                    var packet = Packet.createFromType(data, socket);
                    packet.doAction();
                }
            }

			function init() {
				var serverBaseUrl = document.domain;

                socket = new WebSocket('ws://' + serverBaseUrl + ':8080');
                socket.onopen = function(ev) {
                    initProtocol();
                };

                socket.onerror = function(ev) {
                    console.log(JSON.stringify(ev, null, 4));
                }

                socket.onclose = function(ev) {
                    console.log(JSON.stringify(ev, null, 4));
                }

				/*socket = io.connect(serverBaseUrl, {
					'connect timeout': 10000,
					'reconnect': false,
					'force new connection':false
				});*/

				//var login = prompt('Login ?');
				//var password = prompt('Password ?');

				//socket.emit('login', login, password);

				var sessionId = '';

				/*socket.on('connect', function () {
					sessionId = socket.io.engine.id;
					console.log('Connection successful. Session id : ' + sessionId);
				});*/

				/*socket.on('connect', function() {
					writeMessage('Connected to the server.', 'rgb(255,128,0)');
				});

				socket.on('connecting', function() {
					writeMessage('Connecting to the server...', 'rgb(255,128,0)');
				});

				socket.on('connect_failed', function() {
					alert('Echec de la connexion avec le serveur.');
				});

				socket.on('logout_success', function() {
					writeMessage('You have logged out.', 'rgb(255,128,0)');

					jQuery('#login_div').css('display', 'block');
					jQuery('#password_div').css('display', 'block');
					jQuery('#login_button_block').css('display', 'block');
					jQuery('#logout_button_block').css('display', 'none');

					map = null;
				});

				socket.on('disconnect', function() {
					alert('Vous avez été déconnecté du serveur.');
				});

				socket.on('error', function() {
					alert('Une erreur est survenue.');
				});

				socket.on('server_msg', function(data){
					if(data.userName) data.msg = data.userName + ': ' + data.msg;
					writeMessage(data.msg, data.color);
				});

				socket.on('alertMsg', function(msg) {
					alert(msg);
				});

				//spawn player on map
				socket.on('self_spawn', function(data) {
					console.log("self spawn");
					self_spawn(data);
				});

				//a new player enters the map
				socket.on('spawn_player', function(data) {
					//writeMessage(data.name + ' joins in.');
					console.log('spawn player');
					spawn_player(data);
				});

				//a player exits the map
				socket.on('player_exits_map', function(p) {
					//var c = map.getCharacter(p.id);
					//writeMessage(c.playerData.name + ' has disconnected.');
					console.log('player exits map');
					map.removeEntity(p.id);
				});

				//a player is moving
				socket.on('player_move', function(data) {
					var x = data.x;
					var y = data.y;
					var dir = data.direction;

					var c = map.getEntity(data.id);

					if(c) {
						//check x coordinate, warp player if position is too far than the current position, else move player normally
						if(Math.abs(x - c.x) > 1) {
							c.x = x;
							c.direction = dir;
						} else {
							if(x < c.x) {
								c.deplacer(MapEntity.DIRECTION.LEFT, map);
							}
							if(x > c.x) {
								c.deplacer(MapEntity.DIRECTION.RIGHT, map);
							}
						}

						//check y coordinate, warp player if position is too far than the current position, else move player normally
						if(Math.abs(y - c.y) > 1) {
							c.y = y;
							c.direction = dir;
						} else {
							if(y < c.y) {
								c.deplacer(MapEntity.DIRECTION.UP, map);
							}
							if(y > c.y) {
								c.deplacer(MapEntity.DIRECTION.DOWN, map);
							}
						}
					}
					else writeMessage("Failed to get character with id: " + data.id, 'rgb(255,0,0)');
				});*/
			}

			jQuery(document).on('ready', init);
		</script>
	</body>
</html>
