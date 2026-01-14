#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getTracksByStories();
timeout=0
cid=18565

- 执行$result @1
- 执行$result @1
- 执行$tracks['lanes'] @1
- 执行$tracks['lanes'] @1
- 执行$tracks['lanes'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('story');
$table->id->range('1-10');
$table->root->range('1-10');
$table->path->range('`,1,`,`,2,`,`,3,`,`,4,`,`,5,`,`,6,`,`,7,`,`,8,`,`,9,`,`,10,`');
$table->grade->range('1{10}');
$table->type->range('epic{3},requirement{3},story{4}');
$table->product->range('1{10}');
$table->status->range('active{8},draft{2}');
$table->stage->range('wait{5},planned{3},projected{2}');
$table->title->range('Epic Story %s{3},Requirement %s{3},User Story %s{4}');
$table->isParent->range('0{8},1{2}');
$table->parent->range('0{8},1{1},2{1}');
$table->deleted->range('0{10}');
$table->gen(10);

su('admin');

global $lang;
$lang->ERCommon = '业务需求';
$lang->URCommon = '用户需求';
$lang->SRCommon = '研发需求';

$storyTest = new storyModelTest();

$result = $storyTest->getTracksByStoriesTest(array(), 'epic');
r(is_array($result)) && p() && e('1');

$result = $storyTest->getTracksByStoriesTest(array(), 'story');
r(is_array($result)) && p() && e('1');

$stories = array((object)array('id' => 1, 'root' => 1, 'path' => ',1,', 'grade' => 1, 'type' => 'epic', 'product' => 1, 'status' => 'active'));
$tracks = $storyTest->getTracksByStoriesTest($stories, 'epic');
r(isset($tracks['lanes'])) && p() && e('1');

$stories = array((object)array('id' => 7, 'root' => 7, 'path' => ',7,', 'grade' => 1, 'type' => 'story', 'product' => 1, 'status' => 'active'));
$tracks = $storyTest->getTracksByStoriesTest($stories, 'story');
r(isset($tracks['lanes'])) && p() && e('1');

$stories = array(
    (object)array('id' => 4, 'root' => 4, 'path' => ',4,', 'grade' => 1, 'type' => 'requirement', 'product' => 1, 'status' => 'active'),
    (object)array('id' => 5, 'root' => 5, 'path' => ',5,', 'grade' => 1, 'type' => 'requirement', 'product' => 1, 'status' => 'active')
);
$tracks = $storyTest->getTracksByStoriesTest($stories, 'requirement');
r(isset($tracks['lanes'])) && p() && e('1');