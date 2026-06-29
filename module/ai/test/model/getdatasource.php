#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getDataSource();
timeout=0
cid=15082

- 返回数据源分组数量 @15
- program模块使用配置的字段 @name
- doc模块使用配置的字段 @title
- testsuite模块字段数量 @2
- story模块过滤deleted等系统字段 @0
- story模块补充子表需求描述字段 @1
- story模块补充子表验收标准字段 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();
$result = $aiTest->getDataSourceTest();

r(count($result)) && p() && e('15'); // 返回数据源分组数量
r($result['program']['program'][0]) && p() && e('name'); // program模块使用配置的字段
r($result['doc']['doc'][0]) && p() && e('title'); // doc模块使用配置的字段
r(count($result['testsuite']['testsuite'])) && p() && e('2'); // testsuite模块字段数量
r(in_array('deleted', $result['story']['story']) ? 1 : 0) && p() && e('0'); // story模块过滤deleted等系统字段
r(in_array('spec', $result['story']['story']) ? 1 : 0) && p() && e('1'); // story模块补充子表需求描述字段
r(in_array('verify', $result['story']['story']) ? 1 : 0) && p() && e('1'); // story模块补充子表验收标准字段
