# Python 2.7.11
#
# @category  Examples
# @package   Application
# @copyright 2016 Revcontent
# @license   http://www.revcontent.com Revcontent License
# @link      http://api.revcontent.io/docs/stats/index.html
#
import httplib
import json
import ConfigParser
from collections import OrderedDict

try:

    settings = ConfigParser.ConfigParser()
    settings.read('config.ini')
    client_id = settings.get('credentials', 'client_id')
    client_secret = settings.get('credentials', 'client_secret')
    boos_id = settings.get('info', 'boost_id')

    conn = httplib.HTTPSConnection("api.revcontent.io")
    payload = "grant_type=client_credentials&client_id="+client_id+"&client_secret="+client_secret
    headers = {
        'cache-control': "no-cache",
        'content-type': "application/x-www-form-urlencoded"
    }

    conn.request("POST", "/oauth/token", payload, headers)

    res = conn.getresponse()
    data = res.read()

    print 'ACCESS INFO'.center(100, '-')
    print(data.decode("utf-8"))

    data_dict = json.loads(data)
    if 'access_token' not in data_dict:
        raise ValueError('Not able to get access token', data.decode("utf-8"))

    print 'GET BOOSTS'.center(100, '-')
    headers = {
        'authorization': data_dict['token_type'] + " " + data_dict['access_token'],
        'content-type': "application/json",
        'cache-control': "no-cache"
    }

    conn.request("GET", "/stats/api/v1.0/boosts", headers=headers)

    res = conn.getresponse()
    data = res.read()

    print(data.decode("utf-8"))

    print 'CREATE BOOST'.center(100, '-')

    headers = {
        'authorization': data_dict['token_type'] + " " + data_dict['access_token'],
        'content-type': "application/json",
        'cache-control': "no-cache"
    }

    changes = OrderedDict([
        ("name", "testing"),
        ("country_targeting", "include"),
        ("country_codes", ["US", "PE"])
    ])

    conn.request('POST', '/stats/api/v1.0/boosts/add', json.dumps(changes, sort_keys=False), headers)
    res = conn.getresponse()
    data = res.read()
    print(data.decode("utf-8"))

    print 'UPDATE BOOST SETTINGS'.center(100, '-')
    changes = {
        'name': 'Rolando Test Update',
        'default_cpc': '0.51',
        'budget_type': 'daily',
        'budget_amount': '3000',
        'mobile_traffic': ["3", "4"],
        'language_traffic': ["1", "2", "3"]
    }

    headers = {
        'authorization': data_dict['token_type'] + " " + data_dict['access_token'],
        'content-type': "application/json",
        'cache-control': "no-cache"
    }

    conn.request("POST", "/stats/api/v1.0/boosts/"+boos_id+"/settings", json.dumps(changes), headers)
    res = conn.getresponse()
    data = res.read()
    print(data.decode("utf-8"))

except ValueError as be:
    message, raw_response = be.args
    print 'Error message: ', message
    print 'Raw response', raw_response
except BaseException as re:
    print 'Error: ', re
finally:
    print('Thank you. If you have any problems, contact your Representative.')
