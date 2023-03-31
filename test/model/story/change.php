#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->change();
cid=1
pid=1

不勾选【不需要评审】，不传入由谁评审时的变更，给出提示 >> 『由谁评审』不能为空。
变更时不填写需求名称，给出提示 >> 『研发需求名称』不能为空。
正常变更需求，判断返回的status、title等信息 >> changed,测试需求1变更标题,测试需求1的变更描述,测试需求1的变更验收标准,1,admin,3

*/

$story  = new storyTest();
$story1['title']    = '测试需求1变更标题';
$story1['spec']     = '测试需求1的变更描述';
$story1['verify']   = '测试需求1的变更验收标准';
$story1['estimate'] = 1;

$story2 = $story1;
$story2['reviewer'] = array('admin', 'test2');
$story2['title']    = '';

$story3 = $story1;
$story3['needNotReview'] = true;

$result1 = $story->changeTest(1,  $story1);
$result2 = $story->changeTest(2,  $story2);
$result3 = $story->changeTest(26, $story3);

r($result1[0]) && p()          && e('『由谁评审』不能为空。');     // 不勾选【不需要评审】，不传入由谁评审时的变更，给出提示
r($result2)    && p('title:0') && e('『研发需求名称』不能为空。'); // 变更时不填写需求名称，给出提示
r($result3)    && p('status,title,spec,verify,estimate,lastEditedBy,version') && e('changed,测试需求1变更标题,测试需求1的变更描述,测试需求1的变更验收标准,1,admin,3'); // 正常变更需求，判断返回的status、title等信息
