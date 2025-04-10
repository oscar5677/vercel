<?php
// Set CORS headers to allow access from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400"); // Cache preflight requests for 1 day

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

// Your existing script below
$get = $_GET['get'];
$mpdUrl = 'https://linearjitp-playback.astro.com.my/dash-wv/linear/' . $get;

$mpdheads = [
    'http' => [
       // 'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36\r\n",
     'header' => "User-Agent: Mozilla/5.0 (Linux; Android 10; MI 9 Build/QKQ1.190825.002; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/111.0.5563.58 Mobile Safari/537.36",

        'follow_location' => 1,
        'timeout' => 5
    ]
];
$context = stream_context_create($mpdheads);
$res = @file_get_contents($mpdUrl, false, $context);

if ($res === false) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "Error: Unable to fetch content from the source.";
    exit();
}

header("Content-Type: application/dash+xml");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

echo $res;
?>
