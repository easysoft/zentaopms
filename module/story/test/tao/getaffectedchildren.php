#!/usr/bin/env php
<?php

/**

title=测试 storyTao::getAffectedChildren();
timeout=0
cid=18631

- 执行storyTest模块的getAffectedChildrenTest方法，参数是$parentStory, array
 - 属性id @1
 - 属性type @story
- 执行storyTest模块的getAffectedChildrenTest方法，参数是$normalStory, array
 - 属性id @6
 - 属性type @story
- 执行storyTest模块的getAffectedChildrenTest方法，参数是$parentStory, array
 - 属性id @1
 - 属性type @story
- 执行storyTest模块的getAffectedChildrenTest方法，参数是$emptyChildrenStory, array
 - 属性id @2
 - 属性type @story
- 执行storyTest模块的getAffectedChildrenTest方法，参数是$parentStory, array 属性type @story

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$story = zenData('story');
$story->product->range(1);
$story->parent->range('0{3},1,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9');
$story->root->range('1,1,1,1{17}');
$story->path->range(',1,{20}');
$story->isParent->range('1{3},0{17}');
$story->title->range('父需求{3}, 子需求{17}');
$story->type->range('story');
$story->status->range('active');
$story->openedBy->range('admin{10}, user1{6}, user2{4}');
$story->gen(20);

su('admin');

$storyTest = new storyTaoTest();

// 创建测试故事对象1：有子故事的父故事
$parentStory = new stdClass();
$parentStory->id = 1;
$parentStory->type = 'story';
$parentStory->isParent = '1';
$parentStory->children = array();

$child1 = new stdClass();
$child1->id = 4;
$child1->type = 'story';
$child1->status = 'active';
$child1->openedBy = 'admin';
$parentStory->children[4] = $child1;

$child2 = new stdClass();
$child2->id = 5;
$child2->type = 'story';
$child2->status = 'draft';
$child2->openedBy = 'user1';
$parentStory->children[5] = $child2;

// 创建测试故事对象2：无子故事的普通故事
$normalStory = new stdClass();
$normalStory->id = 6;
$normalStory->type = 'story';
$normalStory->isParent = '0';
$normalStory->children = array();

// 创建测试故事对象3：空子项故事
$emptyChildrenStory = new stdClass();
$emptyChildrenStory->id = 2;
$emptyChildrenStory->type = 'story';
$emptyChildrenStory->isParent = '1';
$emptyChildrenStory->children = array();

r($storyTest->getAffectedChildrenTest($parentStory, array('admin' => '管理员', 'user1' => '用户1'))) && p('id,type') && e('1,story');
r($storyTest->getAffectedChildrenTest($normalStory, array('admin' => '管理员'))) && p('id,type') && e('6,story');
r($storyTest->getAffectedChildrenTest($parentStory, array())) && p('id,type') && e('1,story');
r($storyTest->getAffectedChildrenTest($emptyChildrenStory, array('admin' => '管理员', 'user1' => '用户1', 'user2' => '用户2'))) && p('id,type') && e('2,story');
r($storyTest->getAffectedChildrenTest($parentStory, array('admin' => '管理员'))) && p('type') && e('story');