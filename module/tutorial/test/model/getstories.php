#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 tutorialModel->getStories();
cid=19474

- 测试获取需求1的信息
 - 第1条的id属性 @1
 - 第1条的title属性 @Test epic
 - 第1条的estimate属性 @1
 - 第1条的status属性 @active
 - 第1条的pri属性 @3
 - 第1条的stage属性 @wait
 - 第1条的openedBy属性 @admin
- 测试获取需求2的信息
 - 第2条的id属性 @2
 - 第2条的title属性 @Test requirement
 - 第2条的estimate属性 @1
 - 第2条的status属性 @active
 - 第2条的pri属性 @3
 - 第2条的stage属性 @wait
 - 第2条的openedBy属性 @admin
- 测试获取需求3的信息
 - 第3条的id属性 @3
 - 第3条的title属性 @Test active story
 - 第3条的estimate属性 @1
 - 第3条的status属性 @active
 - 第3条的pri属性 @3
 - 第3条的stage属性 @wait
 - 第3条的openedBy属性 @admin
- 测试获取需求4的信息
 - 第4条的id属性 @4
 - 第4条的title属性 @Test reviewing story
 - 第4条的estimate属性 @1
 - 第4条的status属性 @reviewing
 - 第4条的pri属性 @3
 - 第4条的stage属性 @wait
 - 第4条的openedBy属性 @admin
- 切换用户，查看需求1的创建者第1条的openedBy属性 @user1
- 切换用户，查看需求2的创建者第2条的openedBy属性 @user1
- 切换用户，查看需求3的创建者第3条的openedBy属性 @user1
- 切换用户，查看需求4的创建者第4条的openedBy属性 @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);

$tutorial = new tutorialModelTest();

su('admin');
r($tutorial->getStoriesTest()) && p('1:id,title,estimate,status,pri,stage,openedBy') && e('1,Test epic,1,active,3,wait,admin');               // 测试获取需求1的信息
r($tutorial->getStoriesTest()) && p('2:id,title,estimate,status,pri,stage,openedBy') && e('2,Test requirement,1,active,3,wait,admin');        // 测试获取需求2的信息
r($tutorial->getStoriesTest()) && p('3:id,title,estimate,status,pri,stage,openedBy') && e('3,Test active story,1,active,3,wait,admin');       // 测试获取需求3的信息
r($tutorial->getStoriesTest()) && p('4:id,title,estimate,status,pri,stage,openedBy') && e('4,Test reviewing story,1,reviewing,3,wait,admin'); // 测试获取需求4的信息

su('user1');
r($tutorial->getStoriesTest()) && p('1:openedBy') && e('user1'); // 切换用户，查看需求1的创建者
r($tutorial->getStoriesTest()) && p('2:openedBy') && e('user1'); // 切换用户，查看需求2的创建者
r($tutorial->getStoriesTest()) && p('3:openedBy') && e('user1'); // 切换用户，查看需求3的创建者
r($tutorial->getStoriesTest()) && p('4:openedBy') && e('user1'); // 切换用户，查看需求4的创建者
