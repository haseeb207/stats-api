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
error_reporting(E_ALL);
define('PATH',dirname(__FILE__));

require_once (PATH .'/classes/Psr4AutoloaderClass.php');
$loader = new FIG\PSR4\Psr4AutoloaderClass();
$loader->register();
$loader->addNamespace('Revcontent\Stats', PATH .'/classes');

$print_divider = function ($input) {
    echo str_pad($input, 100, "-", STR_PAD_BOTH), PHP_EOL;
};

try {
    $credentials = parse_ini_file(PATH .'/classes/config.ini');

    $boost = new Revcontent\Stats\Boosts();
    $boost->clientId = $credentials['client_id'];
    $boost->clientSecret = $credentials['client_secret'];

    $raw_response = $boost->getToken();
    $access_info = json_decode($boost->getToken(), true);
    if (!isset($access_info['access_token'])) {
        throw new Exception('Not able to get access. Raw response' . $raw_response);
    }

    $print_divider('ACCESS INFO');
    var_dump($access_info);

    //All boost no filters
    $raw_response = $boost->getAllBoosts($access_info);
    $response_array = json_decode($raw_response, true);
    if (!isset($response_array['success'])) {
        throw new Exception('Not able to get boosts. Raw response' . $raw_response);
    }

    if (false === $response_array['success']) {
        throw new Exception('Not able to get boosts. Raw response' . $raw_response);
    }

    $print_divider('GET ALL BOOSTS');

    echo 'Only the first boost', PHP_EOL;
    if (count($response_array['data'])) {
       var_dump($response_array['data'][0]);
    } else {
       var_dump($response_array);
    }

    $print_divider('GET ALL BOOSTS WITH FILTERS');

    $filters = [
        'targeting_type' => 'topic',
        'limit' => 1,
        'offset' => 0,
    ];

    $raw_response = $boost->getAllBoosts($access_info, $filters);
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

    var_dump($response_array['data']);

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

    $raw_response = $boost->updateBoostSettings($access_info, $credentials['boost_id'], $changes);
    var_dump($raw_response);

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
