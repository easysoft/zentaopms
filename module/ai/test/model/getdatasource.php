#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDataSource();
timeout=0
cid=15082

- 返回内置数据源分组数量 @21
- 返回数据源包含全部内置分组 @1
- program模块使用配置的字段 @name
- doc模块使用配置的字段 @title
- testsuite模块字段数量 @2
- epic模块复用story数据源 @1
- requirement模块复用story数据源 @1
- story模块过滤deleted等系统字段 @0
- story模块补充子表需求描述字段 @1
- story模块补充子表验收标准字段 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();
$result = $aiTest->getDataSourceTest();

global $config;

r(count($config->ai->moduleGroup)) && p() && e('21'); // 返回内置数据源分组数量
r(count(array_intersect_key($result, $config->ai->moduleGroup)) == count($config->ai->moduleGroup) ? '1' : '0') && p() && e('1'); // 返回数据源包含全部内置分组
r($result['program']['program'][0]) && p() && e('name'); // program模块使用配置的字段
r($result['doc']['doc'][0]) && p() && e('title'); // doc模块使用配置的字段
r(count($result['testsuite']['testsuite'])) && p() && e('2'); // testsuite模块字段数量
r(isset($result['epic']['story']) ? 1 : 0) && p() && e('1'); // epic模块复用story数据源
r(isset($result['requirement']['story']) ? 1 : 0) && p() && e('1'); // requirement模块复用story数据源
r(in_array('deleted', $result['story']['story']) ? 1 : 0) && p() && e('0'); // story模块过滤deleted等系统字段
r(in_array('spec', $result['story']['story']) ? 1 : 0) && p() && e('1'); // story模块补充子表需求描述字段
r(in_array('verify', $result['story']['story']) ? 1 : 0) && p() && e('1'); // story模块补充子表验收标准字段
