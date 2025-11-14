#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

zenData('scene')->loadYaml('treescene')->gen('20');
zenData('branch')->gen('10');
zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->buildTreeArray();
cid=18967
pid=1

*/

global $tester;
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();
$scenes = $tester->dao->update(TABLE_SCENE)->set("path= replace(`path`,',0,', ',')")->exec();

$sceneList   = array(array(1,2,3,4,5,6,7,8,9,10), array(11,12,13,14,15,16,17,18,19,20));
$sceneIdList = array(1, 2, 3, 11, 12, 13, 18, 19, 20);
$tree        = array(array(), array());

$testcase = new testcaseTest();

r($testcase->buildTreeArrayTest($tree[0], $sceneList[0], $sceneIdList[0])) && p() && e('/这个是测试场景1|1');                                                       // 测试构建 tree 0 场景 1-10 场景 id 1 的tree
r($testcase->buildTreeArrayTest($tree[0], $sceneList[0], $sceneIdList[1])) && p() && e('/这个是测试场景1|1,/这个是测试场景1/这个是测试场景2|2');                    // 测试构建 tree 0 场景 1-10 场景 id 2 的tree
r($testcase->buildTreeArrayTest($tree[0], $sceneList[0], $sceneIdList[2])) && p() && e('/这个是测试场景1|1,/这个是测试场景1/这个是测试场景2|2,/这个是测试场景3|3'); // 测试构建 tree 0 场景 1-10 场景 id 3 的tree

r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[3])) && p() && e('/这个是测试场景11|11');                                                                             // 测试构建 tree 1 场景 10-20 场景 id 11 的tree
r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[4])) && p() && e('/这个是测试场景11|11,/这个是测试场景11/这个是测试场景12|12');                                                        // 测试构建 tree 1 场景 10-20 场景 id 12 的tree
r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[5])) && p() && e('/这个是测试场景11|11,/这个是测试场景11/这个是测试场景12|12,/这个是测试场景11/这个是测试场景12/这个是测试场景13|13'); // 测试构建 tree 1 场景 10-20 场景 id 13 的tree
r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[6])) && p() && e('/这个是测试场景11|11/分支1/这个是测试场景18|18,/这个是测试场景11/这个是测试场景12|12,/这个是测试场景11/这个是测试场景12/这个是测试场景13|13');                                                                                         // 测试构建 tree 1 场景 10-20 场景 id 18 的tree
r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[7])) && p() && e('/这个是测试场景11|11/分支1/这个是测试场景18|18,/这个是测试场景11/这个是测试场景12|12,/这个是测试场景11/这个是测试场景12/这个是测试场景13|13,/分支1/这个是测试场景18/这个是测试场景19|19');                                                              // 测试构建 tree 1 场景 10-20 场景 id 19 的tree
r($testcase->buildTreeArrayTest($tree[1], $sceneList[1], $sceneIdList[8])) && p() && e('/这个是测试场景11|11/分支1/这个是测试场景18|18,/这个是测试场景11/这个是测试场景12|12,/这个是测试场景11/这个是测试场景12/这个是测试场景13|13,/分支1/这个是测试场景18/这个是测试场景19|19,/分支1/这个是测试场景18/这个是测试场景19/这个是测试场景20|20'); // 测试构建 tree 1 场景 10-20 场景 id 20 的tree
