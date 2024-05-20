#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getLastNodes();
cid=0

- 传入空参数。 @0
- 只传入 allStoryIdList @0
- 只传入 stories @0
- 执行story模块的getLastNodes方法，参数是$stories, $allStoryIdList  @11;10;9;8;7;6;5;4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11);

su('admin');

global $tester;
$tester->loadModel('story');

$tester->story->lang->ERCommon = '业务需求';
$tester->story->lang->URCommon = '用户需求';
$tester->story->lang->SRCommon = '研发需求';

$allStoryIdList = array(1,2,3,4,5,6,7,8,9,10,11);
$stories        = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('1,11')->orderBy('id_desc')->fetchAll('id');

r(count($tester->story->getLastNodes(array(), array())))         && p() && e('0');  //传入空参数。
r(count($tester->story->getLastNodes(array(), $allStoryIdList))) && p() && e('0');  //只传入 allStoryIdList
r(count($tester->story->getLastNodes($stories, array())))        && p() && e('0');  //只传入 stories

r(implode(';', array_keys($tester->story->getLastNodes($stories, $allStoryIdList)))) && p() && e('11;10;9;8;7;6;5;4');
