<?php

//



$cwd = getcwd();
$dir = "youtube";

if( !is_dir( $cwd . "/" . $dir ))
{
    mkdir( $dir );
}

if( is_dir( $cwd . "/" . $dir ))
{
    echo "the dir" . $cwd . "has been created, or was there allready";
}

