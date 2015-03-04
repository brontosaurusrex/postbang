<?php 
// unset cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
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


