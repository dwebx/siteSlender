<?php

// main config
require_once('./configs/config.php');

// init
require( DIR_INCLUDES . 'init' . FILE_EXT );

// set index
define ('INDEX', 1);

// header
@include( DIR_TEMPLATES . 'header' . FILE_EXT );

// page content
@include ( PAGE_ID . FILE_EXT );

// footer
@include( DIR_TEMPLATES . 'footer' . FILE_EXT );

?>