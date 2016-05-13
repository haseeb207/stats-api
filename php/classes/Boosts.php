<?php
/**
 * PHP version 5.6
 *
 * LICENSE: This source file is closed source, strictly confidential and
 * proprietary to Revcontent. Viewing the contents of this file binds the
 * viewer to the NDA agreement  available by Integraclick Inc. Electronic
 * transfer of this file outside of the Integraclick corporate network is
 * strictly prohibited. Questions, comments or concerns should be directed to
 * compliance@revcontent.com
 *
 * @category  Examples
 * @package   Application
 * @copyright 2016 Revcontente
 * @license   http://www.revcontent.com Revcontent License
 * @link      http://api.revcontent.io/docs/stats/index.html
 */
namespace Revcontent\Stats;

class Boosts
{
    /**
     * @const string Stats API URL
     */
    const URL = 'https://api.revcontent.io';

    /**
     * @const string Client ID
     */
    public $clientId;

    /**
     * @var string Client Secret
     */
    public $clientSecret;

    /**
     * @return mixed
     * @throws \Exception
     * @see http://api.revcontent.io/docs/stats/index.html#api-Access-GetOauthAccess
     */
    function getToken()
    {
        $curl = new Curl();
        return $curl->post(self::URL . '/oauth/token',
            [
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ],
            'grant_type=client_credentials&client_id=' . $this->clientId. '&client_secret=' . $this->clientSecret
        );
    }

    /**
     * @param array $access_info
     * @param array $filters
     * @return mixed
     * @throws \Exception
     * @see http://api.revcontent.io/docs/stats/index.html#api-Boosts-GetAllBoosts
     */
    function getAllBoosts(array $access_info, array $filters = [])
    {
        $curl = new Curl();

        $url = self::URL . "/stats/api/v1.0/boosts";
        if (count($filters)) {
            $url .= '?';
            foreach ($filters as $parameter => $value) {
                $url .= $parameter . '=' . $value . '&';
            }

            $url = rtrim($url, '&');
        }

        return $curl->get($url, [
            "authorization: {$access_info['token_type']} {$access_info['access_token']}",
            "cache-control: no-cache",
            "content-type: application/json"]
        );
    }
}

