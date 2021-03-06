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
     * Get Oauth Access Token
     *
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
     * Get all boosts
     *
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

    /**
     * Update boost settings
     *
     * @param array $access_info
     * @param $id
     * @param array $post_fields
     * @return string
     * @throws \Exception
     * @see http://api.revcontent.io/docs/stats/index.html#api-Boosts-PostBoostSettings
     */
    function updateBoostSettings(array $access_info, $id, array $post_fields)
    {
        $curl = new Curl();

        return $curl->post(self::URL . '/stats/api/v1.0/boosts/' . $id . '/settings',
            [
                "authorization: {$access_info['token_type']} {$access_info['access_token']}",
                "cache-control: no-cache",
                "content-type: application/json",
            ],
            json_encode($post_fields)
        );
    }
}
