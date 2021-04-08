<?php

$url = 'https://javascript-minifier.com/raw';
$js = file_get_contents('js/compiled.js');

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
    CURLOPT_POSTFIELDS => http_build_query([ "input" => $js ])
]);

$minified = curl_exec($ch);

curl_close($ch);

echo $minified;

echo "\n";

file_put_contents('js/app.min.js', $minified);
