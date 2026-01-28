#!/usr/bin/env php
<?php

/**

title=测试 storyModel->doCreateSpec();
cid=18618

- 不传入任何数据。 @0
- 验证spec信息。
 - 属性story @10
 - 属性title @test story
 - 属性spec @test spec
 - 属性verify @test verify
 - 属性version @1
 - 属性files @1:2
- 不传入附件，验证附件信息。 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('storyspec')->gen(0);

global $tester;
$storyModel = $tester->loadModel('story');

$data  = new stdclass();
$data->title   = 'test story';
$data->spec    = 'test spec';
$data->verify  = 'test verify';
$data->version = 1;
r($storyModel->doCreateSpec(0, $data)) && p() && e('0'); // 不传入任何数据。

$story     = new storyTaoTest();
$storySpec = $story->doCreateSpecTest(10, $data, array(1 => '1.png', 2 => '2.png'));
$storySpec = array_shift($storySpec);
$storySpec->files = str_replace(',', ':', $storySpec->files);
r($storySpec) && p('story,title,spec,verify,version,files') && e('10,test story,test spec,test verify,1,1:2'); // 验证spec信息。

$storySpec = $story->doCreateSpecTest(11, $data);
$storySpec = array_shift($storySpec);
r($storySpec->files) && p() && e('0'); // 不传入附件，验证附件信息。
