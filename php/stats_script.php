<?php
/**
 * PHP version 5.6
 *
 * @category  Examples
 * @package   Application
 * @copyright 2016 Revcontent
 * @license   http://www.revcontent.com Revcontent License
 * @link      http://api.revcontent.io/docs/stats/index.html
 */
try {
    /**
     * @var string $client_id
     */
    $client_id = '<CLIENT_ID>';
    /**
     * @var string $client_secret
     */
    $client_secret = '<CLIENT_SECRET>';
    /**
     * @var integer $boost_id
     */
    $boost_id_to_update = <BOOST_ID>;

    /**
     * @param $input
     */
    $print_divider = function ($input) {
        echo str_pad($input, 100, "-", STR_PAD_BOTH), PHP_EOL;
    };

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

    $print_divider('ACCESS INFO');

    echo 'ACCESS TOKEN - RAW RESPONSE :: ', $raw_response, PHP_EOL;
    $access_info = json_decode($raw_response, true);
    if (!isset($access_info['access_token'])) {
        throw new Exception('Not able to get access. Raw response' . $raw_response);
    }

    $print_divider('GET ALL BOOSTS');

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
        throw new Exception('Not able to get boosts. Raw response ' . $raw_response);
    }

    if (!isset($response_array['success'])) {
        throw new Exception('Not able to get boosts. Raw response ' . $raw_response);
    }

    if (false === $response_array['success']) {
        throw new Exception('Not able to get boosts. Raw response ' . $raw_response);
    }

    if (count($response_array['data'])) {
        var_dump($response_array['data'][0]);
    } else {
        var_dump($response_array);
    }

    $print_divider('UPDATE BOOST SETTINGS');

    $changes = [
        "name" => "Update name",
        "default_cpc" => "0.99",
        "start_date_time" => "2016-05-14 00:00:00",
        "has_end_date" => "true",
        "end_date_time" => "2016-05-20 00:00:00",
        "mobile_traffic" => ["3","4"],
        "language_traffic" => ["1","2","3"]
    ];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.revcontent.io/stats/api/v1.0/boosts/{$boost_id_to_update}/settings",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($changes),
        CURLOPT_HTTPHEADER => array(
            "authorization: {$access_info['token_type']} {$access_info['access_token']}",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        var_dump($response);
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
