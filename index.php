<!doctype html>
<html>
<head>
<title>Connect 4 - The Game</title>
<style type="text/css">
p{ margin-top:40px; }
p.message{font-size:1.5em; text-transform:uppercase; }
table { border-left:1px solid #444; border-top:1px solid #444; border-collapse:collapse; }
table td{
	font-family:arial;
	font-size:12px;
	width:20px;
	height:20px;
	vertical-align:middle;
	text-align:center;
	border-right:1px solid #444;
	border-bottom:1px solid #444;
	text-indent:-9999px;
}
table td.player-1{background:red; }
table td.player-2{background:blue; }
</style>
</head>

<body>
<h2>Connect 4</h2>
Player 1 - Red <br/>
Player 2 - Blue <br/>
<?php
//Load our file
require 'ConnectFour.php';
//Instantiate our game
$new_game = new ConnectFour(); 
?>
</body>
</html>
