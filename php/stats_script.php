<?php
try {
    $client_id = '<CLIENT_ID>';
    $client_secret = '<CLIENT_SECRET>';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.revcontent.io/oauth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id={$client_id}&client_secret={$client_secret}",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $raw_response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        throw new Exception("ACCESS TOKEN - cURL Error :: " . $err . ' Raw response :: '. $raw_response);
    }

    echo 'ACCESS TOKEN - RAW RESPONSE :: ', $raw_response, PHP_EOL;
    $access_info = json_decode($raw_response, true);
    if (!isset($access_info['access_token'])) {
        throw new Exception('Not able to get access. Raw response' . $raw_response);
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.revcontent.io/stats/api/v1.0/boosts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: {$access_info['token_type']} {$access_info['access_token']}",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $raw_response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        throw new Exception("GET ALL BOOSTS - cURL Error :: " . $err . ' Raw response :: '. $raw_response);
    }

    $response_array = json_decode($raw_response, true);

    if (isset($response_array['errors'])) {
        throw new Exception('Not able to get boosts - filters. Raw response ' . $raw_response);
    }

    if (!isset($response_array['success'])) {
        throw new Exception('Not able to get boosts - filters. Raw response ' . $raw_response);
    }

    if (false === $response_array['success']) {
        throw new Exception('Not able to get boosts - filters. Raw response ' . $raw_response);
    }

    if (count($response_array['data'])) {
        var_dump($response_array['data'][0]);
    } else {
        var_dump($response_array);
    }

} catch (Exception $e) {
    $datetime = new DateTime();
    $datetime->setTimezone(new DateTimeZone('UTC'));
    $logEntry =
        $datetime->format('Y/m/d H:i:s') . '   ' .
        PHP_EOL . 'Message: ' . $e->getMessage() .
        PHP_EOL . 'Code: ' . $e->getCode() .
        PHP_EOL . 'File: ' . $e->getFile() .
        PHP_EOL . 'Line: ' . $e->getLine() . PHP_EOL;

    //echo message in console
    echo sprintf($logEntry), PHP_EOL;
    // log to default error_log destination
    error_log($logEntry);
} finally {
    echo PHP_EOL,PHP_EOL,'Thank you. If you have any problems, contact your Representative.', PHP_EOL;
}