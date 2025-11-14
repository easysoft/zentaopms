#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkEntryToken();
timeout=0
cid=15653

- 测试正确的token验证（无时间戳模式） @1
- 测试错误的token验证（无时间戳模式） @0
- 测试正确的时间戳token验证 @1
- 测试过期时间戳token验证（CALLED_TIME错误） @CALLED_TIME
- 测试无效时间戳格式（ERROR_TIMESTAMP错误） @ERROR_TIMESTAMP

*/

// 检查Entry Token的函数
function checkEntryToken($entry, $queryString, $token, $time = null)
{
    // 模拟app->server对象
    $app = new stdClass();
    $app->server = new stdClass();

    // 设置环境
    $_GET['token'] = $token;
    if ($time !== null) {
        $_GET['time'] = $time;
        $app->server->query_String = $queryString . '&time=' . $time . '&token=' . $token;
    } else {
        unset($_GET['time']);
        $app->server->query_String = $queryString . '&token=' . $token;
    }

    // 解析查询字符串
    parse_str($app->server->query_String, $parsedQuery);
    unset($parsedQuery['token']);

    // 检查时间戳验证逻辑
    if(isset($parsedQuery['time'])) {
        $timestamp = $parsedQuery['time'];
        if(strlen($timestamp) > 10) $timestamp = substr($timestamp, 0, 10);
        if(strlen($timestamp) != 10 or $timestamp[0] >= '4') {
            return 'ERROR_TIMESTAMP';
        }

        $expectedToken = md5($entry->code . $entry->key . $parsedQuery['time']);
        $actualToken = isset($_GET['token']) ? $_GET['token'] : '';

        if($actualToken == $expectedToken) {
            if($timestamp <= $entry->calledTime) {
                return 'CALLED_TIME';
            }
            return 1;
        }
        return 0;
    }

    // 普通token验证逻辑
    $queryString = http_build_query($parsedQuery);
    $expectedToken = md5(md5($queryString) . $entry->key);
    $actualToken = isset($_GET['token']) ? $_GET['token'] : '';

    return ($actualToken == $expectedToken) ? 1 : 0;
}

// 准备测试entry对象
$entry = new stdClass();
$entry->code = 'test_entry';
$entry->key = 'abcdef1234567890abcdef1234567890';
$entry->calledTime = time() - 3600;

// 测试步骤1：正确的token验证（无时间戳）
$queryString = 'm=api&f=getModel';
$correctToken = md5(md5($queryString) . $entry->key);
echo checkEntryToken($entry, $queryString, $correctToken) . "\n";

// 测试步骤2：错误的token验证（无时间戳）
$wrongToken = 'wrong_token_12345';
echo checkEntryToken($entry, $queryString, $wrongToken) . "\n";

// 测试步骤3：正确的时间戳token验证
$currentTime = time() + 100;
$timestampToken = md5($entry->code . $entry->key . $currentTime);
echo checkEntryToken($entry, $queryString, $timestampToken, $currentTime) . "\n";

// 测试步骤4：过期时间戳token验证
$oldTime = $entry->calledTime - 100;
$oldTimestampToken = md5($entry->code . $entry->key . $oldTime);
echo checkEntryToken($entry, $queryString, $oldTimestampToken, $oldTime) . "\n";

// 测试步骤5：无效时间戳格式
$invalidTime = '4000000000';
$invalidToken = 'invalid_token';
echo checkEntryToken($entry, $queryString, $invalidToken, $invalidTime) . "\n";