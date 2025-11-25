#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doUpdateSpec();
cid=18623

- 执行storyTest模块的doUpdateSpecTest方法，参数是1, $data, $oldData
 - 属性title @teststory
 - 属性spec @testspec
 - 属性verify @testverify
- 执行storyTest模块的doUpdateSpecTest方法，参数是1, $data, $oldData
 - 属性title @teststory1
 - 属性spec @testspec1
 - 属性verify @testverify1
- 执行storyTest模块的doUpdateSpecTest方法，参数是2, $data, $oldData
 - 属性title @teststory1
 - 属性spec @testspec1
 - 属性verify @testverify1
 - 属性files @~~
- 执行storyTest模块的doUpdateSpecTest方法，参数是3, $data, $oldData
 - 属性title @teststory1
 - 属性spec @testspec1
 - 属性verify @testverify1
 - 属性files @8,9,2
- 执行storyTest模块的doUpdateSpecTest方法，参数是4, $data, $oldData
 - 属性title @teststory1
 - 属性spec @testspec1
 - 属性verify @testverify1
 - 属性files @8,9
- 执行$storySpec
 - 属性title @teststory1
 - 属性spec @testspec1
 - 属性verify @testverify1
 - 属性files @8,9

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';
su('admin');

$story = zenData('story');
$story->id->range('1-100');
$story->title->range('teststory');
$story->product->range('1');
$story->twins->range('``{3},5,4');
$story->version->range('1');
$story->gen(5);

$storySpec = zenData('storyspec');
$storySpec->story->range('1-100');
$storySpec->title->range('teststory');
$storySpec->spec->range('testspec');
$storySpec->verify->range('testverify');
$storySpec->files->range('``,1-10');
$storySpec->version->range('1');
$storySpec->gen(5);

$storyTest = new storyTest();

$addedFiles[8] = 'aaa.png';
$addedFiles[9] = 'bbb.png';

$data  = new stdclass();
$data->title       = 'teststory';
$data->spec        = 'testspec';
$data->verify      = 'testverify';
$data->version     = 1;
$data->deleteFiles = array();
$data->files       = '1';
$oldData = $tester->loadModel('story')->getByID(1);
r($storyTest->doUpdateSpecTest(1, $data, $oldData)) && p('title,spec,verify') && e('teststory,testspec,testverify');

$data->title       = 'teststory1';
$data->spec        = 'testspec1';
$data->verify      = 'testverify1';
$data->files       = '1';
r($storyTest->doUpdateSpecTest(1, $data, $oldData)) && p('title,spec,verify') && e('teststory1,testspec1,testverify1');

$data->deleteFiles = array(1);
$data->files       = '';
$oldData = $tester->loadModel('story')->getByID(2);
r($storyTest->doUpdateSpecTest(2, $data, $oldData)) && p('title,spec,verify,files') && e('teststory1,testspec1,testverify1,~~');

$data->deleteFiles = array();
$data->addedFiles  = $addedFiles;
$data->files       = '8,9,2';
$oldData = $tester->loadModel('story')->getByID(3);
r($storyTest->doUpdateSpecTest(3, $data, $oldData)) && p('title|spec|verify|files', '|') && e('teststory1|testspec1|testverify1|8,9,2');

$data->deleteFiles = array(3);
$data->files       = '8,9';
$oldData = $tester->loadModel('story')->getByID(4);
r($storyTest->doUpdateSpecTest(4, $data, $oldData)) && p('title|spec|verify|files', '|') && e('teststory1|testspec1|testverify1|8,9');

$storySpec = $storyTest->objectModel->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->eq('5')->fetch();
r($storySpec) && p('title|spec|verify|files', '|') && e('teststory1|testspec1|testverify1|8,9');
