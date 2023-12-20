#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/mr.class.php';

/**

title=测试 mrModel::link();
timeout=0
cid=1

- 错误的合并请求 @0
- 错误的关联类型 @0
- 关联需求
 - 属性id @2
 - 属性BType @story
 - 属性BID @2
- 关联bug
 - 属性id @4
 - 属性BType @bug
 - 属性BID @2
- 关联task
 - 属性id @6
 - 属性BType @task
 - 属性BID @2

*/

zdTable('mr')->config('mr')->gen(1);
zdTable('relation')->gen(0);
su('admin');

$mrModel = new mrTest();

r($mrModel->linkTester(2, 'story'))   && p() && e('0'); // 错误的合并请求
r($mrModel->linkTester(1, 'stories')) && p() && e('0'); // 错误的关联类型

r($mrModel->linkTester(1, 'story')) && p('id,BType,BID') && e('2,story,2'); //关联需求
r($mrModel->linkTester(1, 'bug'))   && p('id,BType,BID') && e('4,bug,2');   //关联bug
r($mrModel->linkTester(1, 'task'))  && p('id,BType,BID') && e('6,task,2');  //关联task