#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::setSessionForViewPage();
timeout=0
cid=17668

- 步骤1: 所有参数都符合不设置 session 的条件，storyList 应该不被设置属性storyList @0
- 步骤2: 所有参数都符合不设置 session 的条件，bugList 应该不被设置属性bugList @0
- 步骤3: orderBy 不为 order_desc,应该设置 storyList属性storyList @1
- 步骤4: type 为 bug 且 orderBy 不为 order_desc,应该设置 bugList属性bugList @1
- 步骤5: pageID 不为 1,应该设置 storyList属性storyList @1
- 步骤6: recPerPage 不为 100,应该设置 bugList属性bugList @1
- 步骤7: type 不为 story 或 bug,不应该设置 storyList属性storyList @0
- 步骤8: type 不为 story 或 bug,不应该设置 bugList属性bugList @0
- 步骤9: 多个参数均非默认值,应该设置 storyList属性storyList @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productplanTest = new productplanZenTest();

$result1 = $productplanTest->setSessionForViewPageTest(1, 'story', 'order_desc', 1, 100);
r($result1) && p('storyList') && e('0'); // 步骤1: 所有参数都符合不设置 session 的条件，storyList 应该不被设置
r($result1) && p('bugList') && e('0'); // 步骤2: 所有参数都符合不设置 session 的条件，bugList 应该不被设置
r($productplanTest->setSessionForViewPageTest(1, 'story', 'id_desc', 1, 100)) && p('storyList') && e('1'); // 步骤3: orderBy 不为 order_desc,应该设置 storyList
r($productplanTest->setSessionForViewPageTest(2, 'bug', 'id_desc', 1, 100)) && p('bugList') && e('1'); // 步骤4: type 为 bug 且 orderBy 不为 order_desc,应该设置 bugList
r($productplanTest->setSessionForViewPageTest(3, 'story', 'order_desc', 2, 100)) && p('storyList') && e('1'); // 步骤5: pageID 不为 1,应该设置 storyList
r($productplanTest->setSessionForViewPageTest(4, 'bug', 'order_desc', 1, 50)) && p('bugList') && e('1'); // 步骤6: recPerPage 不为 100,应该设置 bugList
$result6 = $productplanTest->setSessionForViewPageTest(5, 'task', 'order_desc', 1, 100);
r($result6) && p('storyList') && e('0'); // 步骤7: type 不为 story 或 bug,不应该设置 storyList
r($result6) && p('bugList') && e('0'); // 步骤8: type 不为 story 或 bug,不应该设置 bugList
r($productplanTest->setSessionForViewPageTest(6, 'story', 'status_asc', 5, 20)) && p('storyList') && e('1'); // 步骤9: 多个参数均非默认值,应该设置 storyList