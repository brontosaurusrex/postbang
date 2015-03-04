		<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		   "http://www.w3.org/TR/html4/loose.dtd"> 
		<html> 
		  <head> 
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
			<title>      player docs		</title>
			   

			   
			   
			   </head>
			   <body>



<?php
//navigation inline
foreach (glob("*.txt") as $filename) {

    $filestrip = substr($filename,3);
    echo "<a href=\"#$filestrip\">$filestrip</a>  ";
    
    // <a href="#C4">See also Chapter 4</a>
}

echo "<br><br><br>";

//include all text files
foreach (glob("*.txt") as $filename) {

    $filestrip = substr($filename,3);
    echo "<a name=\"$filestrip\">$filestrip</a><br>";
    

    
  // <a name="C4">Chapter 4</a>
    
    
     $includeFile = $filename;
			if (file_exists($includeFile) && is_readable($includeFile)) {
			echo "<pre>";
			include($includeFile);
			echo "</pre>";
			}
    
    
      echo "<br><br>";
    
}
?>



		  </body>
		</html>
