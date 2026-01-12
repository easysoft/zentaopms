#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getObjectList();
timeout=0
cid=18328

- 查看获取到story类型对象
 - 第4条的id属性 @4
 - 第4条的type属性 @story
- 查看获取到requirement类型对象
 - 第5条的id属性 @5
 - 第5条的type属性 @requirement
- 查看获取到story类型对象
 - 第6条的id属性 @6
 - 第6条的type属性 @story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('task')->gen(10);
zenData('story')->gen(10);
zenData('bug')->gen(10);
zenData('case')->gen(10);

global $tester;
$tester->loadModel('search');
$idGroup = array('task' => array(1, 2, 3), 'story' => array(4, 5, 6), 'bug' => array(7, 8, 9), 'case' => array(10, 11, 12));

$result = $tester->search->getObjectList($idGroup);

r($result['story']) && p('4:id,type') && e('4,story'); // 查看获取到story类型对象
r($result['story']) && p('5:id,type') && e('5,requirement'); // 查看获取到requirement类型对象
r($result['story']) && p('6:id,type') && e('6,story'); // 查看获取到story类型对象