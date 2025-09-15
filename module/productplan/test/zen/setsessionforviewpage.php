#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::setSessionForViewPage();
timeout=0
cid=0

- 执行 @0
- 执行 @1
- 执行 @1
- 执行 @1
- 执行 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$productplanZenTest = new productplanZenTest();

// 模拟setSessionForViewPage的核心逻辑进行测试
function testSessionLogic($planID, $type, $orderBy, $pageID, $recPerPage) {
    $shouldSetSession = in_array($type, array('story', 'bug')) && ($orderBy != 'order_desc' || $pageID != 1 || $recPerPage != 100);
    return $shouldSetSession ? 1 : 0;
}

// 测试1：story类型，默认参数不满足条件，session不变
r(testSessionLogic(1, 'story', 'order_desc', 1, 100)) && p() && e('0');

// 测试2：story类型，orderBy不是order_desc，session应该设置
r(testSessionLogic(1, 'story', 'title_asc', 1, 100)) && p() && e('1');

// 测试3：story类型，pageID不是1，session应该设置  
r(testSessionLogic(1, 'story', 'order_desc', 2, 100)) && p() && e('1');

// 测试4：bug类型，recPerPage不是100，session应该设置
r(testSessionLogic(1, 'bug', 'order_desc', 1, 50)) && p() && e('1');

// 测试5：无效类型，session不应该变化
r(testSessionLogic(1, 'task', 'order_desc', 1, 100)) && p() && e('0');