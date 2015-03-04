<?php 
setcookie("autoadvance", "true", time()+8640000,"/");
setcookie("repeat", "false", time()+8640000,"/");
header('Location: ../?'.$_SERVER['QUERY_STRING']);
?>
<html>
<head>
<title>HTML Redirection</title>

	  
	  <style type="text/css">
body {
font: 12px sans-serif;
margin: 20px;
  color: #fff;
  background-color: #181818;
}
</style>
</head>
<body>
loading...
</body>
</html>