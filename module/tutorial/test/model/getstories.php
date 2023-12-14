#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getStories();
cid=1

- 测试是否能拿到 admin 数据 1
 - 第0条的id属性 @1
 - 第0条的title属性 @Test story
 - 第0条的module属性 @1
 - 第0条的estimate属性 @1
 - 第0条的status属性 @active
 - 第0条的pri属性 @3
 - 第0条的stage属性 @wait
 - 第0条的openedBy属性 @admin
- 测试是否能拿到 admin 数据 2
 - 第1条的id属性 @2
 - 第1条的title属性 @Test story 2
 - 第1条的module属性 @1
 - 第1条的estimate属性 @1
 - 第1条的status属性 @active
 - 第1条的pri属性 @3
 - 第1条的stage属性 @wait
 - 第1条的openedBy属性 @admin
- 测试是否能拿到 user1 数据 1
 - 第0条的id属性 @1
 - 第0条的title属性 @Test story
 - 第0条的module属性 @1
 - 第0条的estimate属性 @1
 - 第0条的status属性 @active
 - 第0条的pri属性 @3
 - 第0条的stage属性 @wait
 - 第0条的openedBy属性 @user1
- 测试是否能拿到 user1 数据 2
 - 第1条的id属性 @2
 - 第1条的title属性 @Test story 2
 - 第1条的module属性 @1
 - 第1条的estimate属性 @1
 - 第1条的status属性 @active
 - 第1条的pri属性 @3
 - 第1条的stage属性 @wait
 - 第1条的openedBy属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/tutorial.class.php';

zdTable('user')->gen(5);

$tutorial = new tutorialTest();

su('admin');
r($tutorial->getStoriesTest()) && p('0:id,title,module,estimate,status,pri,stage,openedBy') && e('1,Test story,1,1,active,3,wait,admin'); // 测试是否能拿到 admin 数据 1
r($tutorial->getStoriesTest()) && p('1:id,title,module,estimate,status,pri,stage,openedBy') && e('2,Test story 2,1,1,active,3,wait,admin'); // 测试是否能拿到 admin 数据 2

su('user1');
r($tutorial->getStoriesTest()) && p('0:id,title,module,estimate,status,pri,stage,openedBy') && e('1,Test story,1,1,active,3,wait,user1'); // 测试是否能拿到 user1 数据 1
r($tutorial->getStoriesTest()) && p('1:id,title,module,estimate,status,pri,stage,openedBy') && e('2,Test story 2,1,1,active,3,wait,user1'); // 测试是否能拿到 user1 数据 2
