#!/usr/bin/env php
<?php

/**

title=测试 hostModel::processTreemap();
timeout=0
cid=0

- 步骤1：空数组输入 @0
- 步骤2：单个简单对象第0条的text属性 @简单主机
- 步骤3：带roomName的对象第0条的text属性 @主机名称
- 步骤4：带hostID的对象第0条的hostid属性 @123
- 步骤5：HTML特殊字符转义第0条的text属性 @&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/host.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 加载语言包
global $app;
$app->loadLang('serverroom');

// 4. 创建测试实例（变量名与模块名一致）
$hostTest = new hostTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r(count($hostTest->processTreemapTest(array()))) && p() && e('0'); // 步骤1：空数组输入
r($hostTest->processTreemapTest(array((object)array('name' => '简单主机')))) && p('0:text') && e('简单主机'); // 步骤2：单个简单对象
r($hostTest->processTreemapTest(array((object)array('roomName' => '主机房名称', 'name' => '主机名称')))) && p('0:text') && e('主机名称'); // 步骤3：带roomName的对象
r($hostTest->processTreemapTest(array((object)array('name' => '带ID主机', 'hostID' => 123)))) && p('0:hostid') && e('123'); // 步骤4：带hostID的对象
r($hostTest->processTreemapTest(array((object)array('name' => '<script>alert("test")</script>')))) && p('0:text') && e('&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;'); // 步骤5：HTML特殊字符转义