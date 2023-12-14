#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getExecutionStories();
cid=1

- 检查是否获取到 admin 数据
 - 第1条的id属性 @1
 - 第1条的title属性 @Test story
 - 第1条的stage属性 @wait
 - 第1条的openedBy属性 @admin
- 检查是否获取到 user1 数据
 - 第1条的id属性 @1
 - 第1条的title属性 @Test story
 - 第1条的stage属性 @wait
 - 第1条的openedBy属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getExecutionStoriesTest()) && p('1:id,title,stage,openedBy') && e('1,Test story,wait,admin'); // 检查是否获取到 admin 数据

su('user1');
r($tutorial->getExecutionStoriesTest()) && p('1:id,title,stage,openedBy') && e('1,Test story,wait,user1'); // 检查是否获取到 user1 数据
