#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getLeafNodes();
timeout=0
cid=0

- 传入空参数。 @0
- 执行story模块的getLeafNodes方法，参数是$stories  @11;10;9;8;7;6;5;4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->isParent->range('1{3},0,0{3},0{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11);

su('admin');

global $tester;
$tester->loadModel('story');

$stories = $tester->story->dao->select('*')->from(TABLE_STORY)->orderBy('id_desc')->fetchAll('id');

r(count($tester->story->getLeafNodes(array())))         && p() && e('0');  //传入空参数。
r(implode(';', array_keys($tester->story->getLeafNodes($stories)))) && p() && e('11;10;9;8;7;6;5;4');