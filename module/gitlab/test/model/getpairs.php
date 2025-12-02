#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::getPairs();
timeout=0
cid=16654

- 步骤1：正常获取GitLab pairs，验证返回类型 @array
- 步骤2：验证ID为1的GitLab服务器名称属性1 @GitLab服务器
- 步骤3：验证ID为2的GitLab测试名称属性2 @GitLab测试
- 步骤4：验证只返回type为gitlab且未删除的记录数量 @5
- 步骤5：验证返回数组的第一个键为1 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pipeline');
$table->id->range('1-10');
$table->type->range('gitlab{5},jenkins{3},sonarqube{2}');
$table->name->range('GitLab服务器,GitLab测试,GitLab开发,GitLab生产,GitLab集成,Jenkins服务器,Jenkins测试,Jenkins开发,SonarQube服务器,SonarQube测试');
$table->url->range('http://gitlab1.test,http://gitlab2.test,http://gitlab3.test,http://gitlab4.test,http://gitlab5.test,http://jenkins1.test,http://jenkins2.test,http://jenkins3.test,http://sonar1.test,http://sonar2.test');
$table->deleted->range('0{8},1{2}');
$table->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$gitlabTest = new gitlabTest();

// 5. 执行测试步骤（必须包含至少5个测试步骤）
r($gitlabTest->getPairsTest()) && p() && e('array'); // 步骤1：正常获取GitLab pairs，验证返回类型
r($gitlabTest->getPairsTest()) && p('1') && e('GitLab服务器'); // 步骤2：验证ID为1的GitLab服务器名称
r($gitlabTest->getPairsTest()) && p('2') && e('GitLab测试'); // 步骤3：验证ID为2的GitLab测试名称
r(count($gitlabTest->getPairsTest())) && p() && e('5'); // 步骤4：验证只返回type为gitlab且未删除的记录数量
r(array_keys($gitlabTest->getPairsTest())) && p('0') && e('1'); // 步骤5：验证返回数组的第一个键为1