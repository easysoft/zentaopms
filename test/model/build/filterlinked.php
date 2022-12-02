#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/build.class.php';
su('admin');

/**

title=测试 buildModel->filterLinked();
cid=1
pid=1

不传任何数据                      >>  null
迭代版本未关联需求和Bug           >>  null
迭代版本关联需求和Bug             >>  101
项目版本关联版本关联需求和Bug     >>  102
项目版本关联版本未关联需求和Bug   >>  null
版本列表中关联需求和Bug           >>  101
版本列表中混合项目版本和迭代版本  >>  101,102

*/

$build = new buildTest();

$buildData = new stdclass();
$buildData->id        = 100;
$buildData->product   = 1;
$buildData->project   = 1;
$buildData->execution = 2;
$buildData->name      = 'test1';
$buildData->date      = date('Y-m-d');
$build->objectModel->dao->replace(TABLE_BUILD)->data($buildData)->exec();
$buildId1 = $buildData->id;

$buildData->id        = 101;
$buildData->name      = 'test2';
$buildData->stories   = '1,2,3';
$build->objectModel->dao->replace(TABLE_BUILD)->data($buildData)->exec();
$buildId2 = $buildData->id;

$buildData = new stdclass();
$buildData->id        = 102;
$buildData->product   = 1;
$buildData->project   = 1;
$buildData->execution = 0;
$buildData->builds    = ",$buildId2,";
$buildData->name      = 'test3';
$buildData->date      = date('Y-m-d');
$build->objectModel->dao->replace(TABLE_BUILD)->data($buildData)->exec();
$buildId3 = $buildData->id;

$buildData = new stdclass();
$buildData->id        = 103;
$buildData->product   = 1;
$buildData->project   = 1;
$buildData->execution = 0;
$buildData->builds    = ",$buildId1,";
$buildData->name      = 'test4';
$buildData->date      = date('Y-m-d');
$build->objectModel->dao->replace(TABLE_BUILD)->data($buildData)->exec();
$buildId4 = $buildData->id;


r($build->filterLinkedTest(array()))                     && p() && e('null');    //不传任何数据
r($build->filterLinkedTest(array($buildId1)))            && p() && e('null');    //迭代版本未关联需求和Bug
r($build->filterLinkedTest(array($buildId2)))            && p() && e('101');     //迭代版本关联需求和Bug
r($build->filterLinkedTest(array($buildId3)))            && p() && e('102');     //项目版本关联版本关联需求和Bug
r($build->filterLinkedTest(array($buildId4)))            && p() && e('null');    //项目版本关联版本未关联需求和Bug
r($build->filterLinkedTest(array($buildId1, $buildId2))) && p() && e('101');     //版本列表中关联需求和Bug
r($build->filterLinkedTest(array($buildId3, $buildId2))) && p() && e('101,102'); //版本列表中混合项目版本和迭代版本
