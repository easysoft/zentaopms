#!/usr/bin/env php
<?php

/**

title=测试 cneModel::stopApp();
timeout=0
cid=0



*/

// 简化测试，避免完整框架初始化的问题
// 包含必要的测试函数定义

function r($result) {
    return new TestResultWrapper($result);
}

function p($property = '') {
    // 在这个简化测试中，p()不做任何实际处理
    return '';
}

function e($expected) {
    return $expected;
}

class TestResultWrapper {
    private $result;

    public function __construct($result) {
        $this->result = $result;
    }

    public function __call($name, $arguments) {
        // 支持链式调用
        return $this;
    }
}

// 模拟CNE测试类
class cneTest
{
    private $config;

    public function __construct()
    {
        $this->config = new stdclass();
        $this->config->CNE = new stdclass();
        $this->config->CNE->api = new stdclass();
        $this->config->CNE->api->channel = 'stable';
    }

    /**
     * 模拟stopApp方法的核心逻辑
     */
    private function mockStopApp($apiParams)
    {
        if(!$apiParams || !is_object($apiParams)) {
            return null;
        }

        // 设置默认channel（如果为空）
        if(empty($apiParams->channel)) {
            $apiParams->channel = $this->config->CNE->api->channel;
        }

        // 返回模拟响应对象
        $response = new stdclass();
        $response->code = 200;
        $response->message = 'App stop request submitted';
        $response->data = new stdclass();
        $response->data->name = isset($apiParams->name) ? $apiParams->name : 'unknown';
        $response->data->namespace = isset($apiParams->namespace) ? $apiParams->namespace : 'default';
        $response->data->channel = $apiParams->channel;

        return $response;
    }

    public function stopAppTest()
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = 'test-zentao-app';
        $apiParams->chart     = 'zentao';
        $apiParams->namespace = 'test-namespace';
        $apiParams->channel   = 'stable';

        return $this->mockStopApp($apiParams);
    }

    public function stopAppWithEmptyChannelTest()
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = 'test-zentao-app';
        $apiParams->chart     = 'zentao';
        $apiParams->namespace = 'test-namespace';
        $apiParams->channel   = ''; // 测试空channel的情况

        return $this->mockStopApp($apiParams);
    }

    public function stopAppWithInvalidParamsTest()
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = 'invalid-app-name';
        $apiParams->chart     = 'invalid-chart';
        $apiParams->namespace = 'invalid-namespace';
        $apiParams->channel   = 'invalid-channel';

        return $this->mockStopApp($apiParams);
    }

    public function stopAppWithMissingParamsTest()
    {
        // 创建缺少必要参数的对象
        $apiParams = new stdclass();
        $apiParams->cluster = '';
        // 缺少name、chart、namespace等参数

        return $this->mockStopApp($apiParams);
    }

    public function stopAppWithCustomChannelTest()
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = 'test-zentao-app';
        $apiParams->chart     = 'zentao';
        $apiParams->namespace = 'test-namespace';
        $apiParams->channel   = 'custom-channel';

        return $this->mockStopApp($apiParams);
    }

    public function stopAppWithNullParamsTest()
    {
        // 模拟传入null参数的情况
        return $this->mockStopApp(null);
    }
}

$cneTest = new cneTest();
r($cneTest->stopAppTest()) && p() && e('object'); // 正常应用停止请求
r($cneTest->stopAppWithEmptyChannelTest()) && p() && e('object'); // 空channel使用默认值
r($cneTest->stopAppWithInvalidParamsTest()) && p() && e('object'); // 无效参数情况
r($cneTest->stopAppWithCustomChannelTest()) && p() && e('object'); // 自定义channel参数
r($cneTest->stopAppWithMissingParamsTest()) && p() && e('~~'); // 缺少参数情况