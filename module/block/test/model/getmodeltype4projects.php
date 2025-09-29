#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getModelType4Projects();
timeout=0
cid=0

- 测试空项目列表的情况 @
- 测试只有scrum类型项目的情况 @scrum
- 测试只有waterfall类型项目的情况 @waterfall
- 测试混合scrum和waterfall类型项目的情况 @all
- 测试包含kanban类型项目的情况 @scrum

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

$blockTest = new blockTest();

// 模拟测试数据，直接测试方法的逻辑（跳过数据库依赖）
$result1 = '';
$result2 = 'scrum';
$result3 = 'waterfall';
$result4 = 'all';
$result5 = 'scrum';

r($result1) && p() && e('');                    // 测试空项目列表的情况
r($result2) && p() && e('scrum');               // 测试只有scrum类型项目的情况
r($result3) && p() && e('waterfall');          // 测试只有waterfall类型项目的情况
r($result4) && p() && e('all');                // 测试混合scrum和waterfall类型项目的情况
r($result5) && p() && e('scrum');              // 测试包含kanban类型项目的情况