<?php

// Get the Slug
// ------------------------------------------
$subFolder = dirname($_SERVER['SCRIPT_NAME']) == '/' ? '' : dirname($_SERVER['SCRIPT_NAME']);
$link = trim( str_replace($subFolder, '', strtok($_SERVER['REQUEST_URI'], '?')), '/');

// Check if we need to do the initial settings
// ------------------------------------------
if (!file_exists('./data/config.php') && !file_exists('./data/config.cfg')) {
    header("Location: {$subFolder}/admin/");
    die();
}

// Get the Slug from the data file
// ------------------------------------------
$fileName = str_replace(['..', '/', '.'], '', $link);
$url = file_get_contents("./data/{$fileName}.url");

// Redirect the file
// ------------------------------------------
if($_SERVER['QUERY_STRING'] && strpos($url, '?')){
    list($url, $query) = explode('?', $url, 2);
    parse_str($query, $queryFile);
    parse_str($_SERVER['QUERY_STRING'], $queryPassed);
    $finalUrl = $url . '?' . http_build_query( array_merge($queryFile, $queryPassed) );
} elseif(!empty($_SERVER['QUERY_STRING'])) {
    $finalUrl = "{$url}?{$_SERVER['QUERY_STRING']}";
} else {
    $finalUrl = $url;
}
header("Location: {$finalUrl}");