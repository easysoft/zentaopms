#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionStories();
cid=19434

- 检查获取需求ID第3条的id属性 @3
- 检查获取需求名称第3条的title属性 @Test active story
- 检查获取需求阶段第3条的stage属性 @wait
- 检查获取需求创建者第3条的openedBy属性 @admin
- 切换用户后检查创建者第3条的openedBy属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

zenData('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getExecutionStoriesTest()) && p('3:id')       && e('3');                 // 检查获取需求ID
r($tutorial->getExecutionStoriesTest()) && p('3:title')    && e('Test active story'); // 检查获取需求名称
r($tutorial->getExecutionStoriesTest()) && p('3:stage')    && e('wait');              // 检查获取需求阶段
r($tutorial->getExecutionStoriesTest()) && p('3:openedBy') && e('admin');             // 检查获取需求创建者

su('user1');
r($tutorial->getExecutionStoriesTest()) && p('3:openedBy') && e('user1'); // 切换用户后检查创建者
