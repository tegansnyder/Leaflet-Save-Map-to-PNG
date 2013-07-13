<?php

header('Access-Control-Max-Age:' . 5 * 60 * 1000);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Request-Method: *');
header('Access-Control-Allow-Methods: OPTIONS, GET');
header('Access-Control-Allow-Headers *');
header("Content-Type: application/javascript");

// Url params
$url = $_GET['url'];
$callback = $_GET['callback'];

// Retrieve file details
$file_details = get_url_details($url, 1, $callback);

if (!in_array($file_details["mime_type"], array("image/jpg", "image/png")))
{
    print "error:Application error";
} else
{
    $re_encoded_image = sprintf(
            'data:%s;base64,%s', $file_details["mime_type"], base64_encode($file_details["data"])
    );

    print "{$callback}(" . json_encode($re_encoded_image) . ")";
}

function get_url_details($url, $attempt = 1, $callback = "")
{
    $pathinfo = pathinfo($url);

    $max_attempts = 10;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);
    //curl_setopt($ch, CURLOPT_PROXY, 'username:password@host:port');
    $data = curl_exec($ch);
    $error = curl_error($ch);

    $mime_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if (!in_array($mime_type, array("image/jpg", "image/png")) && $max_attempts != $attempt)
    {
        return get_url_details($url, $attempt++, $callback);
    }

    return array(
        "pathinfo" => $pathinfo,
        "error" => $error,
        "data" => $data,
        "mime_type" => $mime_type
    );
}
