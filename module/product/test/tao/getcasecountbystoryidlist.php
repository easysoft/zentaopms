#!/usr/bin/env php
<?php

/**

title=productTao->getCaseCountByStoryIdList();
cid=0

- 测试传入空的需求ID列表 @5
- 测试传入需求ID列表属性1 @1
- 测试传入不存在需求ID列表 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('case')->config('case')->gen(30);
zdTable('user')->gen(5);
su('admin');

$storyIdList[0] = array();
$storyIdList[1] = range(1, 10);
$storyIdList[2] = range(100, 110);

global $tester;
$tester->loadModel('product');
r($tester->product->getCaseCountByStoryIdList($storyIdList[0])) && p('0') && e('5'); // 测试传入空的需求ID列表
r($tester->product->getCaseCountByStoryIdList($storyIdList[1])) && p('1') && e('1'); // 测试传入需求ID列表
r($tester->product->getCaseCountByStoryIdList($storyIdList[2])) && p()    && e('0'); // 测试传入不存在需求ID列表
