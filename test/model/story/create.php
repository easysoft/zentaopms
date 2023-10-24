#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->create();
cid=1
pid=1

不勾选由谁评审并且不传入评审人的情况，报错 >> 『由谁评审』不能为空。
不勾选由谁评审传入评审人的情况，正常插入 >> 0,projected,1
勾选由谁评审，不传入executionID的情况，阶段为wait >> 测试需求3,2,wait
勾选由谁评审，传入executionID和fromBug的情况，阶段为projected >> 『研发需求名称』不能为空。

*/

$story  = new storyTest();
$story1['title']    = '测试需求1'; 
$story1['pri']      = '3'; 
$story1['product']  = 1; 
$story1['spec']     = '测试需求的描述111'; 
$story1['verify']   = '测试需求的验收标准111'; 
$story1['estimate'] = 3; 
$story1['mailto']   = array('user2', 'test2', 'admin'); 

$story2 = $story1;
$story2['reviewer'] = array('admin');
$story2['title']    = '测试需求2';

$story3 = $story1;
$story3['needNotReview'] = true;
$story3['product']       = 2;
$story3['title']         = '测试需求3';

$story4 = $story1;
$story4['needNotReview'] = true;
$story4['title']         = '';

$result1 = $story->createTest(11, 0, '', '', $story1);
$result2 = $story->createTest(11, 0, '', '', $story2);
$result3 = $story->createTest(0,  2, '', '', $story3);
$result4 = $story->createTest(12, 2, '', '', $story4);

r($result1[0]) && p()                        && e('『由谁评审』不能为空。');     //不勾选由谁评审并且不传入评审人的情况，报错
r($result2)    && p('fromBug,stage,product') && e('0,projected,1');              //不勾选由谁评审传入评审人的情况，正常插入
r($result3)    && p('title,fromBug,stage')   && e('测试需求3,2,wait');           //勾选由谁评审，不传入executionID的情况，阶段为wait
r($result4)    && p('title:0')               && e('『研发需求名称』不能为空。'); //勾选由谁评审，传入executionID和fromBug的情况，阶段为projected
