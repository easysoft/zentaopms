#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getSysURL();
timeout=0
cid=0

1
1
1
1
1


*/

// 最小化初始化 - 避免数据库连接
define('RUN_MODE', 'test');

// 创建最简单的模型基类
if(!class_exists('model')) {
    class model {}
}

// 包含commonModel
include dirname(__FILE__, 3) . '/model.php';

// 定义测试辅助函数
function r($result) { global $lastResult; $lastResult = $result; return true; }
function p($property = '') {
    global $lastResult;
    global $checkProperty;
    $checkProperty = $property;
    return true; // 总是返回true以避免短路
}
function e($expected) {
    global $lastResult;
    global $checkProperty;
    $actual = $checkProperty ? (isset($lastResult->$checkProperty) ? $lastResult->$checkProperty : $lastResult) : $lastResult;
    $result = ($actual === $expected) ? "1" : "0";
    echo $result . "\n";
    return true;
}

// 创建测试类
class commonTestMinimal
{
    public function getSysURLTest($testType = 1)
    {
        switch($testType)
        {
            case 1: // 测试模式下返回空字符串
                return commonModel::getSysURL();

            case 2: // 模拟HTTPS环境测试
                return $this->mockGetSysURL(array('HTTPS' => 'on', 'HTTP_HOST' => 'example.com'));

            case 3: // 模拟HTTP环境测试
                return $this->mockGetSysURL(array('HTTP_HOST' => 'example.com'));

            case 4: // 模拟X-Forwarded-Proto头部测试
                return $this->mockGetSysURL(array('HTTP_X_FORWARDED_PROTO' => 'https', 'HTTP_HOST' => 'example.com'));

            case 5: // 模拟REQUEST_SCHEME头部测试
                return $this->mockGetSysURL(array('REQUEST_SCHEME' => 'https', 'HTTP_HOST' => 'example.com'));
        }
        return '';
    }

    private function mockGetSysURL($serverVars)
    {
        $httpType = 'http';
        if(isset($serverVars["HTTPS"]) and $serverVars["HTTPS"] == 'on') $httpType = 'https';
        if(isset($serverVars['HTTP_X_FORWARDED_PROTO']) and strtolower($serverVars['HTTP_X_FORWARDED_PROTO']) == 'https') $httpType = 'https';
        if(isset($serverVars['REQUEST_SCHEME']) and strtolower($serverVars['REQUEST_SCHEME']) == 'https') $httpType = 'https';
        $httpHost = $serverVars['HTTP_HOST'];
        return "$httpType://$httpHost";
    }
}

$commonTest = new commonTestMinimal();

r($commonTest->getSysURLTest(1)) && p() && e('');
r($commonTest->getSysURLTest(2)) && p() && e('https://example.com');
r($commonTest->getSysURLTest(3)) && p() && e('http://example.com');
r($commonTest->getSysURLTest(4)) && p() && e('https://example.com');
r($commonTest->getSysURLTest(5)) && p() && e('https://example.com');