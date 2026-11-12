<?php
/**
 * Standalone token-cache regression tests. Run: php lib/feishuapi/tests/gettoken.php
 * No framework bootstrap, database, network access or Feishu credentials required.
 */
require dirname(__DIR__) . '/feishuapi.class.php';

function zget($object, $key, $default = '')
{
    return isset($object->$key) ? $object->$key : $default;
}

class commonModel
{
    public static $requestErrors = array();
}

class common
{
    public static $requests = array();

    public static function http($url, $data, $options, $headers)
    {
        self::$requests[] = array('url' => $url, 'data' => $data, 'headers' => $headers);
        if(strpos($url, 'auth/v3/tenant_access_token/internal/') !== false)
        {
            return json_encode(array('code' => 0, 'tenant_access_token' => 'fresh-token', 'expire' => 7200));
        }
        return json_encode(array('code' => 0));
    }
}

function check($condition, $message)
{
    if(!$condition) throw new Exception($message);
}

function cacheKey($appId = 'test-app', $appSecret = 'test-secret')
{
    return 'feishu' . md5($appId . $appSecret);
}

function cacheToken($token, $expires)
{
    global $config;
    $config->feishuTokens = array(cacheKey() => array($token, $expires));
}

$tests = array();
$tests['fetch and cache a token when the cache is missing'] = function()
{
    global $config;
    $before = time();
    $api = new feishuapi('test-app', 'test-secret');
    check(count(common::$requests) == 1, 'Expected one token request');
    check($config->feishuTokens[cacheKey()][0] === 'fresh-token', 'Token was not cached');
    check($config->feishuTokens[cacheKey()][1] >= $before + 7200, 'Expiry must be an absolute timestamp');
};
$tests['reuse an unexpired cached token without an HTTP request'] = function()
{
    cacheToken('valid-token', time() + 3600);
    $api = new feishuapi('test-app', 'test-secret');
    check($api->getToken() === 'valid-token', 'Expected the valid cached token');
    check(count(common::$requests) == 0, 'A valid cached token must not be refreshed');
};
$tests['replace an expired cached token'] = function()
{
    global $config;
    cacheToken('expired-token', time() - 3600);
    $api = new feishuapi('test-app', 'test-secret');
    check($api->getToken() === 'fresh-token', 'An expired token must not be reused');
    check(count(common::$requests) == 1, 'Expected exactly one refresh');
    check($config->feishuTokens[cacheKey()][0] === 'fresh-token', 'Refresh must replace the cached token');
};
$tests['refresh a token at its expiration timestamp'] = function()
{
    cacheToken('boundary-token', time());
    $api = new feishuapi('test-app', 'test-secret');
    check($api->getToken() === 'fresh-token', 'A token expiring now is no longer valid');
    check(count(common::$requests) == 1, 'Expected one boundary refresh');
};
$tests['fetch a token when the cached token is empty'] = function()
{
    cacheToken('', time() + 3600);
    $api = new feishuapi('test-app', 'test-secret');
    check($api->getToken() === 'fresh-token', 'An empty token must not be reused');
    check(count(common::$requests) == 1, 'Expected one token request');
};
$tests['reuse a freshly fetched token on repeated calls'] = function()
{
    $api = new feishuapi('test-app', 'test-secret');
    $api->getToken();
    $api->getToken();
    check(count(common::$requests) == 1, 'Repeated calls must reuse the fresh token');
};
$tests['share a valid cached token between client instances'] = function()
{
    $first = new feishuapi('test-app', 'test-secret');
    $second = new feishuapi('test-app', 'test-secret');
    check($second->getToken() === 'fresh-token', 'Expected the shared token');
    check(count(common::$requests) == 1, 'The second client must reuse the cached token');
};
$tests['keep token caches separate for different credentials'] = function()
{
    global $config;
    cacheToken('other-app-token', time() + 3600);
    $api = new feishuapi('another-app', 'another-secret');
    check($api->getToken() === 'fresh-token', 'Different credentials need their own token');
    check(count(common::$requests) == 1, 'Expected a token request for the other credentials');
    check($config->feishuTokens[cacheKey()][0] === 'other-app-token', 'The original cache must remain intact');
};
$tests['send with the refreshed token instead of an expired cached token'] = function()
{
    cacheToken('expired-token', time() - 3600);
    $api = new feishuapi('test-app', 'test-secret');
    $result = $api->send('test-open-id', json_encode(array('msg_type' => 'text')));
    check($result['result'] === 'success', 'Expected a successful stubbed send');
    check(count(common::$requests) == 2, 'Expected a token request followed by a send');
    check(strpos(common::$requests[1]['url'], 'message/v4/batch_send/') !== false, 'Expected the message endpoint');
    check(in_array('Authorization:Bearer fresh-token', common::$requests[1]['headers'], true), 'Send must use the fresh token');
};

echo '1..' . count($tests) . PHP_EOL;
$number = 0;
$failed = 0;
foreach($tests as $name => $test)
{
    $config = new stdClass();
    common::$requests = array();
    $number++;
    try
    {
        $test();
        echo "ok $number - $name\n";
    }
    catch(Exception $error)
    {
        $failed++;
        echo "not ok $number - $name: " . $error->getMessage() . PHP_EOL;
    }
}
exit($failed ? 1 : 0);
