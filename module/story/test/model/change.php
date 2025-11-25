#!/usr/bin/env php
<?php

/**

title=测试 storyModel->change();
timeout=0
cid=18479

- 查看变更后需求数据。
 - 属性title @测试需求1变更标题
 - 属性spec @测试需求1的变更描述
 - 属性version @4
- 变更时不填写需求名称，给出提示 @1
- 测试存在relievedTwins。属性title @测试需求1变更标题
- 测试变成需求。属性title @名称修改

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

zenData('story')->gen(30);
zenData('storyspec')->gen(90);
zenData('doc')->gen(30);

$story  = new storyTest();

$story->objectModel->config->requirement = $story->objectModel->config->story;

$story1 = new stdclass();
$story1->title              = '测试需求1变更标题';
$story1->spec               = '测试需求1的变更描述';
$story1->verify             = '测试需求1的变更验收标准';
$story1->deleteFiles        = array();
$story1->reviewerHasChanged = '';
$story1->estimate           = 1;
$story1->reviewer           = array();
$story1->version            = 4;
$story1->docs               = '1';
$story1->oldDocs            = array(1);
$story1->docVersions        = '1';

$story2 = clone $story1;
$story2->reviewer = array('admin', 'test2');
$story2->title    = '';
$story2->version  = 5;

$story3 = clone $story1;
$story3->title = '';

$story4 = clone $story1;
$story4->relievedTwins = true;

$story5 = clone $story1;
$story5->title = '名称修改';

$result = $story->changeTest(2,  $story2);

r($story->changeTest(1, $story1))                               && p('title,spec,version') && e('测试需求1变更标题,测试需求1的变更描述,4'); // 查看变更后需求数据。
r((int)strpos($result['title'][0], '名称』不能为空') !== false) && p()                     && e('1');                                       // 变更时不填写需求名称，给出提示
r($story->changeTest(3, $story4))                               && p('title')              && e('测试需求1变更标题');                       // 测试存在relievedTwins。
r($story->changeTest(4, $story5))                               && p('title')              && e('名称修改');                                // 测试变成需求。
