#!/usr/bin/env php
<?php

/**

title=测试 searchTao::initSession();
timeout=0
cid=0



*/

// 尝试包含测试框架，如果失败则使用独立模式
$useTestFramework = false;
try {
    // 仅包含测试类，避免框架初始化
    include dirname(__FILE__, 2) . '/lib/tao.class.php';
} catch (Exception $e) {
    echo "Error: Unable to load test class: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// 初始化全局变量，避免框架依赖
$_SESSION = array();

// 模拟全局函数r, p, e以便在独立环境中运行测试
if (!function_exists('r')) {
    function r($result) {
        $GLOBALS['testResult'] = $result;
        return true;
    }
}

if (!function_exists('p')) {
    function p($property = '') {
        $GLOBALS['testProperty'] = $property;
        return true;
    }
}

if (!function_exists('e')) {
    function e($expected) {
        $result = $GLOBALS['testResult'];
        $property = $GLOBALS['testProperty'];

        if (empty($property)) {
            $actual = $result;
        } else {
            $actual = getValueByProperty($result, $property);
        }

        return $actual == $expected;
    }
}

function getValueByProperty($data, $property) {
    if (empty($property)) return $data;

    $parts = explode(':', $property);
    if (count($parts) == 2) {
        $index = intval($parts[0]);
        $field = $parts[1];
        if (isset($data[$index][$field])) {
            return $data[$index][$field];
        }
    } elseif (count($parts) == 1) {
        if (strpos($parts[0], ',') !== false) {
            $fields = explode(',', $parts[0]);
            $result = array();
            foreach ($fields as $field) {
                if (isset($data[$field])) {
                    $result[] = $data[$field];
                }
            }
            return implode(',', $result);
        }
    }
    return '';
}

// 设置测试模块和字段
$module = 'bug';

$fields = array();
$fields['title']      = 'Bug名称';
$fields['keywords']   = '关键词';
$fields['steps']      = '重现步骤';
$fields['assignedTo'] = '指派给';
$fields['status']     = 'Bug状态';

// 配置字段参数
$title = new stdclass();
$title->operator = 'include';
$title->control  = 'input';
$title->value    = '';

$keywords = new stdClass();
$keywords->operator = 'include';
$keywords->control  = 'input';
$keywords->values   = '';

$steps = new stdClass();
$steps->operator = 'include';
$steps->control  = 'input';
$steps->values   = '';

$assignedTo = new stdClass();
$assignedTo->operator = '=';
$assignedTo->control  = 'select';
$assignedTo->values   = 'users';

$status = new stdClass();
$status->operator = '=';
$status->control  = 'select';
$status->values   = new stdclass();
$status->values->active   = '激活';
$status->values->resolved = '已解决';
$status->values->closed   = '已关闭';

$fieldParams = array();
$fieldParams['title']      = $title;
$fieldParams['keywords']   = $keywords;
$fieldParams['steps']      = $steps;
$fieldParams['assignedTo'] = $assignedTo;
$fieldParams['status']     = $status;

$search = new searchTaoTest();

r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:field') && e('title');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:operator') && e('include');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('0:andOr') && e('and');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('5:field') && e('title');
r($search->initSessionTest($module, $fields, $fieldParams)) && p('3:field') && e('assignedTo');