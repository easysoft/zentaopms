#!/usr/bin/env php
<?php

/**

title=测试 ciModel::sendRequest();
timeout=0
cid=15591

- 步骤1：正常情况 @1
- 步骤2：空数据 @1
- 步骤3：无效URL @0
- 步骤4：错误认证 @0
- 步骤5：PARAM_TAG处理 @1
- 步骤6：PARAM_TAG置空REVISION @v1.0.0-

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$ci = new ciModelTest();

/* 启动本地重定向服务，避免用例依赖外部网络。 */
$port   = 0;
$router = tempnam(sys_get_temp_dir(), 'ci_router_') . '.php';
file_put_contents($router, <<<'router'
<?php
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if($path === '/redirect')
{
    header('Location: http://' . $host . '/item/123/');
    http_response_code(302);
    exit;
}
if($path === '/params')
{
    $tag      = isset($_GET['PARAM_TAG']) ? $_GET['PARAM_TAG'] : '';
    $revision = isset($_GET['PARAM_REVISION']) ? $_GET['PARAM_REVISION'] : '';
    header('Location: http://' . $host . '/item/' . $tag . '-' . $revision . '/');
    http_response_code(302);
    exit;
}
if($path === '/basic-auth')
{
    if(isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] === 'jenkins' && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] === 'secret')
    {
        header('Location: http://' . $host . '/item/jenkins/');
        http_response_code(302);
        exit;
    }

    header('WWW-Authenticate: Basic realm="ci"');
    http_response_code(401);
    echo 'Unauthorized';
    exit;
}
if(strpos($path, '/item/') === 0)
{
    echo 'OK';
    exit;
}

http_response_code(404);
echo 'Not Found';
router);

$process = null;
if(function_exists('proc_open'))
{
    for($try = 0; $try < 10 and !$port; $try++)
    {
        $tryPort = 18000 + ((getmypid() + $try * 13) % 800);
        $process = proc_open(sprintf('%s -S 127.0.0.1:%d %s', PHP_BINARY, $tryPort, escapeshellarg($router)), array(array('pipe', 'r'), array('pipe', 'w'), array('pipe', 'w')), $pipes);
        if(!is_resource($process)) break;

        for($i = 0; $i < 50; $i++)
        {
            usleep(100000);
            $socket = @fsockopen('127.0.0.1', $tryPort, $errno, $errstr, 0.2);
            if($socket)
            {
                fclose($socket);
                $port = $tryPort;
                break;
            }

            $status = proc_get_status($process);
            if(!$status['running']) break;
        }

        if(!$port)
        {
            proc_terminate($process);
            proc_close($process);
            $process = null;
        }
    }
}
if($process)
{
    register_shutdown_function(function() use ($process, $router)
    {
        proc_terminate($process);
        proc_close($process);
        @unlink($router);
    });
}

/* 本地服务不可用时退回外部重定向服务。 */
$baseUrl    = $port ? "http://127.0.0.1:$port" : 'https://httpbin.org';
$validUrl   = $port ? "$baseUrl/redirect" : 'https://httpbin.org/redirect-to?url=http://example.com/item/123/';
$paramsUrl  = $port ? "$baseUrl/params" : 'https://httpbin.org/redirect-to?url=http://example.com/item/123/';
$authUrl    = $port ? "$baseUrl/basic-auth" : 'https://httpbin.org/basic-auth/jenkins/secret';
$invalidUrl = $port ? "$baseUrl/not-exists" : 'https://httpbin.org/status/404';

$validPWD   = 'jenkins:secret';
$invalidPWD = 'invalid:password';

$emptyData = new stdclass();

$normalData = new stdclass();
$normalData->PARAM_TAG = 'tag_test1';
$normalData->PARAM_REVISION = 'revision123';

$tagData = new stdclass();
$tagData->PARAM_TAG = 'v1.0.0';
$tagData->PARAM_REVISION = 'should_be_cleared';

r($ci->sendRequestTest($validUrl, $normalData, $validPWD))   && p() && e('1');  // 步骤1：正常情况
r($ci->sendRequestTest($validUrl, $emptyData, $validPWD))    && p() && e('1');  // 步骤2：空数据
r($ci->sendRequestTest($invalidUrl, $normalData, $validPWD)) && p() && e('0');  // 步骤3：无效URL
r($ci->sendRequestTest($authUrl, $normalData, $invalidPWD))  && p() && e('0');  // 步骤4：错误认证
r($ci->sendRequestTest($validUrl, $tagData, $validPWD))      && p() && e('1');  // 步骤5：PARAM_TAG处理
if($port) r($ci->sendRequestRawTest($paramsUrl, $tagData, $validPWD)) && p() && e('v1.0.0-'); // 步骤6：PARAM_TAG置空REVISION
