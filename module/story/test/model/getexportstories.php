#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$story = zdTable('story');
$story->product->range(1);
$story->parent->range('0,`-1`,2,`-1`,0{10},`-1`,100,14,15,15,15');
$story->type->range('story');
$story->gen(20);
zdTable('storyspec')->gen(60);
zdTable('bug')->gen(10);
zdTable('case')->gen(10);

/**

title=测试 storyModel->getExportStories();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');

$postData = new stdclass();
$postData->fileType   = 'csv';

$_COOKIE['checkedItem']          = '1,2';
$_SESSION['storyOnlyCondition']  = true;
$_SESSION['storyQueryCondition'] = ' `id` < 10';

$postData->exportType = 'selected';
$stories1 = $tester->story->getExportStories('id_desc', 'story', clone($postData));

$postData->exportType = 'all';
$stories2 = $tester->story->getExportStories('id_desc', 'story', clone($postData));

$_SESSION['storyOnlyCondition']  = false;
$_SESSION['storyQueryCondition'] = "SELECT * FROM `zt_story` WHERE `status` = 'active'";
$postData->exportType = 'selected';
$stories3 = $tester->story->getExportStories('id_desc', 'story', clone($postData));

$postData->exportType = 'all';
$stories4 = $tester->story->getExportStories('id_desc', 'story', clone($postData));

r(count($stories1)) && p() && e('2');  //查看只保存导出条件，导出选中需求数
r(count($stories2)) && p() && e('9');  //查看只保存导出条件，导出全部需求数
r(count($stories3)) && p() && e('2');  //查看保存全部导出SQL，导出选中需求数
r(count($stories4)) && p() && e('5');  //查看保存全部导出SQL，导出全部需求数

r(implode('|', array_keys($stories2))) && p() && e('9|8|7|6|5|4|2|3|1');  //查看需求的id顺序
r($stories2) && p('2:parent,title,spec,verify,bugCountAB') && e('`-1`,用户需求版本一2,这是一个软件需求描述2,这是一个需求验收2,1');  //查看需求ID为2的数据信息。
