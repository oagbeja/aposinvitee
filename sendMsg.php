<?php //include('acn.php');
$errormsg=$succmsg='';

function sendMessage($addUrl){
    $curl = curl_init();
    $url = messageUrl().$addUrl;
    // Set the URL and other options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Perform the request and get the response
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        echo 'Curl error: ' . curl_error($curl);
    } else {
        // Process the response
        echo 'Response: ' . $response;
    }

    // Close the cURL resource
    curl_close($curl);
}