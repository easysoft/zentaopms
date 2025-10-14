#!/usr/bin/env php
<?php

/**

title=测试 sonarqubeZen::sortAndPage();
cid=0

- 测试步骤1：正常数据排序（升序） >> 期望第一个元素为id最小值
- 测试步骤2：正常数据排序（降序） >> 期望第一个元素为id最大值
- 测试步骤3：测试分页功能（第一页） >> 期望返回指定数量的数据
- 测试步骤4：测试分页功能（第二页） >> 期望返回剩余数据或指定数量
- 测试步骤5：测试边界情况（空数据列表） >> 期望返回空数组

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/sonarqube.unittest.class.php';

// 准备测试数据
$testData = array();
for($i = 1; $i <= 10; $i++)
{
    $obj = new stdClass();
    $obj->id = $i;
    $obj->name = 'test' . $i;
    $obj->status = $i % 2 == 0 ? 'active' : 'inactive';
    $testData[] = $obj;
}

// 用户登录
su('admin');

// 创建测试实例
$sonarqubeTest = new sonarqubeTest();

// 测试步骤1：正常数据排序（升序）
r($sonarqubeTest->sortAndPageTest($testData, 'id_asc', 5, 1)) && p('0:0:id') && e('1');

// 测试步骤2：正常数据排序（降序）
r($sonarqubeTest->sortAndPageTest($testData, 'id_desc', 5, 1)) && p('0:0:id') && e('10');

// 测试步骤3：测试分页功能（总页数）
r($sonarqubeTest->sortAndPageTest($testData, 'id_asc', 3, 1)) && p() && e('4');

// 测试步骤4：测试分页功能（第二页）
r($sonarqubeTest->sortAndPageTest($testData, 'id_asc', 3, 2)) && p('1:0:id') && e('4');

// 测试步骤5：测试边界情况（空数据列表）
r($sonarqubeTest->sortAndPageTest(array(), 'id_asc', 5, 1)) && p() && e('0');