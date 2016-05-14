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
namespace Revcontent\Stats;

class Curl
{
    const DEFAULT_TIMEOUT = 30;
    const DEFAULT_MAXREDIRS = 10;

    /**
     * cURL GET
     *
     * @param string $url
     * @param array $headers
     * @return string
     * @throws \Exception
     */
    public function get($url, array $headers)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => self::DEFAULT_MAXREDIRS,
            CURLOPT_TIMEOUT => self::DEFAULT_TIMEOUT,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);
        $error_msg = curl_error($curl);
        $error_num = curl_errno($curl);

        curl_close($curl);

        if ($error_msg) {
            throw new \Exception('cURL GET Error ' . $error_msg, curl_errno($error_num));
        }

        return $response;
    }

    /**
     * cURL POST
     *
     * @param string $url
     * @param array $headers
     * @param string $post_fields
     * @return string
     * @throws \Exception
     */
    public function post($url, array $headers, $post_fields)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => self::DEFAULT_MAXREDIRS,
            CURLOPT_TIMEOUT => self::DEFAULT_TIMEOUT,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_fields,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception('cURL Error ' . $err);
        }

        return $response;
    }
}
