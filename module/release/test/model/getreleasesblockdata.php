#!/usr/bin/env php
<?php

/**

title=taskModel->getReleasesBlockData();
timeout=0
cid=0

- 测试传入的projectID为0时，获取发布信息
 - 第1条的id属性 @79
 - 第1条的name属性 @发布79
- 测试传入的projectID为1时，获取发布信息
 - 第1条的id属性 @61
 - 第1条的name属性 @发布61
 - 第1条的project属性 @1
- 测试传入的projectID为2时，获取发布信息
 - 第1条的id属性 @62
 - 第1条的name属性 @发布62
 - 第1条的project属性 @2
- 测试传入的projectID为3时，获取发布信息
 - 第1条的id属性 @63
 - 第1条的name属性 @发布63
 - 第1条的project属性 @3
- 测试传入的projectID为4时，获取发布信息
 - 第1条的id属性 @64
 - 第1条的name属性 @发布64
 - 第1条的project属性 @4
- 测试传入的projectID为5时，获取发布信息
 - 第1条的id属性 @65
 - 第1条的name属性 @发布65
 - 第1条的project属性 @5
- 测试传入的projectID不存在时，获取发布信息 @0
- 测试传入的projectID为5时，获取按照id正序排序的发布信息
 - 第1条的id属性 @15
 - 第1条的name属性 @发布15
 - 第1条的project属性 @5
- 测试传入的projectID为5时，获取按照产品正序排序的发布信息
 - 第1条的id属性 @15
 - 第1条的name属性 @发布15
 - 第1条的project属性 @5
- 获取按照id正序排序的所有发布信息 @80
- 获取按照id正序排序的10条发布信息 @10
- 获取按照id正序排序的20条发布信息 @20

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('product')->loadYaml('product')->gen(10);
zenData('project')->loadYaml('project')->gen(10);
zenData('release')->loadYaml('release')->gen(100);

$projectIdList = array(0, 1, 2, 3, 4, 5, 100);
$orderByList   = array('id_asc', 'product_asc');
$limitList     = array(0, 10, 20);

global $tester;
$releaseModel = $tester->loadModel('release');
$releaseModel->app->user->admin = true;
$releaseModel->app->user->view  = new stdclass();
$releaseModel->app->user->view->products = '1,2,3';

r($releaseModel->getReleasesBlockData($projectIdList[0]))                                        && p('1:id,name')         && e('79,发布79');   // 测试传入的projectID为0时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[1]))                                        && p('1:id,name,project') && e('61,发布61,1'); // 测试传入的projectID为1时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[2]))                                        && p('1:id,name,project') && e('62,发布62,2'); // 测试传入的projectID为2时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[3]))                                        && p('1:id,name,project') && e('63,发布63,3'); // 测试传入的projectID为3时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[4]))                                        && p('1:id,name,project') && e('64,发布64,4'); // 测试传入的projectID为4时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[5]))                                        && p('1:id,name,project') && e('65,发布65,5'); // 测试传入的projectID为5时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[6]))                                        && p()                    && e('0');           // 测试传入的projectID不存在时，获取发布信息
r($releaseModel->getReleasesBlockData($projectIdList[5], $orderByList[0]))                       && p('1:id,name,project') && e('15,发布15,5'); // 测试传入的projectID为5时，获取按照id正序排序的发布信息
r($releaseModel->getReleasesBlockData($projectIdList[5], $orderByList[1]))                       && p('1:id,name,project') && e('15,发布15,5'); // 测试传入的projectID为5时，获取按照产品正序排序的发布信息
r(count($releaseModel->getReleasesBlockData($projectIdList[0], $orderByList[0], $limitList[0]))) && p()                    && e('80');          // 获取按照id正序排序的所有发布信息
r(count($releaseModel->getReleasesBlockData($projectIdList[0], $orderByList[0], $limitList[1]))) && p()                    && e('10');          // 获取按照id正序排序的10条发布信息
r(count($releaseModel->getReleasesBlockData($projectIdList[0], $orderByList[0], $limitList[2]))) && p()                    && e('20');          // 获取按照id正序排序的20条发布信息
