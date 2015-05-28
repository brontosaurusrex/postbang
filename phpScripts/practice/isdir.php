<?php

// is dir

$cwd = getcwd() . "/";

if ( is_dir( "test/12" ))
{
    echo "it is a dir";
}
else
{
    echo "not a dir";
}
