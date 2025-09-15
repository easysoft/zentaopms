#!/usr/bin/env php
<?php

/**

title=测试 adminZen::getZentaoData();
timeout=0
cid=0

- 执行adminTest模块的getZentaoDataTest方法，参数是null, 'open', false 属性hasData @0
- 执行adminTest模块的getZentaoDataTest方法，参数是null, 'biz', false 属性hasData @0
- 执行adminTest模块的getZentaoDataTest方法，参数是$fullConfig, 'open', false 属性hasData @1
- 执行adminTest模块的getZentaoDataTest方法，参数是$emptyConfig, 'open', false 属性hasData @1
- 执行adminTest模块的getZentaoDataTest方法，参数是$partialConfig, 'open', false 属性hasData @1
- 执行adminTest模块的getZentaoDataTest方法，参数是$partialConfig, 'open', true 属性hasData @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/admin.unittest.class.php';

su('admin');

$adminTest = new adminTest();

// 测试步骤1：无zentaoWebsite配置且为open版本
r($adminTest->getZentaoDataTest(null, 'open', false)) && p('hasData') && e('0');

// 测试步骤2：无zentaoWebsite配置且为非open版本
r($adminTest->getZentaoDataTest(null, 'biz', false)) && p('hasData') && e('0');

// 测试步骤3：有完整zentaoWebsite配置数据
$fullConfig = new stdClass();
$fullConfig->dynamic = '{"1":{"id":"1","title":"动态1","addedDate":"2023-01-01","link":"http://test.com/1"}}';
$fullConfig->publicClass = '{"1":{"name":"公开课1","image":"http://test.com/image1.jpg","viewLink":"http://test.com/class1"}}';
$fullConfig->plugin = '{"1":{"id":"1","name":"插件1","viewLink":"http://test.com/plugin1"}}';
$fullConfig->patch = '{"1":{"id":"1","name":"补丁1","viewLink":"http://test.com/patch1"}}';
r($adminTest->getZentaoDataTest($fullConfig, 'open', false)) && p('hasData') && e('1');

// 测试步骤4：zentaoWebsite配置为空对象
$emptyConfig = new stdClass();
r($adminTest->getZentaoDataTest($emptyConfig, 'open', false)) && p('hasData') && e('1');

// 测试步骤5：有部分zentaoWebsite配置数据
$partialConfig = new stdClass();
$partialConfig->plugin = '{"1":{"id":"1","name":"插件1","viewLink":"http://test.com/plugin1"}}';
r($adminTest->getZentaoDataTest($partialConfig, 'open', false)) && p('hasData') && e('1');

// 测试步骤6：非中国地区用户有插件数据（模拟行为）
r($adminTest->getZentaoDataTest($partialConfig, 'open', true)) && p('hasData') && e('1');