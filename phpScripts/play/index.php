<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>
            <?php
            /* start timings */
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $starttime = $mtime;

//ini_set('display_errors', 1);
            error_reporting(0);

            $flvplayer_ver = 'play45couch';

            if (!$_GET['moviename']) {
                echo $flvplayer_ver;
            } else {

                // strrchr($_GET['moviename'],'/');   - return only the string after last slash
                //substr("abcdef", 0, -1);
                $moviepart = substr(strrchr($_GET['moviename'], '/'), 1);
                echo 'play '.$moviepart;
            }
            ?>
        </title>
        <script type="text/javascript" src="swfobject.js"></script>



        <?php
            // if autoadvance cookie is set to true, then load autoadvance javascript

            if (isset($_COOKIE["autoadvance"])) {
                if ($_COOKIE["autoadvance"] == "true") {
                    echo " <script type=\"text/javascript\" src=\"autoadvance.js\"></script> ";
                }
            }
        ?>
           
            <link rel="stylesheet" type="text/css" href="flvplayer.css">
		
		<?php		
		if ((isset($_COOKIE["couch"])) && ($_COOKIE['couch']=="true")) {	
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"couch.css\">" ;
		}
		?>
		
		
        </head>
        <body>    
		

		
        <?php
            //echo php_uname()."<br>";
            //echo PHP_OS;



            if (isset($_GET['moviename'])) {
                $moviename = $_GET['moviename'];
            } else {
                $moviename = 'movies/';
            }
            if (isset($_GET['filter'])) {
                $filter = $_GET['filter'];


                //trim Strip whitespace (or other characters) from the beginning and end of a string
                $filter = trim($filter);

                //call IsRegExp function to trap regex error and if there is one, then unset the $filter
                if (IsRegExp('/' . $filter . '/i') != 1) {
                    unset($filter);
                }
            }
            //note: this $linkage would be probably easier solved with $_SERVER['QUERY_STRING']
            if (empty($filter)) {
                $linkage = '?moviename=movies/';
            } else {
                $linkage = '?filter=' . $filter . '&moviename=movies/';
                //echo $filter;
            }

	//echo $linkage."<br>";
	//echo $_SERVER['QUERY_STRING'];




            function dirlist($dir, $filter) {



                foreach (scandir($dir) as $entry)
                //	if($entry != '.' && $entry != '..' && $entry != '.htaccess')
                    if (substr($entry, 0, 1) != '.') {

                        //get file owner if on posix compatible system and store that to $user variable
                        //the user field is also used by the filter
                        if (PHP_OS != 'WINNT') {
                            $fileowneruid = fileowner("movies/" . $entry);
                            $fileownerarray = posix_getpwuid($fileowneruid);
                            $user = $fileownerarray['name'];
                        } else {
                            $user = "";
                        }
                        //echo $user;

                        if ((!empty($filter) && (preg_match('/' . $filter . '/i', $entry)) || (preg_match('/' . $filter . '/i', $user) == 1)) || (empty($filter))) {
                            $entry = $dir . '/' . $entry;
                            if (is_dir($entry)) {
                          // if entry is folder, do nothing, not interested in subfolders
                            } else {
                                $path = pathinfo($entry);

                                /* if array key allready exist (the mtime, then do enlarge the value by one)
                                  . this is a cheap way to avoid colisions when fils have same mtime and at the same time
                                  . stay with the simple key -> value pricip
                                 */

                                $varmtime = filemtime($entry);
                                while (array_key_exists($varmtime, $listarray)):
                                    $varmtime++;
                                endwhile;

                                //echo  $varmtime.'<br>';

                                $listarray[$varmtime] = $path['basename'];
                            }
                        }
                    }

      

               
                    if ($_COOKIE["sortdate"] == "false") {
					//nothing to do
					} else {
                        krsort($listarray);
						
                      
                    }
                
				

                return($listarray);
            }

// this next function should be able to trap preg-match errors, will return 1 if there were no errors
// found that at http://weblogtoolscollection.com/regex/regex.php
            Function IsRegExp($sREGEXP) {
                $sPREVIOUSHANDLER = Set_Error_Handler("TrapError");
                Preg_Match($sREGEXP, "");
                Restore_Error_Handler($sPREVIOUSHANDLER);
                Return!TrapError ();
            }

            Function TrapError() {
                Static $iERRORES;

                If (!Func_Num_Args ()) {
                    $iRETORNO = $iERRORES;
                    $iERRORES = 0;
                    Return $iRETORNO;
                } Else {
                    $iERRORES++;
                }
            }

            


            echo "";

            function xres($f) {

                /* pozicije stringov x, y in pike */
                $xpos = strripos($f, "x");
                $ypos = strripos($f, "y");
                $dotpos = strripos($f, ".");
                /* dolzine stringov med pozicijami */
                $xlen = $ypos - $xpos;
                $ylen = $dotpos - $ypos;
                /* resolucije, x mora biti pred y-onom v stringu, drugace bo poclo */
                $xres = substr($f, $xpos + 1, $xlen - 1);
                $yres = substr($f, $ypos + 1, $ylen - 1);
                return $xres;
            }

            function yres($f) {
                /* pozicije stringov x, y in pike */
                $xpos = strripos($f, "x");
                $ypos = strripos($f, "y");
                $dotpos = strripos($f, ".");
                /* dolzine stringov med pozicijami */
                $xlen = $ypos - $xpos;
                $ylen = $dotpos - $ypos;
                /* resolucije, x mora biti pred y-onom v stringu, drugace bo poclo */
                $xres = substr($f, $xpos + 1, $xlen - 1);
                $yres = substr($f, $ypos + 1, $ylen - 1);
                return $yres;
            }

            /* stara metoda za ugotovitev resolucije za npr filename_576_320 */

            function strip_ext_last3($f) {
                return substr(substr($f, 0, strrpos($f, '.')), -3);
            }

            function strip_ext_last7($f) {
                return substr(substr($f, 0, strrpos($f, '.')), -7, 3);
            }

            /* ce variabla moviename ni enaka null ali je enaka movies/ potem izrisi flash player, ce ne pa inkludaj notes.htm in thumbe  */
            if ((!$_GET['moviename']) || ($_GET['moviename'] == 'movies/')) {
                /* general notes */
                $includeFile = 'notes.htm';
                if (file_exists($includeFile) && is_readable($includeFile)) {
                    include($includeFile);
                }

                // thumbi, morajo biti jpg
                if (file_exists('./thumbs') && is_readable('./thumbs')) {
                    
                    echo "<div id=thumbs>";

                    $filez = dirlist('./movies', $filter);
					/*
					echo "<pre>";
					print_r($filez);
                    echo "</pre>";*/
					
					// PAGES new
					$files = dirlist('./movies', $filter);
					if (isset($files)) {
					$countarray = count($files);
					}
					
					$maxthumbs = 28;
					$page = $_GET['page'];
					if (isset ($page)){
						if (($page * $maxthumbs) > $countarray) {
						$page = 0; }
					}
					
					if (isset ($page)){
					$sliceStart = $page * $maxthumbs;
					} else { 
					$sliceStart = 0 ; }
					
					
					
					
					// start display next, prev page navigation
										
					/*echo "linkage = ".$linkage;
					echo ", slice start = ".$sliceStart;
					echo ", allFiles = ".$countarray;
					echo ", page = ".$page;
					echo "<br><br>";*/
					
	if ($countarray > $maxthumbs) {
					
					echo "<div id=\"naprejnazaj\"> ";
					if ($page > 0){
					echo "<a href=\"".$linkage."&page=".($page - 1)."\">&lt; </a>";
					} else {
					echo "&lt; ";
					}

					if ((($page + 1) * $maxthumbs) < $countarray) {
					echo "<a href=\"".$linkage."&page=".($page + 1)."\">&gt;</a>";
					} else {
					echo "&gt;";
					}
					
					echo "</div>";
					
					
					
					
					
					
					
					
					
					
					
					// slice counter logic
					/*
					if (($sliceStart + $maxthumbs) < $countarray) {
					echo " ".$sliceStart."-".($sliceStart + $maxthumbs);
					} else {
					echo " ".$sliceStart."-".$countarray;
					
					} */
					
			
					
					// add page to linkage
					
					
	}
	echo "<br><br><br><br>";
					// end display next, prev page navigation
					
					$filez = array_slice($filez, $sliceStart, $maxthumbs);  
					
					
					if (isset($filez)) {
                        foreach ($filez as $file) {
                            $file2 = "$file.jpg";

			

                            if (PHP_OS != 'WINNT') {
                                $fileowneruid = fileowner("movies/" . $file);
                                $fileownerarray = posix_getpwuid($fileowneruid);
                                $user = $fileownerarray['name'];
                            } else {
                                $user = "";
                            }

                            // see if there is a thumb for specific file
                            if ((file_exists('thumbs/' . $file2)) || (file_exists('thumbs/' . "$file.error"))) {
                                if (PHP_OS != 'WINNT') {
                                    $title = $file . ", " . $user;
                                } else {
                                    $title = $file;
                                }
                                // if thumb is normal or an error different display is used
                                if (file_exists('thumbs/' . $file2)) {
                                    echo "<a href=\"" . $linkage . $file . "\"><img src=\"thumbs/$file2\" title=\"$title\" alt=\"$title\"></a>";
                                } else if (file_exists('thumbs/' . "$file.error")) {
                                    echo "<a href=\"" . $linkage . $file . "\"><img src=\"error/error.png\" title=\"$title\" alt=\"$title\"></a>";
                                }
                            } else {


                                //generate thumbnails
                                //no errors are logged curently, so it will always try to regenerate....
                                $fileext = substr($file, strripos($file, '.'), strlen($file));

# linux /usr/local/bin/makethumb

                                if ((file_exists(exec("which makethumb")))) {
                                    exec("makethumb movies/$file", $output, $state);
                                    if ($state == 1) {
                                        touch("movies/$file.error");
                                        rename("movies/$file.error", "thumbs/$file.error");
                                    }
                                    //echo $file."-".$state;
                                }


# windows s:/makethumb_NG/makethumb_NG.bat - WARNING, this is still old code, not really uptodate with the linux example up there
                                if ((file_exists("s:/makethumb_NG/makethumb_NG.bat")) && (($fileext == '.mp4') || ($fileext == '.flv') | ($fileext == '.mov'))) {
                                    exec("s:/makethumb_NG/makethumb_NG.bat movies/$file", $output);
                                }




                                if (file_exists("movies/$file.jpg")) {
                                    rename("movies/$file.jpg", "thumbs/$file.jpg");
                                }

                                // see if thumb was made maybe
                                if (file_exists('thumbs/' . $file2)) {
                                    echo "<a href=\"" . $linkage . $file . "\"><img src=\"thumbs/$file2\" title=\"$file\"></a>";
                                }
                            }
                        }
                        echo "</div>";
						
						

						$agent = getenv("HTTP_USER_AGENT");
						if (preg_match("/MSIE/i", $agent)) {
						echo "Warning: you are using Microsoft Internet Explorer, which is no longer supported by this player app.<br><br><br>";
						}

						
						
                    }
                }
            } else 
					
// player include

					
			$includeFile = 'player.htm';
            if (file_exists($includeFile) && is_readable($includeFile)) {

                include($includeFile);
				
			} 
			
			
//search form
            echo "<div id=\"navigation\">";

            if (!$filter) {
                $search = '';
            } else {
                $search = $filter;
            }
            echo "
					<form name=\"filter\" action=\"./\" method=\"get\" title=\"filter\">
					<input id=filter type=\"text\" name=\"filter\" value=\"" . $search . "\">
					</form>
					";




            //pulldown navigation
            echo "<form action=\"\">";

            echo "<div id=\"stop\">";
            //<a href=\"".$linkage."\" id=\"big\" title=\"root\">::</a>";
            echo "<a href=\"" . $linkage . "\"> stop </a>";
			
            echo "</div>";

            echo "<select id=\"pull\" name=\"yourfiles\" title=\"select\" onchange=\"document.location.href = '" . $linkage . "' + this.value\">";
            /* display play or stop depending if the movie is null or not */
            if ((!$_GET['moviename']) || ($_GET['moviename'] == 'movies/')) {
                echo "<option value=\"\">play</option>";
            } else {
                //echo "<option value=\"\">stop</option>";
            }




            $files = dirlist('./movies', $filter); 

            if (isset($files)) {
               $countarray = count($files);    





                foreach ($files as $file) {


                    if ($moviename == "movies/" . $file) {
                        echo "<option value=\"\">stop</option>";
                        echo "<option value=\"$file\"";
                        echo " selected style=\"color:#7777ff;\"";
                        echo ">";
                        echo $file . "</option> \n";
                    } else {
                        echo "<option value=\"$file\"";
                        echo ">";
                        echo $file . "</option> \n";
                    }
                }
            } else {

                echo "<option value=\"\">nothing was found</option> ";
            }
            if (isset($countarray)) {
                echo "<option value=\"\" style=\"color:#00ff00;\">files: " . $countarray . "</option>";
            }
            //Close Select
            echo "</select>";
            echo "</form>";



            // <<previous 	this		next>>   array
            // http://www.w3schools.com/PHP/func_array_next.asp

            if (isset($files)) {

                reset($files);

                foreach ($files as $file) {

                    if ($moviename == "movies/" . $file) {

                        $current = prev($files);
                        $next = next($files);
                        prev($files);
                        prev($files);
                        $previous = current($files);
                        if ((end($files)) == $file) {

                            $current = end($files);
                            prev($files);
                            $previous = current($files);
                        }

                        // ?moviename=movies/' + this.value
                        // echo $current;
                        echo "<div id=\"naprejnazaj\"> ";

                        if (!empty($previous)) {
                            echo "<a href=\"" . $linkage . $previous . "\">&lt;</a> ";
                        } else {
                            echo "&lt; ";
                        }



                        //if (!empty($next)) { echo " ||||||-> ".$next; }
                        if (!empty($next)) {
                            echo "<a href=\"" . $linkage . $next . "\">&gt;</a>";
                        } else {
                            echo "&gt; ";
                        }



                        echo "</div>";

                        echo "</div> <!-- end of div navigation -->";  // end of div with id=navigation

						// this is a php to javascript variable dump, which will be used when
						// autoadvance.js is set to true
						
						// also when repeatAll cookie is set, the next must become 1st when array
						// finnishes
						
				if (isset($_COOKIE["repeatAll"])) {
                if ($_COOKIE["repeatAll"] == "true") {
                
                		if (empty($next)) {
						$tmp = array_slice($files, 0, 1);
						foreach ($tmp as $muki) {
						
						$next = $muki;
						
						}
						}
                    
                }
            }
						
						

                        echo " <script type=\"text/javascript\"> ";
                        echo " var next = \"" . $linkage . $next . "\";";
                        echo " </script> ";

                        
                    }
                }
            } 
			echo "</div> <!-- end of div navigation -->";  // end of div with id=navigation

            // better way would be (evulish)
            /*
              $i = array_search($movie, $files);
              if ($i > 0) { echo "prev: ".$files[$i-1]; } echo $files[$i];
              if ($i+1 >= count($files)) { echo "next: ".$files[$i+1]; }
             */


            /* movie related notes * must be in directory notes/ with filename = moviename.ext.txt */
            $includeFile = 'notes/' . $moviepart . '.txt';
            if (file_exists($includeFile) && is_readable($includeFile)) {
                echo "<div id=\"movie_notes\">";
                include($includeFile);
                echo "</div>";
            }

			
			// check for ie
			
			function ae_detect_ie()
			{
			if (isset($_SERVER['HTTP_USER_AGENT']) && 
			(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
			return true;
				else
			return false;
			}

			
			

			if ((!isset($_COOKIE["couch"])) || ($_COOKIE['couch']=="false")) {
			if (ae_detect_ie() == false) {
			
			/* main footer */
			
			
			
            $includeFile = '../nav.htm';
            if (file_exists($includeFile) && is_readable($includeFile)) {

                include($includeFile);
            }
			
			} else {
			
			//echo "<br><br><br>nag: Internet explorer is not entirely supported/tested";
			
			}
			}
			
            echo "<div id=\"notes_bottom_left\">";
            $mtime = microtime();
            $mtime = explode(" ", $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;
            $totaltime = round(($endtime - $starttime), 3);
            echo "php done<br>in " . $totaltime . "<br>seconds";
            echo "</div>";
        ?>



        <!-- Piwik -->
        <script type="text/javascript">
            var pkBaseURL = (("https:" == document.location.protocol) ? "https://kresnica/piwik/" : "http://kresnica/piwik/");
            document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
        </script><script type="text/javascript">
            try {
                var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
                piwikTracker.trackPageView();
                piwikTracker.enableLinkTracking();
            } catch( err ) {}
        </script><noscript><p><img src="http://kresnica/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
        <!-- End Piwik Tracking Tag -->



    </body>
</html>



