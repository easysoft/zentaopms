#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->product->range('1');
$story->root->range('1{10},11');
$story->grade->range('1,2{3},3{6},1');
$story->parent->range('0,1{3},2{3},3{3},0');
$story->isParent->range('1{3},0,0{3},0{3},0');
$story->type->range('epic,requirement{3},story{6},epic');
$story->gen(11)->fixPath();

su('admin');

global $tester;
$tester->loadModel('story');

$stories        = $tester->story->dao->select('*')->from(TABLE_STORY)->where('id')->in('3')->orderBy('id_desc')->fetchAll('id');
$allStories     = $tester->story->dao->select('id,parent,isParent,root,path,grade,product,pri,type,status,stage,title,estimate')->from(TABLE_STORY)->where('deleted')->eq(0)->orderBy('id_desc')->fetchAll('id');
